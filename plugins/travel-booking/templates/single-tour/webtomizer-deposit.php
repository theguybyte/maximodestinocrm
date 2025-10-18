<?php
/**
 * Single tour deposit
 *
 * @version 1.0.0
 * @author  Physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_plugin_active( 'woocommerce-deposits/woocommerce-deposits.php' ) ) {
	global $product;

	// initialize
	$webtomizer_deposit_disable     = $webtomizer_deposit_checkout_enable = $webtomizer_deposit_text = '';
	$webtomizer_full_text           = $webtomizer_deposit_option_text = '';
	$webtomizer_tour_deposit_enable = $webtomizer_tour_deposit_type = '';
	$webtomizer_tour_deposit_amount = $webtomizer_tour_deposit_force = '';

	extract( TravelPhysWebtomizerDeposit::getOptionDepoist() );
	extract( TravelPhysWebtomizerDeposit::getOptionTourDeposit( get_the_ID() ) );
	$disable_full_amount = '';

	if ( $webtomizer_tour_deposit_force == 'yes' ) {
		$disable_full_amount = 'disabled';
	}

	if ( $webtomizer_deposit_disable != '' && $webtomizer_deposit_disable == 'no' && $webtomizer_deposit_checkout_enable == 'no' ) {
		if ( $webtomizer_tour_deposit_enable == 'yes' ) {
			$deposit_amount = 0;
			if ( $webtomizer_tour_deposit_type == 'fixed' ) {
				$text_deposit_amount = wc_price( $webtomizer_tour_deposit_amount, 'deposit_amount' );
			} elseif ( $webtomizer_tour_deposit_type == 'percent' ) {
				$text_deposit_amount = $webtomizer_tour_deposit_amount . '%';
			}

			//echo '<input type="hidden" name="deposit_type" value="' . $tour_deposit_type . '">';
			//echo '<input type="hidden" name="deposit_amount" value="' . $tour_deposit_amount . '">';
			//echo '<input type="hidden" name="deposit_force" value="' . $tour_deposit_force . '">';
			echo '<p>' . __( $webtomizer_deposit_option_text, 'woocommerce-deposits' ) . '<span class="deposit_amount_per"> ' . $text_deposit_amount . '&nbsp;' . __( 'on tour', 'woocommerce-deposits' ) . '</span>' . '</p>';
			echo '<div class="basic-switch-woocommerce-deposits tour-variations">';
			echo '<p>';
			echo '<input class="input-radio field_variation" type="radio" name="webtomizer_tour_deposit" value="deposit" checked>';
			echo '&nbsp;<label>' . __( $webtomizer_deposit_text, 'woocommerce-deposits' ) . '</label>';
			echo '</p>';
			echo '<p>';
			echo '<input class="input-radio field_variation" type="radio" name="webtomizer_tour_deposit" value="full" ' . $disable_full_amount . '>';
			echo '&nbsp;<label>' . __( $webtomizer_full_text, 'woocommerce-deposits' ) . '</label>';
			echo '</p>';
			echo '</div>';
		}
	}
}
