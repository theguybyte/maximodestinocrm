<?php
/**
 * Metabox for Tour Booking
 *
 * @author  physcode
 * @version 1.0.0
 */
global $post;


?>
<div id="tour_booking_phys" class="panel woocommerce_options_panel">
	<?php
	foreach ( TravelPhysTab::$_fields_tab_tour_booking as $name_field => $field ) {
		echo TravelPhysUtility::get_type_field( $post->ID, $name_field, $field );
	}
	?>
</div>
