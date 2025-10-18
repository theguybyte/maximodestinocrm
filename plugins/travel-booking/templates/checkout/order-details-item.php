<?php
/**
 * Order Item Details frontend
 *
 * @author  physcode
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}

?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
	<td class="product-name">
		<?php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
		$date_booking      = wc_get_order_item_meta( $item_id, '_date_booking', true );
		$price_adults            = wc_get_order_item_meta( $item_id, '_price_adults', true );
		$number_children         = wc_get_order_item_meta( $item_id, '_number_children', true );
		$price_children          = wc_get_order_item_meta( $item_id, '_price_children', true );
		$tour_variations         = wc_get_order_item_meta( $item_id, '_tour_variations', true );
		$tour_variations_options = wc_get_order_item_meta( $item_id, '_tour_variations_options', true );
		$is_tour                 = wc_get_order_item_meta( $item_id, '_is_tour', true );
		$date_check_in           = wc_get_order_item_meta( $item_id, '_date_check_in', true );
		$date_check_out          = wc_get_order_item_meta( $item_id, '_date_check_out', true );
		$tour_group_discount     = wc_get_order_item_meta( $item_id, '_tour_group_discount', true );
		$tour                    = $item->get_product();

		echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item['name'] ) : $item['name'], $item, $is_visible );

		if ( $is_tour ) {
			if ( $date_check_in !== '' && $date_check_out !== '' ) {
				echo '<div><strong>' . __( 'Date check in', 'travel-booking' ) . '</strong>:&nbsp;' . $date_check_in . '</div>';
				echo '<div><strong>' . __( 'Date check out', 'travel-booking' ) . '</strong>:&nbsp;' . $date_check_out . '</div>';
			}

			if ( isset( $number_children ) && $number_children != '' ) {
				echo '<div class="cart-item-number-adult"><strong>' . __( 'Adults', 'travel-booking' ) . '</strong>&nbsp;<span>(' . TravelPhysUtility::tour_format_price( $price_adults ) . ')</span> &times; <span>' . $item['qty'] . '</span></div>';
				echo '<div class="cart-item-number-child"><strong>' . __( 'Children', 'travel-booking' ) . '</strong>&nbsp;<span>(' . TravelPhysUtility::tour_format_price( $price_children ) . ')</span> &times; <span>' . $number_children . '</span></div>';
			} else {
				echo '<div class="cart-item-number-ticket"><strong>' . __( 'Tickets', 'travel-booking' ) . '</strong>&nbsp;<span>(' . TravelPhysUtility::tour_format_price( $price_adults ) . ')</span> &times; <span>' . $item['qty'] . '</span></div>';
			}

			/*** Variation ***/
			if ( isset( $tour_variations ) && is_object( $tour_variations ) && isset( $tour_variations_options ) && is_object( $tour_variations_options ) ) {
				echo TravelPhysVariation::view_variation_detail( $tour_variations, $tour_variations_options );
			}

			/*** Group discount ***/
			if ( isset( $tour_group_discount ) && $tour_group_discount !== '' ) {
				echo $tour_group_discount;
			}
		} else {
			echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );
		}

		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

		if ( TravelBookingPhyscode::$_version_woo < 3 ) {
			$order->display_item_meta( $item );
			$order->display_item_downloads( $item );
		} elseif ( TravelBookingPhyscode::$_version_woo == 3 ) {
			wc_display_item_meta( $item );
			wc_display_item_downloads( $item );
		} else {
			wc_display_item_meta( $item );
			wc_display_item_downloads( $item );
		}

		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
		?>
	</td>
	<td class="product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</td>
</tr>
<?php if ( $show_purchase_note && $purchase_note ) : ?>
	<tr class="product-purchase-note">
		<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
	</tr>
<?php endif; ?>
