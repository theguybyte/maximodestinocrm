<?php
/**
 * Personal Information
 *
 * @author  : Physcode
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$travel_personal_information_option = get_option( Tour_Settings_Tab_Phys::$_personal_information );

if ( $travel_personal_information_option !== '' ) {
	$travel_personal_information_option_obj = json_decode( $travel_personal_information_option );

	if ( ! is_null( $travel_personal_information_option_obj ) && count( (array) $travel_personal_information_option_obj ) > 0 ) {

		$cart = WC()->cart->get_cart();
		?>

		<div class="wrapper-personal-information">
			<h3><?php _e( 'Personal Information', 'travel-booking' ); ?></h3>

			<?php
			foreach ( $cart as $cart_item ) {
				if ( array_key_exists( 'is_tour', $cart_item ) ) {
					$tour      = $cart_item['data'];
					$qty_adult = (int) $cart_item['quantity'];
					$key       = $tour->get_id() . '_';

					echo '<h5>' . $tour->get_name() . '</h5>';

					for ( $i = 1; $i <= $qty_adult; $i ++ ) {
						echo '<div class="personal-infomation-item">
					<p><span><strong>' . __( 'Adult', 'travel-booking' ) . ' ' . $i . '</strong></span>:</p>';
						foreach ( $travel_personal_information_option_obj as $k => $info ) {
							$key_field = $key . $k . '_adult_' . $i;
							if ( $info->enable ) {
								echo '<p>';
								echo '<span class="label col-sm-3">' . __( $info->label, 'travel-booking' ) . '</span>';
								if ( $info->type == 'text' ) {
									echo '<input class="col-sm-9" type="text" name="' . $key_field . '" >';
								} elseif ( $info->type == 'select' ) {
									$attributes = explode( '|', $info->attribute );

									echo '<select name="' . $key_field . '">';
									foreach ( $attributes as $attribute ) {
										echo '<option value="' . $attribute . '">' . $attribute . '</option>';
									}
									echo '</select>';
								} elseif ( $info->type == 'radio' ) {
									$attributes = explode( '|', $info->attribute );

									foreach ( $attributes as $attribute ) {
										echo '<input type="radio" name="' . $key_field . '" value="' . $attribute . '" /><label>' . $attribute . '</label>&nbsp;&nbsp;';
									}
								} elseif ( $info->type == 'checkbox' ) {
									$attributes = explode( '|', $info->attribute );

									foreach ( $attributes as $attribute ) {
										echo '<input type="checkbox" name="' . $key_field . '[]' . '" value="' . $attribute . '" /><label>' . $attribute . '</label>&nbsp;&nbsp;';
									}
								}
								echo '</p>';
							}
						}
						echo '</div > ';
					}

					if ( array_key_exists( 'number_children', $cart_item ) ) {
						$qty_children = (int) $cart_item['number_children'];

						for ( $i = 1; $i <= $qty_children; $i ++ ) {
							echo '<div class="personal-infomation-item">
					<p><span><strong>' . __( 'Children', 'travel-booking' ) . ' ' . $i . '</strong></span>:</p>';
							foreach ( $travel_personal_information_option_obj as $k => $info ) {
								$key_field = $key . $k . '_child_' . $i;
								if ( $info->enable ) {
									echo '<p>';
									echo '<span class="label col-sm-3">' . __( $info->label, 'travel-booking' ) . '</span>';
									if ( $info->type == 'text' ) {
										echo '<input class="col-sm-9" type="text" name="' . $key_field . '" >';
									} elseif ( $info->type == 'select' ) {
										$attributes = explode( '|', $info->attribute );

										echo '<select name="' . $key_field . '">';
										foreach ( $attributes as $attribute ) {
											echo '<option value="' . $attribute . '">' . $attribute . '</option>';
										}
										echo '</select>';
									} elseif ( $info->type == 'radio' ) {
										$attributes = explode( '|', $info->attribute );

										foreach ( $attributes as $attribute ) {
											echo '<input type="radio" name="' . $key_field . '" value="' . $attribute . '" /><label>' . $attribute . '</label>&nbsp;&nbsp;';
										}
									} elseif ( $info->type == 'checkbox' ) {
										$attributes = explode( '|', $info->attribute );

										foreach ( $attributes as $attribute ) {
											echo '<input type="checkbox" name="' . $key_field . '[]' . '" value="' . $attribute . '" /><label>' . $attribute . '</label>&nbsp;&nbsp;';
										}
									}
									echo '</p>';
								}
							}
							echo '</div > ';
						}
					}
				}
			}
			?>

			<input type="hidden" name="travel_personal_information_option" value="<?php echo htmlentities( $travel_personal_information_option ); ?>">
		</div>
		<?php

	}
}
