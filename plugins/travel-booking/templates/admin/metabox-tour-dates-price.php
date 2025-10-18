<?php
/**
 * Tab Set Dates and prices
 *
 * @author  physcode
 * @version 1.0.0
 */

global $post;
?>

<div id="phys_tour_dates_price" class="panel woocommerce_options_panel">
	<?php
	foreach ( TravelPhysTab::$_fields_tab_tour_dates_price as $name_field => $field ) {
		echo TravelPhysUtility::get_type_field( $post->ID, $name_field, $field );
	}
	?>
</div>
