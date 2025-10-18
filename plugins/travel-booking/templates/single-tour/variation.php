<?php
/**
 * Variation Travel
 * @version 1.0.0
 * @author  Physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$_tour_variation_enable   = 0;
$_tour_variations_options = '{}';
foreach ( TravelPhysVariation::$_fields_variation as $key => $val ) {
	${$key} = get_post_meta( get_the_ID(), $key, true ) ? get_post_meta( get_the_ID(), $key, true ) : $val['default'];
}

$tour_variations_options_obj = TravelPhysVariation::getInstance()->get_variation_options( get_the_ID() );

if ( json_last_error() != JSON_ERROR_NONE ) {
	return;
}

$tour_variation_item_structure_fields_obj = TravelPhysVariation::$tour_variation_item_structure_fields;
$class_field_calculate                    = 'field-travel-booking';
global $currency;

if ( $_tour_variation_enable === 'Yes' && ! is_null( $tour_variations_options_obj )
	&& count( (array) $tour_variations_options_obj ) > 0 ) {
	echo '<div class="tour-variations">';
	foreach ( $tour_variations_options_obj as $k => $tour_variation_item ) {
		if ( $tour_variation_item->enable ) {
			$tour_variation_item_option = '';

			foreach ( $tour_variation_item_structure_fields_obj as $k_structure_field => $v_structure_field ) {
				if ( $k_structure_field !== 'variation_attr' ) {
					$tour_variation_item_option .= ' data-' . $k_structure_field . '=' . ( $tour_variation_item->{$k_structure_field} ?? '' );
				}
			}
			echo '<div class="tour-variation-item" data-variation-id="' . $k . '" ' . $tour_variation_item_option . '>';
			echo '<p>' . $tour_variation_item->label_variation . '</p>';

			switch ( $tour_variation_item->type_variation ) {
				case 'quantity':
					if ( $tour_variation_item->set_price == 1 ) {
						foreach ( $tour_variation_item->variation_attr as $k_attr => $v_attr ) {
							echo '<p class="variation-attr-item"><input type="number" name="' . $k_attr . '" 
									value="0" min="0" 
									data-attr-price="' . $v_attr->price . '"
									class="' . $class_field_calculate . '">&nbsp;
									<span>' . $v_attr->label . '</span>&nbsp;
									&times;&nbsp;' . TravelPhysUtility::tour_format_price( $v_attr->price, 'price_' . $k_attr ) .
								 '</p>';
						}
					} else {

					}

					break;
				case 'select':
					echo '<select name="' . $k . '" class="' . $class_field_calculate . '">';
					echo '<option value="0">' . __( 'Choose', 'travel-booking' ) . '&nbsp;' . $tour_variation_item->label_variation . '</option>';
					foreach ( $tour_variation_item->variation_attr as $k_attr => $v_attr ) {
						$price_attr = $tour_variation_item->variation_attr->{$k_attr}->price;
						echo '<option value="' . $k_attr . '" data-attr-price="' . $v_attr->price . '">' . $v_attr->label . '</option>';
					}
					echo '</select>';
					break;
				case 'checkbox':
					if ( $tour_variation_item->set_price == 1 ) {
						foreach ( $tour_variation_item->variation_attr as $k_attr => $v_attr ) {
							echo '<p><input type="checkbox" name="' . $k_attr . '" value="' . $k_attr . '"
						data-attr-price="' . $v_attr->price . '" 
						class="' . $class_field_calculate . '"><span>' . $v_attr->label . ' (' . TravelPhysUtility::tour_format_price( $v_attr->price, 'price_' . $k_attr ) . ')' . '</span></p>';
						}
					} else {
						foreach ( $tour_variation_item->variation_attr as $k_attr => $v_attr ) {
							echo '<p><input type="checkbox" name="' . $k_attr . '" value="' . $k_attr . '" class="' . $class_field_calculate . '"><span>' . $v_attr->label . '</span></p>';
						}
					}
					break;
				case 'radio':
					if ( $tour_variation_item->set_price == 1 ) {
						foreach ( $tour_variation_item->variation_attr as $k_attr => $v_attr ) {
							echo '<p><input type="radio" name="' . $k . '" value="' . $k_attr . '" 
						data-attr-price="' . $v_attr->price . '" 
						class="' . $class_field_calculate . '">
						<span>' . $v_attr->label . ' (' . TravelPhysUtility::tour_format_price( $v_attr->price, 'price_' . $k_attr ) . ')' . '</span>
						</p>';
						}
					} else {
						foreach ( $tour_variation_item->variation_attr as $k_attr => $v_attr ) {
							echo '<p><input type="radio" name="' . $k . '" value="' . $k_attr . '" class="' . $class_field_calculate . '"><span>' . $v_attr->label . '</span></p>';
						}
					}

					break;
				default:
					break;
			}
			echo '</div>';
		}
	}
	echo '</div>';
	?>
	<input type="hidden" name="_tour_variations_options"
		   value=" <?php echo htmlentities( json_encode( $tour_variations_options_obj ) ); ?>">
	<?php
}
