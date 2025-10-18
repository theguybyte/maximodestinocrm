<?php
/**
 * Content Tab "Tour Variable"
 */

global $post;
?>

<div id="tour_variation_phys" class="panel woocommerce_options_panel">
	<?php
	$_tour_variations_options = null;
	foreach ( TravelPhysVariation::$_fields_variation as $key => $value ) {
		${$key} = get_post_meta( $post->ID, $key, true ) ? get_post_meta( $post->ID, $key, true ) : $value['default'];

		if ( $value['type'] == 'json' ) {
			${$key} = json_decode( ${$key} );
			echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars( json_encode( ${$key} ) ) . '">';
		} else {
			echo TravelPhysUtility::show_html_field_admin( $post->ID, $key, $value );
		}
	}

	$tour_variation_item_structure_fields = TravelPhysVariation::$tour_variation_item_structure_fields;

	if ( json_last_error() != JSON_ERROR_NONE ) {
		return;
	}
	?>
	<hr>
	<div class="form-field">
		<div class="button-action">
			<button id="new-tour-variations" class="btn"><?php _e( 'Add new variant', 'travel-booking' ); ?></button>
			<button id="save-tour-variations" class="btn"><?php _e( 'Save', 'travel-booking' ); ?></button>
		</div>

		<div class="wrapper-tour-variants">
			<?php
			if ( ! is_null( $_tour_variations_options ) && count( array( $_tour_variations_options ) ) > 0 ) {
				foreach ( $_tour_variations_options as $k => $tour_variant_item ) {
					$toggle = 'toggle-up';
					if ( ! isset( $tour_variant_item->toggle ) ) {
						$toggle = 'toggle-down';
					} else {
						$toggle = $tour_variant_item->toggle;
					}
					?>
					<div class="wrapper-tour-variant-item <?php echo $toggle == 'toggle-down' ? '' : 'open'; ?>">
						<div class="header-tour-variation-item">
							<h3><?php echo $tour_variant_item->label_variation !== '' ? $tour_variant_item->label_variation : 'Item'; ?></h3>
							<span class="toggle-variation-item <?php echo $toggle; ?>" aria-hidden="true"></span>
						</div>
						<div class="tour-variant-item" data-variation-id="<?php echo $k; ?>"
							 style="<?php echo $toggle == 'toggle-down' ? 'display: none' : ''; ?>">
							<?php
							foreach ( $tour_variation_item_structure_fields as $k_field => $v_field ) {

								if ( $k_field !== 'variation_attr' ) {
									echo '<p class="variation-field"><span>' . $v_field['label'] . '</span>';

									if ( is_array( $v_field['types'] ) ) {
										echo '<select class="field"  name="' . $k_field . '">';
										foreach ( $v_field['types'] as $k_type => $v_type ) {
											$selected = '';
											if ( $tour_variant_item->{$k_field} == $k_type ) {
												$selected = 'selected';
											}
											echo '<option value="' . $k_type . '" ' . $selected . '>' . $v_type['label'] . '</option>';
										}
										echo '</select>';
									} elseif ( $v_field['types'] === 'input_text' ) {
										echo '<input type="text" name="' . $k_field . '" value="' . ( $tour_variant_item->{$k_field} ?? '' ) . '" class="field" >';
									}

									echo '</p>';
								} else {
									echo '<div class="variation-field-attrs" name="' . $k_field . '">';
									echo '<button class="new-variation-attr btn">Add new attribute</button>';
									echo '<div class="wrapper-tour-variation-attribute">';
									foreach ( $tour_variant_item->{$k_field} as $k_variation_attr => $v_variation_attr ) {
										echo '<ul class="tour-variation-attribute" data-attr-id="' . $k_variation_attr . '">';
										echo '<li><h4><strong>Attribute:</strong></h4></li>';
										foreach ( $v_field as $k_attr => $v_attr ) {
											echo '<li>';
											echo '<span>' . $v_attr['label'] . '</span>';
											echo '<input class="field" type="text" name="' . $k_attr . '" value="' . $v_variation_attr->{$k_attr} . '" >';
											echo '</li>';
										}
										echo '<span class="remove-variant-attr dashicons dashicons-no-alt" title="Remove Attribute"></span>';
										echo '</ul>';
									}
									echo '</div>';
									echo '</div>';
								}
							}
							?>
							<span class="remove-variant dashicons dashicons-no"
								  title="<?php echo __( 'Remove Variation', 'travel-booking' ); ?>"></span>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>

		<p class="errors"></p>

		<input type="hidden" name="tour_variation_item_structure_fields"
			   value="<?php echo htmlspecialchars( json_encode( $tour_variation_item_structure_fields ) ); ?>">
	</div>
</div>
