<?php

namespace Elementor;

use Thim_EL_Kit\GroupControlTrait;

class Thim_Ekit_Widget_Archive_Tours extends Thim_Ekit_Widget_List_Base {
	use GroupControlTrait;

	protected $attributes = array();
	/**
	 * Query args.
	 *
	 * @since 3.2.0
	 * @var   array
	 */
	protected $query_args = array();
	protected $current_permalink;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-archive-tours';
	}

	protected function get_html_wrapper_class() {
		return 'thim-ekits-archive-tours';
	}

	public function get_title() {
		return esc_html__( 'Archive Tours', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-archive-posts';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_ARCHIVE_TOUR );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_Setting',
			array(
				'label' => esc_html__( 'Setting', 'travel-booking' ),
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'   => esc_html__( 'Choose a template', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 'default',
				'options' => array( 'default' => esc_html__( 'Default', 'travel-booking' ) ) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'tours' ),
			)
		);
		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'travel-booking' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'selectors' => array(
					'body {{WRAPPER}} .thim-ekit-archive-tours__inner' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);
		$this->add_control(
			'number_posts',
			array(
				'label'   => esc_html__( 'Number Post', 'travel-booking' ),
				'min'     => -1,
				'max'     => 100,
				'step'    => 1,
				'default' => 8,
				'type'    => Controls_Manager::NUMBER,
			)
		);
		$this->add_control(
			'show_topbar',
			array(
				'label'        => esc_html__( 'Show Topbar', 'travel-booking' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'travel-booking' ),
				'label_off'    => esc_html__( 'Hide', 'travel-booking' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->end_controls_section();
		$this->register_navigation_archive();
		$this->update_control(
			'pagination_type',
			array(
				'label'              => esc_html__( 'Pagination', 'travel-booking' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => array(
					''        => esc_html__( 'None', 'travel-booking' ),
					'numbers' => esc_html__( 'Numbers', 'travel-booking' ),
				),
				'frontend_available' => true,
			)
		);
		$this->remove_control(
			'pagination_numbers_shorten',
			array(
				'label'     => esc_html__( 'Shorten', 'travel-booking' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'pagination_type' => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);
		$this->_register_style_layout();
		$this->register_style_pagination_archive( '.thim-ekit-archive-tours__pagination' );
	}
	protected function _register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label' => esc_html__( 'Layout', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-tours-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 35,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-tours-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		global $wp_query;
		$query_vars                   = $wp_query->query_vars;
		$query_vars                   = apply_filters( 'thim_ekit/elementor/archive_tours/query_posts/query_vars', $query_vars );
		$query_vars['posts_per_page'] = $settings['number_posts'] ?? 8;
		if ( $query_vars !== $wp_query->query_vars ) {
			$query = new \WP_Query( $query_vars );
		} else {
			$query = $wp_query;
		}
		$class_item = 'thim-ekits-tours__article';
		?>
		<div class="thim-ekit-archive-tours">
			<?php
			if ( $settings['show_topbar'] == 'yes' ) :
				echo '<div class="thim-ekits-archive-tours__topbar">';
				add_filter( 'travel_tours_posts_per_page', array( $this, 'travel_tours_posts_per_page' ) );
				add_filter( 'travel_booking_show_label_sort', array( $this, 'travel_booking_show_label_sort' ) );
				do_action( 'travelbooking_result_count' );
				echo '</div>';
			endif;
			if ( $query->found_posts ) {
				?>
				<ul class="thim-ekit-archive-tours__inner">
					<?php
					if ( $query->in_the_loop ) { // It's the global `wp_query` it self. and the loop was started from the theme.
						$this->current_permalink = get_permalink();
						if ( $settings['template_id'] != 'default' ) {
							echo '<li class="tour">';
							\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
							echo '</li>';
						} else {
							tb_get_file_template( 'content-tour.php', false );
						}
					} else {
						while ( $query->have_posts() ) {
							$query->the_post();

							$this->current_permalink = get_permalink();
							if ( $settings['template_id'] != 'default' ) {
								echo '<li class="tour">';
								\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
								echo '</li>';
							} else {
								tb_get_file_template( 'content-tour.php', false );
							}
						}
					}

					wp_reset_postdata();
					?>
				</ul>
				<?php
				$this->render_loop_footer( $query, $settings );
			} else {
				echo '<p class="woocommerce-info">No tours were found matching your selection.</p>';
			}
			?>
		</div>
		<?php
	}

	public function travel_booking_show_label_sort() {
		return true;
	}

	public function travel_tours_posts_per_page() {
		$settings       = $this->get_settings_for_display();
		$posts_per_page = $settings['number_posts'] ?? 8;
		return $posts_per_page;
	}

	public function render_loop_footer( $query, $settings ) {
		if ( '' === $settings['pagination_type'] ) {
			return;
		}
		$page_limit = $query->max_num_pages;
		if ( 2 > $page_limit ) {
			return;
		}

		$has_numbers   = in_array( $settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = in_array( $settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ) );

		$load_more_type = $settings['pagination_type'];

		if ( $settings['pagination_type'] === '' ) {
			$paged = 1;
		} else {
			$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
		}

		$links = array();
		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $paged,
				'total'              => $page_limit,
				'prev_next'          => true,
				'show_all'           => 'yes',
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'travel-booking' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;

				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $query, $paged, $page_limit, $settings );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}
		?>
			<nav class="thim-ekit-archive-tours__pagination" aria-label="<?php esc_attr_e( 'Pagination', 'travel-booking' ); ?>">
				<?php echo wp_kses_post( implode( PHP_EOL, $links ) ); ?>
			</nav>
		<?php
	}

	public function get_posts_nav_link( $query, $paged, $page_limit = null, $settings = array() ) {
		if ( ! $page_limit ) {
			$page_limit = $query->max_num_pages;
		}

		$return = array();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;

			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $settings['pagination_prev_label'] );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $settings['pagination_prev_label'] );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $settings['pagination_next_label'] );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $settings['pagination_next_label'] );
		}

		return $return;
	}

	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id']    = absint( wp_unslash( $_GET['preview_id'] ) );
				$query_args['preview_nonce'] = sanitize_text_field( wp_unslash( $_GET['preview_nonce'] ) );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}
}
