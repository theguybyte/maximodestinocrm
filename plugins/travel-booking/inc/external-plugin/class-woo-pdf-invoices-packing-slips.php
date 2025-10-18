<?php
/**
 * Class TravelPhysWooPdfInvoicesPackingSlips
 * @version 1.0.1
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysWooPdfInvoicesPackingSlips {

	public static function init() {
		if ( is_plugin_active( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) ) {
			add_action( 'wpo_wcpdf_after_item_meta', array( __CLASS__, 'show_info_tour_booking' ), 11, 3 );
		}
	}

	/**
	 * @param               $type
	 * @param array         $item
	 * @param WC_Order      $order
	 *
	 * @throws Exception
	 */
	public static function show_info_tour_booking( $type, $item, $order ) {

		//echo '<pre>'.print_r($item, true).'</pre>';

		TravelPhysOrder::html_order_tour_item_info( $item['item']->get_id(), $item['item'] );
	}
}

TravelPhysWooPdfInvoicesPackingSlips::init();
