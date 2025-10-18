<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Review Tour
 *
 * @author  physcode
 * @version 1.2.7
 */
class TravelPhysReviewTour {

	public $config;

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
		add_action( 'wp_footer', array( $this, 'add_submit_review_form_popup' ) );
		add_filter( 'comments_template', array( $this, 'comments_template' ), 999999 );
		add_action( 'woocommerce_review_meta', array( $this, 'add_review_title_to_comment_item' ) );
		add_action( 'woocommerce_review_after_comment_text', array( $this, 'add_review_images' ) );
		add_action( 'pre_get_comments', array( $this, 'filter_comment_query' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_comment_metaboxes' ), 10, 2 );
		add_action( 'edit_comment', array( $this, 'save_comment_metaboxes' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'wc_get_template', array( $this, 'change_review_template' ), 10, 5 );
	}

	/**
	 * @param $template
	 * @param $template_name
	 * @param $args
	 * @param $template_path
	 * @param $default_path
	 *
	 * @return mixed|string
	 */
	public function change_review_template( $template, $template_name, $args, $template_path, $default_path ) {
		if ( $template_name === 'single-product/review.php' ) {
			$template = TB_PHYS_TEMPLATE_PATH_DEFAULT . 'single-tour/review.php';
		}

		return $template;
	}

	/**
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		if ( defined( 'TRAVEL_TOUR_DEBUG' ) ) {
			$min = '';
		} else {
			$min = '.min';
		}

		//date time
		wp_register_script(
			'tour-product-review',
			TOUR_BOOKING_PHYS_URL . 'assets/dist/js/admin/product-review' . $min . '.js',
			array(),
			uniqid(),
			true
		);
		wp_enqueue_script( 'tour-product-review' );
	}

	/**
	 * @param $comment_id
	 * @param $data
	 *
	 * @return void
	 */
	public function save_comment_metaboxes( $comment_id, $data ) {
		if ( isset( $_POST['tour_review_title'] ) ) {
			update_comment_meta( $comment_id, 'tour_review_title', sanitize_text_field( $_POST['tour_review_title'] ) );
		}

		if ( isset( $_POST['tour_review_images'] ) ) {
			$value = $_POST['tour_review_images'];
			if ( is_string( $value ) ) {
				$value = explode( ',', $value );
			}
			update_comment_meta( $comment_id, 'tour_review_images', $value );
		}
	}

	/**
	 * @param $type
	 * @param $comment
	 *
	 * @return void
	 */
	public function add_comment_metaboxes( $type, $comment ) {
		if ( $type !== 'comment' ) {
			return;
		}

		if ( $comment->comment_type !== 'review' ) {
			return;
		}

		$product_id = $comment->comment_post_ID;

		if ( empty( $product_id ) ) {
			return;
		}
		$product = wc_get_product( $product_id );

		if ( empty( $product ) ) {
			return;
		}

		if ( $product->get_type() !== 'tour_phys' ) {
			return;
		}

		if ( get_option( 'tour_enable_tour_review' ) !== 'yes' ) {
			return;
		}

		add_meta_box(
			'tour review title',
			esc_html__( 'Title', 'travel-booking' ),
			array( $this, 'render_review_title' ),
			array( 'comment' ),
			'normal',
			'low',
		);

		add_meta_box(
			'tour review image',
			esc_html__( 'Images', 'travel-booking' ),
			array( $this, 'render_review_images' ),
			array( 'comment' ),
			'normal',
			'low',
		);
	}

	/**
	 * @param $comment
	 *
	 * @return void
	 */
	public function render_review_title( $comment ) {
		$comment_id = $comment->comment_ID;
		$value      = get_comment_meta( $comment_id, 'tour_review_title', true );

		if ( empty( $value ) ) {
			$value = '';
		}
		?>
		<input type="text" name="tour_review_title" value="<?php echo esc_attr( $value ); ?>">
		<?php
	}

	public function render_review_images( $comment ) {
		$comment_id = $comment->comment_ID;
		$image_ids  = get_comment_meta( $comment_id, 'tour_review_images', true );
		$file_name  = 'metabox-review-images.php';
		$path_file  = TB_PHYS_TEMPLATE_PATH_ADMIN . $file_name;
		if ( file_exists( $path_file ) ) {
			include $path_file;
		}
	}

	/**
	 * @param $query
	 *
	 * @return void
	 */
	public function filter_comment_query( $query ) {
		if ( isset( $_GET['photos_only'] ) && $_GET['photos_only'] === 'yes' ) {
			$query->query_vars['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => 'tour_review_images',
					'compare' => 'EXISTS',
				),
			);
		}

		if ( isset( $_GET['review_sort_by'] ) && ! empty( $_GET['review_sort_by'] ) ) {
			if ( $_GET['review_sort_by'] === 'newest' ) {
				$query->query_vars['orderby'] = 'comment_date_gmt';
				$query->query_vars['order']   = 'DESC';
			}

			if ( $_GET['review_sort_by'] === 'oldest' ) {
				$query->query_vars['orderby'] = 'comment_date_gmt';
				$query->query_vars['order']   = 'ASC';
			}

			if ( $_GET['review_sort_by'] === 'top-review' ) {
				$query->query_vars['meta_key'] = 'rating';
				$query->query_vars['orderby']  = 'meta_value_num';
				$query->query_vars['order']    = 'DESC';
			}
		}
	}

	/**
	 * @param $comment
	 *
	 * @return void
	 */
	public function add_review_images( $comment ) {
		global $post;

		if ( ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );

			if ( $product->get_type() === TB_PHYS_PRODUCT_TYPE && get_option( 'tour_enable_tour_review' ) === 'yes' ) {
				$comment_id = $comment->comment_ID;

				$images = get_comment_meta( $comment_id, 'tour_review_images', true );

				if ( ! empty( $images ) ) {
					?>
					<ul class="tour-review-images">
						<?php
						foreach ( $images as $id ) {
							?>
							<li>
								<img src="<?php echo wp_get_attachment_image_url( $id ); ?>" alt="#">
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}
			}
		}
	}

	/**
	 * @param $comment
	 *
	 * @return void
	 */
	public function add_review_title_to_comment_item( $comment ) {
		global $post;

		if ( ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );

			if ( $product->get_type() === TB_PHYS_PRODUCT_TYPE && get_option( 'tour_enable_tour_review' ) === 'yes' ) {
				$comment_id = $comment->comment_ID;

				$title = get_comment_meta( $comment_id, 'tour_review_title', true );
				if ( ! empty( $title ) ) {
					?>
					<h2 class="tour-review-title"><?php echo esc_html( $title ); ?></h2>
					<?php
				}
			}
		}
	}

	/**
	 * @param $template
	 *
	 * @return mixed|string
	 */
	public function comments_template( $template ) {
		global $post;

		if ( ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );
			if ( $product && $product->get_type() === TB_PHYS_PRODUCT_TYPE && get_option( 'tour_enable_tour_review' ) === 'yes' ) {
				$template = TB_PHYS_TEMPLATE_PATH_DEFAULT . 'single-tour/product-reviews.php';
			}
		}

		return $template;
	}

	/**
	 * @return false|void
	 */
	public function add_submit_review_form_popup() {
		global $post;

		if ( empty( $post ) ) {
			return;
		}

		if ( ! is_product() ) {
			return;
		}

		if ( get_option( 'tour_enable_tour_review' ) !== 'yes' ) {
			return;
		}

		if ( ! is_user_logged_in() && get_option( 'require_login_to_submit_review' ) === 'yes' ) {
			return;
		}

		$product = wc_get_product( get_queried_object_id() );

		if ( empty( $product ) || $product->get_type() !== TB_PHYS_PRODUCT_TYPE ) {
			return false;
		}

		if ( get_option( 'tour_enable_tour_review' ) !== 'yes' ) {
			return false;
		}

		$max_images = get_option( 'tour_max_images', 10 );
		?>

		<div id="tour-booking-review-form-popup">
			<div class="bg-overlay"></div>
			<form id="tour-booking-submit-review-form"
					data-product-id="<?php echo esc_attr( get_queried_object_id() ); ?>">
				<header>
					<h3><?php esc_html_e( 'Write a review', 'travel-booking' ); ?></h3>
					<div class="close-form-btn">
						<span class="dashicons dashicons-no"></span>
					</div>
				</header>
				<main>
					<div class="review-rating field">
						<label for="review-rating"><?php esc_html_e( 'Rate your experience *', 'travel-booking' ); ?></label>
						<input type="hidden" name="review-rating" value="">
						<div class="rating-star">
							<?php
							for ( $i = 1; $i <= 5; $i++ ) {
								?>
								<a class="rating-star-item" href="#" data-star-rating="<?php echo esc_attr( $i ); ?>">
								</a>
								<?php
							}
							?>
						</div>
					</div>
					<?php
					if ( ! is_user_logged_in() ) {
						?>
						<div class="review-name field">
							<label for="review-name"><?php esc_html_e( 'Name *', 'travel-booking' ); ?></label>
							<input type="text" name="review-name" id="review-name">
						</div>
						<div class="review-email field">
							<label for="review-email"><?php esc_html_e( 'Email *', 'travel-booking' ); ?></label>
							<input type="text" name="review-email" id="review-email">
						</div>
						<?php
					}
					?>

					<div class="review-content field">
						<label for="review-content"><?php esc_html_e( 'Leave a review *', 'travel-booking' ); ?></label>
						<textarea name="review-content" id="review-content" cols="30" rows="5"></textarea>
					</div>

					<div class="review-title field">
						<label for="review-title"><?php esc_html_e( 'Give your review a title *', 'travel-booking' ); ?></label>
						<input type="text" name="review-title" id="review-title">
					</div>


					<div class="tour-gallery-review" data-tour-id="<?php echo esc_attr( $post->ID ); ?>">
						<div class="select-images">
							<label for="tour_review-image">
								<?php
								printf( esc_html( _n( 'Uploads up to %s image', 'Upload up to %s images', $max_images, 'travel-booking' ) ), $max_images );
								?>
							</label>
							<div class="review-notice">
							</div>
							<div class="gallery-preview">
							</div>
							<label class="upload-images">
								<span><?php esc_html_e( 'Upload', 'travel-booking' ); ?></span>
								<input type="file" accept="image/*" multiple="multiple" name="review-image[]"
										id="tour-review-image">
							</label>
						</div>
					</div>
				</main>
				<footer>
					<p class="notice"></p>
					<div class="submit">
						<button type="button"><?php esc_html_e( 'Send', 'travel-booking' ); ?></button>
						<span class="tour-spinner"></span>
					</div>
				</footer>
			</form>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'travel-tour/v1',
			'/update-review',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_review' ),
				'args'                => array(),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return false|string|WP_REST_Response
	 */
	public function update_review( \WP_REST_Request $request ) {
		if ( get_option( 'tour_enable_tour_review' ) !== 'yes' ) {
			return $this->error( esc_html__( 'Tour review popup is disabled.', 'travel-booking' ), 401 );
		}

		$is_user_logined = is_user_logged_in();
		if ( ! $is_user_logined && get_option( 'require_login_to_submit_review' ) === 'yes' ) {
			return $this->error( esc_html__( 'You must log in to submit a review.', 'travel-booking' ), 401 );
		}

		$params = $request->get_params();

		if ( ! isset( $params['product_id'] ) ) {
			return $this->error( esc_html__( 'The product id is required.', 'travel-booking' ), 400 );
		}

		if ( ! isset( $params['rating'] ) ) {
			return $this->error( esc_html__( 'The rating is required.', 'travel-booking' ), 400 );
		}

		if ( ! in_array( $params['rating'], array( 1, 2, 3, 4, 5 ) ) ) {
			return $this->error( esc_html__( 'The rating is invalid.', 'travel-booking' ), 400 );
		}

		$review_check_args = array(
			'post_id' => $params['product_id'],
			'count'   => true,
		);

		if ( ! $is_user_logined ) {
			if ( ! isset( $params['name'] ) ) {
				return $this->error( esc_html__( 'The name is required.', 'travel-booking' ), 400 );
			}

			if ( ! isset( $params['email'] ) ) {
				return $this->error( esc_html__( 'The email is required.', 'travel-booking' ), 400 );
			}

			if ( ! is_email( $params['email'] ) ) {
				return $this->error( esc_html__( 'The email is invalid.', 'travel-booking' ), 400 );
			}

			$review_check_args['author_email'] = $params['email'];
		} else {
			$current_user                      = wp_get_current_user();
			$review_check_args['author_email'] = $current_user->user_email;
		}

		$review_check = get_comments( $review_check_args );

		if ( ! empty( $review_check ) ) {
			return $this->error( esc_html__( 'You have already submitted a review before.', 'travel-booking' ), 400 );
		}

		if ( ! isset( $params['content'] ) ) {
			return $this->error( esc_html__( 'The review content is required.', 'travel-booking' ), 400 );
		}

		if ( ! isset( $params['title'] ) ) {
			return $this->error( esc_html__( 'The review title is required.', 'travel-booking' ), 400 );
		}

		$comment_args = array(
			'comment_post_ID'    => $params['product_id'],
			'comment_author_url' => '',
			'comment_content'    => sanitize_textarea_field( $params['content'] ),
			'comment_type'       => 'review',
			'comment_parent'     => 0,
			'comment_author_IP'  => '',
			'comment_agent'      => '',
			'comment_date'       => date( 'Y-m-d H:i:s' ),
			'comment_approved'   => 1,
		);

		if ( $is_user_logined ) {
			$user_id = get_current_user_id();

			$user                                 = get_userdata( $user_id );
			$comment_args['comment_author']       = $user->display_name;
			$comment_args['comment_author_email'] = $user->user_email;
			$comment_args['user_id']              = $user_id;
		} else {
			$comment_args['comment_author']       = $params['name'];
			$comment_args['comment_author_email'] = $params['email'];
		}

		$require_approved_by_admin = get_option( 'require_approved_by_admin' );
		if ( $require_approved_by_admin === 'yes' ) {
			$comment_args['comment_approved'] = 0;
		}

		$comment_id = wp_insert_comment(
			$comment_args
		);

		if ( ! $comment_id ) {
			return $this->error( esc_html__( 'Could not create review.', 'travel-booking' ), 400 );
		}

		//Update comment meta
		update_comment_meta( $comment_id, 'tour_review_title', sanitize_text_field( $params['title'] ) );
		update_comment_meta( $comment_id, 'rating', sanitize_text_field( $params['rating'] ) );

		$images = $params['base64_images'] ?? '';

		if ( ! empty( $images ) ) {
			$upload_dir  = wp_upload_dir();
			$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

			$attachment_ids = array();

			foreach ( $images as $image ) {
				$img             = preg_replace( '/^data:image\/[a-z]+;base64,/', '', $image['base64'] );
				$img             = str_replace( ' ', '+', $img );
				$decoded         = base64_decode( $img );
				$filename        = $image['name'];
				$file_type       = $image['type'];
				$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

				$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

				if ( $upload_file ) {
					$attachment = array(
						'post_mime_type' => $file_type,
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit',
						'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename ),
					);

					$attachment_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );

					if ( ! is_wp_error( $attachment_id ) && $attachment_id ) {
						$attachment_ids [] = $attachment_id;
					}
				}
			}
			if ( count( $attachment_ids ) ) {
				update_comment_meta( $comment_id, 'tour_review_images', $attachment_ids );
			}
		}
		wp_update_comment_count( $params['product_id'] );

		if ( $require_approved_by_admin === 'yes' ) {
			$approve_mgs = esc_html__( 'Your review is awaiting approval.', 'travel-booking' );
		} else {
			$approve_mgs = '';
		}

		return $this->success(
			esc_html__( 'Submit review successfully.', 'travel-booking' ),
			array(
				'comment_id'                => $comment_id,
				'redirect_url'              => '#comment-' . $comment_id,
				'require_approved_by_admin' => $require_approved_by_admin,
				'approve_mgs'               => $approve_mgs,
			)
		);
	}

	/**
	 * @param string $msg
	 * @param $status_code
	 *
	 * @return WP_REST_Response
	 */
	public function error( string $msg = '', $status_code = 404 ) {
		return new WP_REST_Response(
			array(
				'status'      => 'error',
				'msg'         => $msg,
				'status_code' => $status_code,
			)
			//            $status_code
		);
	}


	/**
	 * @param string $msg
	 * @param array $data
	 *
	 * @return WP_REST_Response
	 */
	public function success( string $msg = '', array $data = array() ) {
		return new WP_REST_Response(
			array(
				'status' => 'success',
				'msg'    => $msg,
				'data'   => $data,
			),
			200
		);
	}

	/**
	 * @param $rating
	 * @param $post_id
	 *
	 * @return string|null
	 */
	public function get_review_count_by_rating( $rating, $post_id ) {
		global $wpdb;
		$comment_tbl      = $wpdb->comments;
		$comment_meta_tbl = $wpdb->commentmeta;

		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) meta_value FROM $comment_meta_tbl LEFT JOIN $comment_tbl ON 
                $comment_meta_tbl.comment_id = $comment_tbl.comment_ID WHERE meta_key = 'rating' AND comment_post_ID = %s 
              AND comment_approved = '1' AND meta_value = %s",
				$post_id,
				$rating
			)
		);
	}

	/**
	 * @return TravelPhysReviewTour|null
	 */
	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}

TravelPhysReviewTour::instance();

