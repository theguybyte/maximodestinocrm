<?php
global $post;
?>
<div id="phys_tour_faq" class="panel woocommerce_options_panel">
	<?php
	$phys_tour_faq_options = null;

	foreach ( TravelPhysFaq::$_fields_faq as $key => $value ) {
		${$key} = get_post_meta( $post->ID, $key, true ) ? get_post_meta( $post->ID, $key, true ) : $value['default'];
		if ( $value['type'] == 'json' ) {
			${$key} = json_decode( ${$key} );
			echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars( json_encode( ${$key} ) ) . '">';
		} else {
			echo TravelPhysUtility::show_html_field_admin( $post->ID, $key, $value );
		}
	}

	$tour_faq_item_structure_fields = TravelPhysFaq::$tour_faq_item_structure_fields;

	if ( json_last_error() != JSON_ERROR_NONE ) {
		return;
	}
	?>
    <hr>
    <div class="form-field">
        <div class="button-action">
            <button type="button" id="new-tour-faq" class="btn">
				<?php esc_html_e( 'Add new faq', 'travel-booking' ); ?></button>
        </div>

        <div class="wrapper-tour-faqs">
			<?php
			if ( ! is_null( $phys_tour_faq_options ) && count( array( $phys_tour_faq_options ) ) > 0 ) {
				foreach ( $phys_tour_faq_options as $k => $tour_faq_item ) {
					$toggle = 'toggle-up';
					if ( ! isset( $tour_faq_item->toggle ) ) {
						$toggle = 'toggle-down';
					} else {
						$toggle = $tour_faq_item->toggle;
					}
					?>
                    <div class="wrapper-tour-faq-item <?php echo $toggle === 'toggle-down' ? '' : 'open'; ?>">
                        <div class="header-tour-faq-item">
                            <h3><?php echo $tour_faq_item->label_faq !== '' ? $tour_faq_item->label_faq : 'Item'; ?></h3>
                            <span class="toggle-faq-item <?php echo $toggle; ?>" aria-hidden="true"></span>
                        </div>
                        <div class="tour-faq-item" data-faq-id="<?php echo $k; ?>"
                             style="<?php echo $toggle == 'toggle-down' ? 'display: none' : ''; ?>">
							<?php
							foreach ( $tour_faq_item_structure_fields as $k_field => $v_field ) {
								?>
                                <p class="faq-field"><span><?php echo esc_html( $v_field['label'] ); ?></span>
									<?php
									if ( $v_field['types'] === 'input_text' ) {
										?>
                                        <input type="text" name="<?php echo esc_attr( $k_field ); ?>"
                                               value="<?php echo esc_attr( $tour_faq_item->{$k_field} ); ?>"
                                               class="field">
										<?php
									} elseif ( $v_field['types'] === 'textarea' ) {
										?>
                                        <textarea rows="3" name="<?php echo esc_attr($k_field);?>" class="field" ><?php echo $tour_faq_item->{$k_field};?></textarea>
                                        <?php
									}

									?>
                                </p>
								<?php
							}
							?>
                            <span class="remove-faq dashicons dashicons-no"
                                  title="
	                        <?php echo __( 'Remove Faq', 'travel-booking' ); ?>"></span>
                        </div>
                    </div>
					<?php
				}
			}
			?>
        </div>

        <p class="errors"></p>

        <input type="hidden" name="tour_faq_item_structure_fields"
               value="
	<?php echo htmlspecialchars( json_encode( $tour_faq_item_structure_fields ) ); ?>">
    </div>
</div>
