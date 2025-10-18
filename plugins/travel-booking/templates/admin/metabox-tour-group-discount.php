<?php
/**
 * Content Tab "Group Discount"
 *
 * @author  physcode
 * @version 1.0.0
 */

global $post;
$tour_group_discount_enable   = false;
$tour_group_text_form_booking = '';
$tour_group_discount_data     = '';
$tour_group_discount_data_obj = null;

foreach ( TravelPhysTab::$_fields_tab_tour_group_discount as $key => $val_default ) {
	${$key} = get_post_meta( $post->ID, '_' . $key, true ) ? get_post_meta( $post->ID, '_' . $key, true ) : $val_default['default'];
}

$tour_group_discount_meta_field = apply_filters(
	'tour_group_discount_meta_field',
	array(
		'number_customer' => array(
			'label' => __( 'Customer number', 'travel-booking' ),
			'type'  => 'number',
			'value' => 1,
		),
		'discount'        => array(
			'label' => __( 'Discount', 'travel-booking' ),
			'type'  => 'text',
			'value' => 0,
		),
		'description'     => array(
			'label' => __( 'Description', 'travel-booking' ),
			'type'  => 'text',
			'value' => __( "* Fill only number for fixed amount, ex. '10' for $10. Fill % at the end if using as percentage, ex. '10%'", 'travel-booking' ),
		),
	)
);

if ( $tour_group_discount_enable == '' ) {
	$tour_group_discount_enable = 0;
}

if ( is_string( $tour_group_discount_data ) && $tour_group_discount_data != '' ) {
	$tour_group_discount_data_obj = json_decode( $tour_group_discount_data );
} else {
	$tour_group_discount_data = '';
}
?>

<div id="phys_tour_group_discount" class="panel woocommerce_options_panel">
	<div class="form-field">
		<p class="tour-group-discount-enable">
			<select name="tour_group_discount_enable">
				<?php
				for ( $i = 0; $i < 2; $i ++ ) {
					$selected = '';
					if ( $i == $tour_group_discount_enable ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}

					$s = $i == 0 ? 'No' : 'Yes';

					echo '<option value="' . $i . '" ' . $selected . '>' . $s . '</option>';
				}
				?>
			</select>
			<strong style="margin-left:10px"><?php _e( 'Enable group discount', 'travel-booking' ); ?></strong>
		</p>
		<p class="right">
			<?php _e( 'Ex: if you create 2 discount boxes, and for the first box, you set up 5 customer with 10% discount and for another box, 10 customer with 20% discount. When customers book smaller than 10 travellers, they will get 10% off. However, if they book for 10, 11, 12 ( and so on ) customer, they will get 20% off.', 'travel' ); ?>
		</p>
	</div>
	<p class="form-field">
		<label><strong><?php _e( 'Text Discount Form Booking', 'travel-booking' ); ?></strong></label>
		<input name="tour_group_text_form_booking" id="tour_group_text_form_booking" value="<?php echo $tour_group_text_form_booking; ?>" style="width: 70%">
	</p>
	<hr>
	<div class="form-field">
		<button id="new-tour-group-discount" class="btn"><?php echo __( 'Add group discount', 'travel-booking' ); ?></button>
		<button id="save-tour-group-discount" class="btn"><?php echo __( 'Save', 'travel-booking' ); ?></button>
		<p class="errors"></p>
		<input type="hidden" name="error-customer-number-exists" value="<?php _e( 'Errors, Customer number is Exists. Please set value another' ); ?>">

		<ul id="list-group-discount">
			<?php
			if ( ! is_null( $tour_group_discount_data_obj ) ) {
				foreach ( $tour_group_discount_data_obj as $item ) {
					echo '<li class="item-group-discount">';
					foreach ( $item as $k => $val ) {
						if ( $k !== 'description' ) {
							echo '<div class="label-name" data-key-field="' . $k . '">' . $tour_group_discount_meta_field[ $k ]['label'] . '</div>';
							switch ( $tour_group_discount_meta_field[ $k ]['type'] ) {
								case 'number':
									echo '<div class="value"><input class="form-control" type="number" name="' . $k . '" value="' . $val . '" /></div>';
									break;
								case 'text':
									echo '<div class="value"><input class="form-control" type="text" name="' . $k . '" value="' . $val . '" /></div>';
									break;
								default:
									break;
							}
						} else {
							echo '<div class="des"><div class="tip" data-key-field="' . $k . '">' . $val . '</i></div></div>';
						}
					}
					echo '<div class="remove-item"><span class="dashicons dashicons-no-alt"></span></div>';
					echo '</li>';
				}
			}
			?>
		</ul>

		<input type="hidden" name="tour_group_discount_data" value="<?php echo $tour_group_discount_data != '' ? htmlspecialchars( $tour_group_discount_data ) : ''; ?>">
		<input type="hidden" name="tour_group_discount_meta_field" value="<?php echo htmlspecialchars( json_encode( $tour_group_discount_meta_field ) ); ?>">
	</div>
</div>
