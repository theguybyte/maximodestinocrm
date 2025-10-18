<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$query_string_url     = $_SERVER['QUERY_STRING'];
$query_string_url_arr = array();

if ( $query_string_url !== '' ) {
	$query_string_url_arr = explode( '&', $query_string_url );
}
$show_label_sort = apply_filters('travel_booking_show_label_sort',false);
?>
<form class="tour-ordering" method="get" action="">
	<?php if($show_label_sort){ ?>
		<h6><?php esc_html_e('Sort By','travel-booking');?></h6>
	<?php }?>
	
	<select name="orderbyt" class="orderby">
		<?php
		$orderby_selected        = isset( $_GET['orderbyt'] ) ? $_GET['orderbyt'] : '';
		$catalog_orderby_options = apply_filters(
			'tour_catalog_orderby',
			array(
				'menu_order' => __( 'Default sorting', 'travel-booking' ),
				'popularity' => __( 'Sort by popularity', 'travel-booking' ),
				'rating'     => __( 'Sort by average rating', 'travel-booking' ),
				'date'       => __( 'Sort by newness', 'travel-booking' ),
				'price'      => __( 'Sort by price: low to high', 'travel-booking' ),
				'price-desc' => __( 'Sort by price: high to low', 'travel-booking' ),
			)
		);
		foreach ( $catalog_orderby_options as $id => $name ) :
			if ( $orderby_selected == esc_attr( $id ) ) {
				echo '<option value="' . esc_attr( $id ) . '"
									selected="' . $orderby_selected . '">' . esc_html( $name ) . '</option>';
			} else {
				echo '<option value="' . esc_attr( $id ) . '">' . esc_html( $name ) . '</option>';
			}
		endforeach;
		?>
	</select>

	<?php
	if ( count( $query_string_url_arr ) > 0 ) {
		foreach ( $query_string_url_arr as $param_str ) {
			$param_arr = explode( '=', $param_str );
			if ( count( $param_arr ) > 1 ) {
				$k_param = urldecode( $param_arr[0] );
				$v_param = $param_arr[1];
				if ( $k_param != 'orderbyt' ) {
					echo '<input type="hidden" name="' . $k_param . '" value="' . $v_param . '">';
				}
			}
		}
	}
	?>
</form>
