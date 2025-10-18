<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class tb_search_widget_phys extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'tb_search_widget',
			'description' => __( 'Tour Search', 'travel-booking' ),
		);
		parent::__construct( 'tb_search_widget_phys', 'Tour Search', $widget_ops );
	}

	public function form( $instance ) {
		$defaults            = array(
			'hide_tour_name'      => 'show',
			'hide_tour_type'      => 'show',
			'show_tour_code'      => 'hide',
			'show_date'           => 'hide',
			'show_duration'       => 'hide',
			'show_rating'         => 'hide',
			'show_filter_ratting' => 'hide',
			'show_filter_price'   => 'hide',
			'load_via_rest'       => '',
		);
		$instance            = wp_parse_args( (array) $instance, $defaults );
		$hide_tour_name      = $instance['hide_tour_name'];
		$hide_tour_type      = $instance['hide_tour_type'];
		$show_tour_code      = $instance['show_tour_code'];
		$show_date           = $instance['show_date'];
		$show_duration       = $instance['show_duration'];
		$show_filter_ratting = $instance['show_filter_ratting'];
		$show_filter_price   = $instance['show_filter_price'];
		$load_via_rest       = $instance['load_via_rest'];
		?>
        <p>
            <label><?php echo esc_html__( 'Tour Name', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'hide_tour_name' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $hide_tour_name, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $hide_tour_name, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <label><?php echo esc_html__( 'Tour Type', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'hide_tour_type' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $hide_tour_type, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $hide_tour_type, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <label><?php echo esc_html__( 'Show Tour Code', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'show_tour_code' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $show_tour_code, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $show_tour_code, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <label><?php echo esc_html__( 'Show Date', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'show_date' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $show_date, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $show_date, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <label><?php echo esc_html__( 'Show Duration', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'show_duration' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $show_duration, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $show_duration, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <label><?php echo esc_html__( 'Show Filter Ratting', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'show_filter_ratting' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $show_filter_ratting, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $show_filter_ratting, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <label><?php echo esc_html__( 'Show Filter Price', 'travel-booking' ); ?></label>
            <select name="<?php echo ent2ncr( $this->get_field_name( 'show_filter_price' ) ); ?>" class="widefat">
                <option value="hide" <?php selected( $show_filter_price, 'hide' ); ?>><?php esc_html_e( 'Hide', 'travel-booking' ); ?></option>
                <option value="show" <?php selected( $show_filter_price, 'show' ); ?>><?php esc_html_e( 'Show', 'travel-booking' ); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( 'on', $load_via_rest, true ); ?>
                   id="<?php echo $this->get_field_id( 'load_via_rest' ); ?>"
                   name="<?php echo $this->get_field_name( 'load_via_rest' ); ?>"/>
            <label for="<?php echo $this->get_field_id( 'load_via_rest' ); ?>">
				<?php esc_html_e( 'Load widget via REST', 'travel-booking' ); ?>
            </label>
        </p>
		<?php
	}

	/**
	 * @param $args
	 * @param $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$load_via_rest = $instance['load_via_rest'] ?? '';

		$min_price_setting = get_option( 'advanced_search_min_price', 0 );
		$max_price_setting = get_option( 'advanced_search_max_price', 100 );

		$data = array(
			'widget'         => 'tb_search_widget_phys',
			'instance'       => wp_json_encode( $instance ),
			'tour_name'      => $_GET['name_tour'] ?? '',
			'tour_code'      => $_GET['tour_code'] ?? '',
			'tour_tax_param' => get_query_var( 'tourtax' ),
			'date_range'     => $_GET['date_range'] ?? '',
			'destination'    => $_GET['destination'] ?? '',
			'duration'       => $_GET['duration'] ?? '',
			'tour_min_price' => $_GET['tour_min_price'] ?? $min_price_setting,
			'tour_max_price' => $_GET['tour_max_price'] ?? $max_price_setting,
			'rating'         => $_GET['tour_rating'] ?? '',
		);

		if ( ! is_admin() && $load_via_rest ) {
			?>
            <div class="tour-widget-wrapper tour-widget-wrapper__restapi"
                 data-widget="<?php echo htmlentities( wp_json_encode( $data ) ); ?>">
				<?php $this->tour_skeleton_animation_html( 5 ); ?>
                <div class="tour-widget-loading-change"></div>
            </div>
			<?php
		} else {
			$content = $this->tour_rest_api_content( $instance, $data );
			?>
            <div class="tour-widget-wrapper" data-widget="<?php htmlentities( wp_json_encode( $data ) ); ?>">
                <div class="tour-widget-loading-change"></div>
				<?php
				if ( is_wp_error( $content ) ) {
					echo $content->get_error_message();
				} else {
					echo $content;
				}
				?>
            </div>
			<?php
		}

		$show_filter_ratting = $instance['show_filter_ratting'] ?? 'hide';
		$show_filter_price   = $instance['show_filter_price'] ?? 'hide';

		if ( $show_filter_ratting == 'show' ) {
			wp_enqueue_style( 'barrating' );
			wp_enqueue_script( 'barrating-js' );
			wp_enqueue_script( 'widget-search-js' );
		}

		if ( $show_filter_price == 'show' ) {
			wp_enqueue_style( 'slider-range' );
			wp_enqueue_script( 'slider-range-js' );
			wp_enqueue_script( 'widget-search-js' );
		}

		wp_enqueue_style( 'style-daterangepicker' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'moment-js' );
		wp_enqueue_script( 'daterangepicker-js' );
		wp_enqueue_script( 'tour-search' );
		$price_format = get_woocommerce_price_format();

		if ( $price_format === '%1$s%2$s' ) {
			$price_format = 'left';
		} elseif ( $price_format === '%2$s%1$s' ) {
			$price_format = 'right';
		} elseif ( $price_format === '%1$s&nbsp;%2$s' ) {
			$price_format = 'left-space';
		} else {
			$price_format = 'right-space';
		}

		$tour_page_url = '';
		if ( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) {
			$tour_page_url = get_the_permalink( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) );
		}
		wp_localize_script( 'tour-search', 'TOUR_SEARCH_OBJ', array(
				'currency_symbol'    => get_woocommerce_currency_symbol(),
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => $price_format,
				'home_url'           => home_url(),
				'tour_base_slug'     => TravelPhysUrl::get_tour_base_slug(),
				'tour_page_url'      => $tour_page_url
			)
		);
	}

	/**
	 * @param $new_instance
	 * @param $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                        = $old_instance;
		$instance['hide_tour_name']      = sanitize_text_field( $new_instance['hide_tour_name'] );
		$instance['hide_tour_type']      = sanitize_text_field( $new_instance['hide_tour_type'] );
		$instance['show_tour_code']      = sanitize_text_field( $new_instance['show_tour_code'] );
		$instance['show_date']           = sanitize_text_field( $new_instance['show_date'] );
		$instance['show_duration']       = sanitize_text_field( $new_instance['show_duration'] );
		$instance['show_filter_ratting'] = sanitize_text_field( $new_instance['show_filter_ratting'] );
		$instance['show_filter_price']   = sanitize_text_field( $new_instance['show_filter_price'] );
		$instance['load_via_rest']       = sanitize_text_field( $new_instance['load_via_rest'] ?? '' );

		return $instance;
	}

	public function tour_skeleton_animation_html( $count_li = 3, $width = 'random', $styleli = '', $styleul = '' ) {
		?>
        <ul class="tour-skeleton-animation" style="<?php echo esc_attr( $styleul ); ?>">
			<?php for ( $i = 0; $i < absint( $count_li ); $i ++ ) : ?>
                <li style="width: <?php echo esc_attr( $width === 'random' ? wp_rand( 90, 100 ) . '%' : $width ); ?>; <?php echo ! empty( $styleli ) ? $styleli : ''; ?>"></li>
			<?php endfor; ?>
        </ul>

		<?php
	}

	/**
	 * @param $instance
	 * @param $params
	 *
	 * @return false|string
	 */
	public function tour_rest_api_content( $instance, $data ) {
		$hide_tour_name      = $instance['hide_tour_name'] ?? 'show';
		$hide_tour_type      = $instance['hide_tour_type'] ?? 'show';
		$show_tour_code      = $instance['show_tour_code'] ?? 'hide';
		$show_date           = $instance['show_date'] ?? 'hide';
		$show_duration       = $instance['show_duration'] ?? 'hide';
		$show_filter_ratting = $instance['show_filter_ratting'] ?? 'hide';
		$show_filter_price   = $instance['show_filter_price'] ?? 'hide';
		$tour_tax_param      = $data['tour_tax_param'] ?? '';
		ob_start();
//		do_action( 'travel-booking/filter-courses/layout', $data );
		?>
        <!--    Display widget-->
        <div class="search_tour">
            <div class="form-block block-after-indent">
                <h3 class="form-block_title"><?php _e( 'Search Tour', 'travel-booking' ); ?></h3>
                <div class="form-block__description"><?php _e( 'Find your dream tour today!', 'travel-booking' ); ?></div>
                <form method="get" action="<?php echo home_url(); ?>" id="search_tour_form">
                    <input type="hidden" name="tour_search" value="1">
					<?php
					$orderby = isset( $_GET['orderbyt'] ) ? wc_clean( $_GET['orderbyt'] ) : '';
					echo sprintf( ' <input type="hidden" name="orderbyt" value="%s">', $orderby );
					if ( $hide_tour_name != 'hide' ) {
						$tour_name = '';
						if ( isset( $data['tour_name'] ) ) {
							$tour_name = $data['tour_name'];
						}
						?>
                        <div class="form-field-input">
                            <input type="text" placeholder="<?php esc_attr_e( 'Search Tour', 'travel-booking' ); ?>"
                                   value="<?php echo esc_attr( $tour_name ); ?>"
                                   name="name_tour">
                        </div>
						<?php
					}
					if ( $show_tour_code == 'show' ) {
						$tour_code = '';
						if ( isset( $data['tour_code'] ) ) {
							$tour_code = $data['tour_code'];
						}
						?>
                        <div class="form-field-input">
                            <input type="text" placeholder="<?php _e( 'Tour Code', 'travel-booking' ); ?>"
                                   value="<?php echo $tour_code; ?>" name="tour_code">
                        </div>
						<?php
					}
					if ( $hide_tour_type == 'show' ) {
						?>
                        <div class="form-field-select">
                            <select name="tourtax[tour_phys]">
                                <option value="0"><?php _e( 'Tour Type', 'travel-booking' ); ?></option>
								<?php
								$taxonomy   = 'tour_phys'; // taxonomy slug
								$tour_terms = get_terms( $taxonomy );
								if ( $tour_terms ) {
									foreach ( $tour_terms as $term ) {
										if ( is_array( $tour_tax_param ) && $term->slug == $tour_tax_param[ $taxonomy ] ) {
											echo '<option value="' . $term->slug . '" selected="selected">' . $term->name . '</option>';
										} else {
											echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
										}
									}
								}
								?>
                            </select>
                        </div>
						<?php
					}
					if ( get_option( 'tour_search_by_attributes' ) ) {
						$option_attribute_to_search = get_option( 'tour_search_by_attributes' );
						foreach ( $option_attribute_to_search as $attribute_to_search ) {
							$tax_attribute      = get_taxonomy( $attribute_to_search );
							$terms_of_attribute = get_terms( $attribute_to_search );
							if ( ( ! empty( $terms_of_attribute ) && ! is_wp_error( $terms_of_attribute ) ) && count( $terms_of_attribute ) > 0 ) {
								echo '<div class="form-field-select"><select name="tourtax[' . $attribute_to_search . ']">';
								echo '<option value="0">' . esc_html__( $tax_attribute->labels->singular_name, 'travel-booking' ) . '</option>';
								foreach ( $terms_of_attribute as $term ) {
									if ( is_array( $tour_tax_param ) && $term->slug == $tour_tax_param[ $attribute_to_search ] ) {
										echo '<option value="' . $term->slug . '" selected="selected">' . $term->name . '</option>';
									} else {
										echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
									}
								}
								echo '</select></div>';
							}
						}
					}
					?>

					<?php
					if ( $show_date === 'show' ) {
						$file = TB_PHYS_TEMPLATE_PATH_DEFAULT . 'tour-search/fields/date-time.php';
						if ( file_exists( $file ) ) {
							include $file;
						}
					}

					if ( $show_duration === 'show' ) {
						$file = TB_PHYS_TEMPLATE_PATH_DEFAULT . 'tour-search/fields/duration.php';
						if ( file_exists( $file ) ) {
							include $file;
						}
					}

					if ( $show_filter_ratting == 'show' ) {
						/*** Filter by star ***/
						$show_filter_tour_rating = apply_filters( 'phys_tour_filter_rating', true );

						if ( $show_filter_tour_rating ) {
							$tour_rating_arr = array( 1, 2, 3, 4, 5 );

							$tour_rating = '';
							if ( isset( $data['tour_rating'] ) ) {
								$tour_rating = $data['tour_rating'];
							}
							?>
                            <div class="tour-rating">
                                <span><?php _e( 'Rating', 'travel-booking' ); ?></span>
                                <select name="tour_rating" id="tour_rating">
                                    <option value=""></option>
									<?php
									foreach ( $tour_rating_arr as $rating ) {
										$tour_rating_selected = '';

										if ( $tour_rating == $rating ) {
											$tour_rating_selected = 'selected';
										} else {
											$tour_rating_selected = '';
										}

										echo '<option value="' . $rating . '" ' . $tour_rating_selected . '>' . $rating . '</option>';
									}
									?>
                                </select>
                            </div>
							<?php
						}
					}
					?>
					<?php
					if ( $show_filter_price == 'show' ) {
						$show_filter_price_tour = apply_filters( 'phys_tour_filter_price', true );

						if ( $show_filter_price_tour ) {
							global $wpdb;

							$tax_query  = array();
							$meta_query = array();

							$tax_query[] = array(
								'taxonomy' => 'product_type',
								'terms'    => array( 'tour_phys' ),
								'field'    => 'slug',
								'operator' => 'IN',
							);

							$meta_query = new WP_Meta_Query( $meta_query );
							$tax_query  = new WP_Tax_Query( $tax_query );

							$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
							$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

							$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
							$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
							$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
							AND {$wpdb->posts}.post_status = 'publish'
							AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
							AND price_meta.meta_value > 0 ";
							$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

							$price_range = $wpdb->get_row( $sql );
							?>
                            <div class="form-field-input" style="text-align: left">
                                <p>
                                    <span><?php _e( ' Price range', 'travel-booking' ); ?></span>
									<?php
									$start_price = $price_range->min_price;
									$end_price   = $price_range->max_price;

									if ( isset( $data['tour_min_price'] ) && isset( $data['tour_max_price'] ) ) {
										$start_price = $data['tour_min_price'];
										$end_price   = $data['tour_max_price'];
									}

									echo sprintf( '%s - %s', TravelPhysUtility::tour_format_price( $start_price, 'tour-min-price' ), TravelPhysUtility::tour_format_price( $end_price, 'tour-max-price' ) );
									?>
                                </p>
                                <div id="tour-price-range"></div>
                                <input type="hidden" name="tour_min_price" value="<?php echo $start_price; ?>">
                                <input type="hidden" name="tour_max_price" value="<?php echo $end_price; ?>">
                            </div>
                            <input type="hidden" name="tour_start_price_fitler"
                                   value="<?php echo $price_range->min_price; ?>">
                            <input type="hidden" name="tour_end_price_filter"
                                   value="<?php echo $price_range->max_price; ?>">
							<?php
						}
					}
					?>

                    <input type="hidden" name="lang" value="<?php echo isset( $_GET['lang'] ) ? $_GET['lang'] : ''; ?>">
                    <button type="submit"><?php _e( 'Find Tours', 'travel-booking' ); ?></button>
                </form>


            </div>
        </div>
		<?php

		return ob_get_clean();
	}
}
