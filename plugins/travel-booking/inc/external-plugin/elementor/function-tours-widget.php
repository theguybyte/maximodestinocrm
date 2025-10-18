<?php
add_action( 'wp_ajax_search_arttibutes_item', 'search_arttibutes_item_callback' );
add_action( 'wp_ajax_nopriv_search_arttibutes_item', 'search_arttibutes_item_callback' );
function search_arttibutes_item_callback() {
	$json = array(
		// 'data' => $_POST,
		'success' => false,
	);
	ob_start();
	if ( ! empty( $_POST['attributes'] ) ) {
		$attr_arr  = $_POST['attributes'];
		$term_args = array(
			'taxonomy'   => $attr_arr,
			'hide_empty' => false,
		);
		if ( ! empty( $_POST['sortby'] ) ) {
			if ( $_POST['sortby'] == 'ASC' || $_POST['sortby'] == 'DESC' ) {
				$term_args['order'] = $_POST['sortby'];
			} else {
				$term_args['order'] = 'ASC';
			}
			if ( $_POST['sortby'] == 'popularity' ) {
				$term_args['orderby']    = 'count';
				$term_args['hide_empty'] = 0;
			}
		}
		if ( ! empty( $_POST['value_search'] ) ) {
			$term_args['name__like'] = $_POST['value_search'];
		}
		$json['data']   = $term_args;
		$terms_off_attr = get_terms( $term_args );
		if ( ! empty( $terms_off_attr[0] ) ) {
			foreach ( $terms_off_attr as $term ) {
				if ( $term ) {
					$thumnail_html = $thumnail_html_hover = $content_html = '';
					foreach ( $_POST['layout'] as $item ) {
						$attributes_html = ( isset( $item['open_new_tab'] ) && $item['open_new_tab'] == 'yes' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
						$link_image      = get_tax_meta( $term->term_id, 'phys_tour_type_thumb', true ) ? get_tax_meta( $term->term_id, 'phys_tour_type_thumb', true ) : '';
						$text_color      = get_tax_meta( $term->term_id, 'phys_text_color', true ) ? get_tax_meta( $term->term_id, 'phys_text_color', true ) : '';
						$custom_link     = get_tax_meta( $term->term_id, 'phys_custom_link', true ) ? get_tax_meta( $term->term_id, 'phys_custom_link', true ) : get_term_link( $term->term_id );

						if ( isset( $item['thumbnail_size_size'] ) && $item['thumbnail_size_size'] == 'custom' ) {
							$size_iamge = array( $item['thumbnail_size_custom_dimension']['width'], $item['thumbnail_size_custom_dimension']['height'] );
						} else {
							$size_iamge = $item['thumbnail_size_size'] ?? 'full';
						}

						switch ( $item['meta_data_img'] ) {
							case 'title':
								if ( $item['list_thumbnail_position'] == 'absolute' ) {
									if ( isset( $item['always_show'] ) && $item['always_show'] == 'yes' ) {
										$thumnail_html_hover .= archive_render_atr_title( $item, $term, $custom_link );
									} else {
										$thumnail_html .= archive_render_atr_title( $item, $term, $custom_link );
									}
								} else {
									$content_html .= archive_render_atr_title( $item, $term, $custom_link );
								}
								break;
							case 'content':
								if ( $item['list_thumbnail_position'] == 'absolute' ) {
									if ( isset( $item['always_show'] ) && $item['always_show'] == 'yes' ) {
										$thumnail_html_hover .= archive_render_atr_content( $item, $term );
									} else {
										$thumnail_html .= archive_render_atr_content( $item, $term );
									}
								} else {
									$content_html .= archive_render_atr_content( $item, $term );
								}
								break;
							case 'count':
								if ( $item['list_thumbnail_position'] == 'absolute' ) {
									if ( isset( $item['always_show'] ) && $item['always_show'] == 'yes' ) {
										$thumnail_html_hover .= archive_render_atr_count( $item, $term );
									} else {
										$thumnail_html .= archive_render_atr_count( $item, $term );
									}
								} else {
									$content_html .= archive_render_atr_count( $item, $term );
								}
								break;
							case 'button':
								if ( $item['list_thumbnail_position'] == 'absolute' ) {
									if ( isset( $item['always_show'] ) && $item['always_show'] == 'yes' ) {
										$thumnail_html_hover .= archive_render_atr_button( $item, $custom_link );
									} else {
										$thumnail_html .= archive_render_atr_button( $item, $custom_link );
									}
								} else {
									$content_html .= archive_render_atr_button( $item, $custom_link );
								}
								break;
						}
					}
					echo '<div class="tours_type_item">';
					?>
					<div class="list-attri-thumbnail">
						<?php if ( ! empty( $link_image ) ) : ?>
							<a href="<?php echo esc_url( $custom_link ); ?>" title="<?php echo $term->name; ?>"
								class="tours-type__item__image" <?php echo $attributes_html; ?>>
								<?php
								echo wp_get_attachment_image(
									$link_image['id'],
									$size_iamge,
									false,
									array(
										'alt' => $term->name,
									)
								);
								?>
							</a>
							<?php
						endif;
						if ( $thumnail_html != '' || $thumnail_html_hover != '' ) :
							?>
							<div class="content-item">
								<?php echo $thumnail_html; ?>
								<?php if ( $thumnail_html_hover != '' ) : ?>
									<div class="content-item-hover">
										<?php echo $thumnail_html_hover; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( $content_html != '' ) : ?>
						<div class="attr-content-item">
							<?php echo $content_html; ?>
						</div>
					<?php endif; ?>
					<?php
					echo '</div> ';
				}
			}
		} else {
			esc_html_e( 'Nothing Found', 'travel-booking' );
		}
		$json['success'] = true;
	}
	$json['content'] = ob_get_clean();
	wp_send_json( $json );
}

function archive_render_atr_title( $item, $term, $custom_link ) {
	$title_tag = $item['title_tag'];

	return '<' . esc_html( $title_tag ) . ' class="item__title"><a href="' . $custom_link . '">' . $term->name . '</a></' . esc_html( $title_tag ) . '>';
}

function archive_render_atr_count( $item, $term ) {
	return '<div class="count-attr">' . $term->count . ' ' . esc_html__( $item['text_count'], 'travel-booking' ) . '</div>';
}

function archive_render_atr_content( $item, $term ) {
	return '<div class="item-attr-des">' . wpautop( $term->description ) . '</div>';
}

function archive_render_atr_button( $item, $custom_link ) {
	$term_name = ( isset( $item['bt_name_content'] ) && ! empty( $item['bt_name_content'] ) ) ? $item['bt_name_content'] : esc_html__( 'See all tours', 'travel-booking' );

	return '<a href="' . $custom_link . '" class="btn">' . $term_name . '</a>';
}

function file_get_contents_stream( $fn, $content_type = '' ) {
	$opts = array(
		'http' => array(
			'method' => 'GET',
			'header' => 'Content-Type: text/html;',
		),
	);
	if ( $content_type != '' ) {
		$opts['http']['header'] = "Content-Type: {$content_type};";
	}

	$context = stream_context_create( $opts );
	$result  = @file_get_contents( $fn, false, $context );
	return $result;
}
add_action( 'wp_ajax_nopriv_fetch_weather', 'travel_booking_fetch_weather_callback' );
add_action( 'wp_ajax_fetch_weather', 'travel_booking_fetch_weather_callback' );
function travel_booking_fetch_weather_callback() {

	$json = array(
		'success' => false,
		'data'    => array(
			'POST' => $_POST,
		),
	);

	$locale = get_locale();
	if ( $locale == '' ) {
		$locale = 'en_US';
	}
	$locale = strtolower( $locale );
	if ( $locale != 'zh_cn' || $locale != 'zh_tw' ) {
		$locale = preg_replace( '/_.+$/m', '', trim( $locale ) );
	}

	$params = array(
		'appid' => get_option( 'tour_weather_api' ),
		'units' => 'metric',
		'lang'  => $locale,
	);

	if ( isset( $_POST['lat'] ) && isset( $_POST['lon'] ) ) {
		$params['lat'] = $_POST['lat'];
		$params['lon'] = $_POST['lon'];
	} else {
		$params['q'] = isset( $_POST['location'] ) ? trim( $_POST['location'], ' ,' ) : '';
	}
	$params = http_build_query( $params, '', '&', PHP_QUERY_RFC3986 );

	$api_url = "https://api.openweathermap.org/data/2.5/forecast?{$params}";
	if ( isset( $_POST['view'] ) && $_POST['view'] == 'simple' ) {
		$api_url = "https://api.openweathermap.org/data/2.5/weather?{$params}";
	}
	$result = file_get_contents_stream( $api_url, 'application/json' );
	if ( $result === false ) {
		$json['error'] = __( 'Weather request error. Please make sure that your api is entered.', 'travel-booking' );
	} else {
		$json['success'] = true;
		$json['result']  = json_decode( $result );
	}
	wp_send_json( $json );
}
