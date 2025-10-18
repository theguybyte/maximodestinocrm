<?php

/**
 * Content Tab "Infomations"
 *
 * @author  physcode
 * @version 1.0.0
 */
global $post;
?>
<div id="phys_tour_infomations" class="panel woocommerce_options_panel">
	<?php
	foreach (TravelPhysTab::$_fields_tab_tour_infomations as $name_field => $field) {
		echo TravelPhysUtility::get_type_field($post->ID, $name_field, $field);
	}
	?>
</div>