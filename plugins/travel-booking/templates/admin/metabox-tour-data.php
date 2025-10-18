<?php

/**
 * Content tab "Tour Data"
 */

global $post;
foreach (TravelPhysTab::$field_arr as $key => $val_default) {
	${$key} = get_post_meta($post->ID, '_' . $key, true) ? get_post_meta($post->ID, '_' . $key, true) : $val_default;
}
$location_opt = htmlentities(get_option('location_option'));

?>

<div id="tour_data_phys" class="panel woocommerce_options_panel">
	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-itinerary"><?php echo esc_html__('Itinerary', 'travel-booking'); ?></li>
		<li class="tab-link" data-tab="tab-location"><?php echo esc_html__('Location', 'travel-booking'); ?></li>
		<li class="tab-link" data-tab="tab-hightlight"><?php echo esc_html__('Hightlight', 'travel-booking'); ?></li>
		<?php do_action('travel_tour_custom_data_title_tab'); ?>
	</ul>

	<div id="tab-itinerary" class="tab-content current">
		<?php
		wp_editor($tour_content_itinerary, 'tour_content_itinerary', array('editor_height' => '220px'));
		?>
	</div>
	<div id="tab-location" class="tab-content">
		<?php if ($location_opt == 'google_api') { ?>
			<p class="form-field">
				<label><?php echo esc_html__('Address', 'travel-booking'); ?></label>
				<input type="text" value="<?php echo $tour_location_address; ?>" name="tour_location_address">
			</p>
		<?php } else { ?>
			<p class="form-field">
				<label><?php echo esc_html__('Google Map (iframe) Or Address', 'travel-booking'); ?></label>
				<textarea name="google_map_iframe" rows="6" style="height: auto"><?php echo $google_map_iframe ? $google_map_iframe : ''; ?></textarea>
			</p>
		<?php } ?>
		<p class="form-field">
			<label><?php echo esc_html__('Latitude', 'travel-booking'); ?></label>
			<input type="text" value="<?php echo $tour_location_lat; ?>" name="tour_location_lat">
		</p>
		<p class="form-field">
			<label><?php echo esc_html__('Longitude', 'travel-booking'); ?></label>
			<input type="text" value="<?php echo $tour_location_long; ?>" name="tour_location_long">
		</p>
		<p class="form-field">
			<span><a href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank"><?php esc_html_e('Get Lat Long from Address', 'travel-booking'); ?></a></span>
		</p>
	</div>
	<div id="tab-hightlight" class="tab-content">
		<div id="phys_tour_hightlight">
			<?php
			$phys_tour_hightlight_options = null;
			foreach (TravelPhysHightlight::$_fields_hightlight as $key => $value) {
				${$key} = get_post_meta($post->ID, $key, true) ? get_post_meta($post->ID, $key, true) : $value['default'];
				if ($value['type'] == 'json') {
					${$key} = json_decode(${$key});
					echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(json_encode(${$key})) . '">';
				} else {
					echo TravelPhysUtility::show_html_field_admin($post->ID, $key, $value);
				}
			}

			$tour_hightlight_item_structure_fields = TravelPhysHightlight::$tour_hightlight_item_structure_fields;

			if (json_last_error() != JSON_ERROR_NONE) {
				return;
			}
			?>
			<hr>
			<div class=" form-field">
				<div class="button-action">
					<button type="button" id="new-tour-hightlight" class="btn">
						<?php esc_html_e('Add new hightlight', 'travel-booking'); ?></button>
				</div>

				<div class="wrapper-tour-hightlights">
					<?php
					if (!is_null($phys_tour_hightlight_options) && count(array($phys_tour_hightlight_options)) > 0) {
						foreach ($phys_tour_hightlight_options as $k => $tour_hightlight_item) {
							$toggle = 'toggle-up';
							if (!isset($tour_hightlight_item->toggle)) {
								$toggle = 'toggle-down';
							} else {
								$toggle = $tour_hightlight_item->toggle;
							}
					?>
							<div class="wrapper-tour-hightlight-item <?php echo $toggle === 'toggle-down' ? '' : 'open'; ?>">
								<div class="header-tour-hightlight-item">
									<h3><?php echo $tour_hightlight_item->label_hightlight !== '' ? $tour_hightlight_item->label_hightlight : 'Item'; ?></h3>
									<span class="toggle-hightlight-item <?php echo $toggle; ?>" aria-hidden="true"></span>
								</div>
								<div class="tour-hightlight-item" data-hightlight-id="<?php echo $k; ?>" style="<?php echo $toggle == 'toggle-down' ? 'display: none' : ''; ?>">
									<?php
									foreach ($tour_hightlight_item_structure_fields as $k_field => $v_field) {
									?>
										<div class="hightlight-field"><span><?php echo esc_html($v_field['label']); ?></span>
											<?php
											if ($v_field['types'] === 'input_text') {
											?>
												<input type="text" name="<?php echo esc_attr($k_field); ?>" value="<?php echo esc_attr($tour_hightlight_item->{$k_field}); ?>" class="field">
											<?php
											} elseif ($v_field['types'] === 'textarea') {
											?>
												<textarea rows="3" name="<?php echo esc_attr($k_field); ?>" class="field"><?php echo $tour_hightlight_item->{$k_field}; ?></textarea>
											<?php
											} elseif ($v_field['types'] === 'image') {
												$unique_id = uniqid($k_field . '_');
												$image_id = esc_attr($tour_hightlight_item->{$k_field});
												$image_src = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
											?>
												<div class="hightlight-field-image">
													<img id="<?php echo $unique_id; ?>_preview" src="<?php echo $image_src; ?>" alt="" style="max-width: 100px; height: auto; margin-top: 10px; <?php echo $image_src ? '' : 'display: none;'; ?>">
													<button type="button" class="button upload_image_button" data-target="#<?php echo $unique_id; ?>">Select Image</button>
													<input type="hidden" id="<?php echo $unique_id; ?>" name="<?php echo esc_attr($k_field); ?>" value="<?php echo $image_id; ?>" class="field">
												</div>
											<?php
											}
											?>

										</div>
									<?php
									}
									?>
									<span class="remove-hightlight dashicons dashicons-no" title="
	                        <?php echo __('Remove hightlight', 'travel-booking'); ?>"></span>
								</div>
							</div>
					<?php
						}
					}
					?>
				</div>

				<p class="errors"></p>
				<input type="hidden" name="tour_hightlight_item_structure_fields" value="
			<?php echo htmlspecialchars(json_encode($tour_hightlight_item_structure_fields)); ?>">
			</div>
		</div>
	</div>
	<?php do_action('travel_tour_custom_data_content_tab'); ?>
</div>