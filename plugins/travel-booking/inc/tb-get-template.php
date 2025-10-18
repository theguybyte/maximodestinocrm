<?php

/***
 * Get Template Travel
 *
 * @author  physcode
 * @version 2.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

function tb_get_template_admin($file_name) {
	$path_file = TB_PHYS_TEMPLATE_PATH_ADMIN . $file_name;
	if (file_exists($path_file)) {
		include($path_file);
	}
}

function tb_template_path() {
	$tb_template_path = get_stylesheet_directory() . DIRECTORY_SEPARATOR . TravelBookingPhyscode::$_folder_plugin_name . DIRECTORY_SEPARATOR;

	return apply_filters('tb_template_path', $tb_template_path);
}

function tb_get_file_template($file_name, $include_one = true, $not_include = false) {
	$tb_template_parent_path = get_template_directory() . DIRECTORY_SEPARATOR . TravelBookingPhyscode::$_folder_plugin_name . DIRECTORY_SEPARATOR;
	$check_file              = tb_template_path() . $file_name;
	$check_file_of_parent    = $tb_template_parent_path . $file_name;
	if ($not_include) {
		if (file_exists($check_file)) {
			return $check_file;
		} elseif (file_exists($check_file_of_parent)) {
			return $check_file_of_parent;
		} else {
			return TB_PHYS_TEMPLATE_PATH_DEFAULT . $file_name;
		}
	} elseif (file_exists($check_file)) {
		if ($include_one) {
			include_once($check_file);
		} else {
			include($check_file);
		}
	} elseif (file_exists($check_file_of_parent)) {
		if ($include_one) {
			include_once($check_file_of_parent);
		} else {
			include($check_file_of_parent);
		}
	} else {
		if ($include_one) {
			include_once(TB_PHYS_TEMPLATE_PATH_DEFAULT . $file_name);
		} else {
			include(TB_PHYS_TEMPLATE_PATH_DEFAULT . $file_name);
		}
	}
}
function travel_get_template($template_name, $args = array()) {

	if (is_array($args) && isset($args)) {
		extract($args);
	}

	$template_file = locate_template(array(
		'templates/' . $template_name . '.php'
	));
	if (!$template_file) {
		$default_template_path = TB_PHYS_TEMPLATE_PATH_DEFAULT . $template_name . '.php';
		if (file_exists($default_template_path)) {
			$template_file = $default_template_path;
		} else {
			_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_name), '1.0.0');
			return;
		}
	}

	include $template_file;
}


if (!function_exists('tour_booking_single_title')) {
	function tour_booking_single_title() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'title.php');
	}
}

if (!function_exists('tour_booking_breadcrumb')) {
	function tour_booking_breadcrumb() {
		tb_get_file_template('global' . DIRECTORY_SEPARATOR . 'breadcrumb.php');
	}
}

if (!function_exists('travelbooking_output_content_wrapper')) {
	function travelbooking_output_content_wrapper() {
		tb_get_file_template('global' . DIRECTORY_SEPARATOR . 'wrapper-start.php');
	}
}

if (!function_exists('travelbooking_output_content_wrapper_end')) {
	function travelbooking_output_content_wrapper_end() {
		tb_get_file_template('global' . DIRECTORY_SEPARATOR . 'wrapper-end.php');
	}
}
if (!function_exists('travelbooking_wrapper_before_loop_start')) {
	function travelbooking_wrapper_before_loop_start() {
		echo '<div class="wrapper-tour-left">';
	}
}
if (!function_exists('travelbooking_wrapper_after_loop_end')) {
	function travelbooking_wrapper_after_loop_end() {
		echo '</div>';
	}
}

if (!function_exists('travelbooking_result_count')) {
	function travelbooking_result_count() {
		tb_get_file_template('loop' . DIRECTORY_SEPARATOR . 'result-count.php');
	}
}

if (!function_exists('travelbooking_catalog_ordering')) {
	function travelbooking_catalog_ordering() {
		tb_get_file_template('loop' . DIRECTORY_SEPARATOR . 'ordering.php');
	}
}

if (!function_exists('travelbooking_get_sidebar')) {
	function travelbooking_get_sidebar() {
		tb_get_file_template('global' . DIRECTORY_SEPARATOR . 'sidebar.php');
	}
}

if (!function_exists('tour_booking_single_ratting')) {
	function tour_booking_single_ratting() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'review-rating.php');
	}
}

if (!function_exists('tour_booking_single_code')) {
	function tour_booking_single_code() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'code.php');
	}
}

if (!function_exists('tour_booking_single_price')) {
	function tour_booking_single_price() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'price.php');
	}
}

if (!function_exists('tour_booking_single_booking')) {
	function tour_booking_single_booking() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'booking.php');
	}
}

if (!function_exists('tour_booking_single_gallery')) {
	function tour_booking_single_gallery() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'gallery.php');
	}
}

if (!function_exists('tour_booking_default_product_tabs')) {
	function tour_booking_default_product_tabs($tabs = array()) {
		global $product, $post;

		$tab_field_itinerary  = get_post_meta($post->ID, '_tour_content_itinerary', true);
		$tab_location_address = get_post_meta($post->ID, '_tour_location_address', true);
		$google_map_iframe    = get_post_meta($post->ID, '_google_map_iframe', true);
		$location_opt         = get_option('location_option');
		$tab_fields_hightlight  = get_post_meta($post->ID, 'phys_tour_hightlight_options', true);
		$tab_fields_hightlight  = json_decode($tab_fields_hightlight, true);
		$data_field_language  = get_post_meta($post->ID, 'language', true);
		$data_field_transport  = get_post_meta($post->ID, '_tour_transport', true);
		$data_field_accommodation  = get_post_meta($post->ID, '_tour_accommodation', true);
		$data_field_meals  = get_post_meta($post->ID, '_tour_meals', true);
		$data_field_group_size  = get_post_meta($post->ID, '_tour_group_size', true);

		if ($post->post_content) {
			$tabs['description'] = array(
				'title'    => __('Description', 'travel-booking'),
				'priority' => 10,
				'callback' => 'woocommerce_product_description_tab',
			);
		}
		if ($tab_field_itinerary) {
			$tabs['itinerary_tab'] = array(
				'title'    => __('Itinerary', 'travel-booking'),
				'priority' => 20,
				'callback' => 'tour_booking_itinerary_tab',
			);
		}

		if (($location_opt == 'google_api' && $tab_location_address != '') || ($location_opt == 'google_iframe' && $google_map_iframe != '')) {
			$tabs['location_tab'] = array(
				'title'    => __('Location', 'travel-booking'),
				'priority' => 21,
				'callback' => 'tour_booking_location_tab',
			);
		}
		if (!empty($tab_fields_hightlight) && is_array($tab_fields_hightlight)) {
			$tabs['hightlight_tab'] = array(
				'title'    => __('Hightlight', 'travel-booking'),
				'priority' => 20,
				'callback' => 'tour_booking_hightlight_tab',
			);
		}
		if ($data_field_language || $data_field_transport || $data_field_accommodation || $data_field_meals || $data_field_group_size) {
			$tabs['_tab_additional_information'] = array(
				'title'    => __('Additional information', 'travel-booking'),
				'priority' => 20,
				'callback' => 'tour_booking_additional_information_tab',
			);
		}
		// Reviews tab - shows comments
		if (comments_open()) {
			$callback = 'comments_template';



			$tabs['reviews'] = array(
				'title'    => sprintf(__('Reviews (%d)', 'travel-booking'), $product->get_review_count()),
				'priority' => 30,
				'callback' => $callback
			);
		}

		return $tabs;
	}
}

if (!function_exists('tour_booking_sort_product_tabs')) {

	/**
	 * Sort tabs by priority.
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	function tour_booking_sort_product_tabs($tabs = array()) {

		// Make sure the $tabs parameter is an array
		if (!is_array($tabs)) {
			trigger_error('Function woocommerce_sort_product_tabs() expects an array as the first parameter. Defaulting to empty array.');
			$tabs = array();
		}

		// Re-order tabs by priority
		if (!function_exists('_sort_priority_callback')) {
			function _sort_priority_callback($a, $b) {
				if ($a['priority'] === $b['priority']) {
					return 0;
				}

				return ($a['priority'] < $b['priority']) ? -1 : 1;
			}
		}

		uasort($tabs, '_sort_priority_callback');

		return $tabs;
	}
}

if (!function_exists('tour_booking_location_tab')) {
	function tour_booking_location_tab() {

		global $post;
		$tab_location_address = get_post_meta($post->ID, '_tour_location_address', true);
		$tab_location_lat     = get_post_meta($post->ID, '_tour_location_lat', true);
		$tab_location_long    = get_post_meta($post->ID, '_tour_location_long', true);
		$google_map_iframe    = get_post_meta($post->ID, '_google_map_iframe', true);
		$api_key              = get_option('google_api_key');
		$location_opt         = get_option('location_option');
		if ($location_opt == 'google_api') {
			$data = ' data-lat="' . esc_attr($tab_location_lat) . '"';
			$data .= ' data-long="' . esc_attr($tab_location_long) . '"';
			$data .= ' data-address="' . esc_attr($tab_location_address) . '"';
			echo '<div class="wrapper-gmap"><div id="googleMapCanvas" class="google-map"' . $data . '></div></div> ';
			wp_enqueue_script('google-map', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '', array('jquery'), false, true);
			wp_enqueue_script('map', TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/gmap.js', array('jquery'), false, true);
		} else {
			if ($google_map_iframe) {
				preg_match('/<iframe.*?src=["\'](.*?)["\'].*?>/', $google_map_iframe, $matches);
				if ($matches) {
					$iframe_src = $matches[1];
					preg_match('/!2z([^!]+)!/', $iframe_src, $address_matches);
					if ($address_matches) {
						$encoded_address = $address_matches[1];
						$map_address     = base64_decode(strtr($encoded_address, '-_', '+/'));
					}
				} else {
					$map_address = $google_map_iframe;
				}
				printf(
					'<div class="google-map-container"><iframe src="https://maps.google.com/maps?q=%1$s&amp;t=m&amp;z=%2$d&amp;output=embed&amp;iwloc=near" title="%3$s" aria-label="%3$s"></iframe></div>',
					rawurlencode($map_address),
					absint(14),
					esc_attr($map_address)
				);
			}
		}
	}
}

if (!function_exists('tour_booking_itinerary_tab')) {
	function tour_booking_itinerary_tab() {
		global $post;
		$tab_field_itinerary = get_post_meta($post->ID, '_tour_content_itinerary', true);
		echo apply_filters('the_content', wpautop($tab_field_itinerary));
	}
}

if (!function_exists('tour_booking_single_information')) {
	function tour_booking_single_information() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'infomation.php');
	}
}

if (!function_exists('tour_booking_single_related')) {
	function tour_booking_single_related() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'related.php', false);
	}
}
if (!function_exists('tour_booking_hightlight_tab')) {
	function tour_booking_hightlight_tab() {
		tb_get_file_template('single-tour' . DIRECTORY_SEPARATOR . 'hightlight.php'); 
	}
}
if (!function_exists('tour_booking_additional_information_tab')) {
	function tour_booking_additional_information_tab() {
		global $post;
		$data_field_language  = get_post_meta($post->ID, 'language', true);
		$data_field_transport  = get_post_meta($post->ID, '_tour_transport', true);
		$data_field_accommodation  = get_post_meta($post->ID, '_tour_accommodation', true);
		$data_field_meals  = get_post_meta($post->ID, '_tour_meals', true);
		$data_field_group_size  = get_post_meta($post->ID, '_tour_group_size', true);
		?>
		<table>
			<tbody>
				<?php if (!empty($data_field_language)) : ?>
					<tr>
						<th><?php esc_html_e('Language', 'travel-booking'); ?></th>
						<td><?php echo esc_html($data_field_language); ?></td>
					</tr>
				<?php endif; ?>

				<?php if (!empty($data_field_transport)) : ?>
					<tr>
						<th><?php esc_html_e('Transport', 'travel-booking'); ?></th>
						<td><?php echo esc_html($data_field_transport); ?></td>
					</tr>
				<?php endif; ?>

				<?php if (!empty($data_field_accommodation)) : ?>
					<tr>
						<th><?php esc_html_e('Accommodation', 'travel-booking'); ?></th>
						<td><?php echo esc_html($data_field_accommodation); ?></td>
					</tr>
				<?php endif; ?>

				<?php if (!empty($data_field_meals)) : ?>
					<tr>
						<th><?php esc_html_e('Meals', 'travel-booking'); ?></th>
						<td><?php echo esc_html($data_field_meals); ?></td>
					</tr>
				<?php endif; ?>

				<?php if (!empty($data_field_group_size)) : ?>
					<tr>
						<th><?php esc_html_e('Group Size', 'travel-booking'); ?></th>
						<td><?php echo esc_html($data_field_group_size); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
	}
}
