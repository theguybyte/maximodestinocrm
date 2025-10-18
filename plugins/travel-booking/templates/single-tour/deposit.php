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

	$deposit_disable          = get_option( 'wc_deposits_site_wide_disable', 'no' );
	$deposit_checkout_disable = get_option( 'wc_deposits_checkout_mode_enabled', 'no' );
	$deposit_text             = get_option( 'wc_deposits_button_deposit' );
	$full_text                = get_option( 'wc_deposits_button_full_amount' );
	$deposit_option_text      = get_option( 'wc_deposits_deposit_option_text' );

	$tour_deposit_enable = get_post_meta( get_the_ID(), '_wc_deposits_enable_deposit', true );
	$tour_deposit_type   = get_post_meta( get_the_ID(), '_wc_deposits_amount_type', true );
	$tour_deposit_amount = get_post_meta( get_the_ID(), '_wc_deposits_deposit_amount', true );
	$tour_deposit_force  = get_post_meta( get_the_ID(), '_wc_deposits_force_deposit', true );
	$disable_full_amount = '';

	if ( $tour_deposit_force == 'yes' ) {
		$disable_full_amount = 'disabled';
	}

	if ( $deposit_disable != '' && $deposit_disable == 'no' && $deposit_checkout_disable == 'no' ) {
		if ( $tour_deposit_enable == 'yes' ) {
			$deposit_amount = 0;
			if ( $tour_deposit_type == 'fixed' ) {
				$text_deposit_amount = wc_price( $tour_deposit_amount, 'deposit_amount' );
			} elseif ( $tour_deposit_type == 'percent' ) {
				$text_deposit_amount = $tour_deposit_amount . '%';
			}

			//echo '<input type="hidden" name="deposit_type" value="' . $tour_deposit_type . '">';
			//echo '<input type="hidden" name="deposit_amount" value="' . $tour_deposit_amount . '">';
			//echo '<input type="hidden" name="deposit_force" value="' . $tour_deposit_force . '">';
			echo '<p>' . __( $deposit_option_text, 'woocommerce-deposits' ) . '<span class="deposit_amount_per"> ' . $text_deposit_amount . '&nbsp;' . __( 'on tour', 'woocommerce-deposits' ) . '</span>' . '</p>';
			echo '<div class="basic-switch-woocommerce-deposits tour-variations">';
			echo '<p>';
			echo '<input class="input-radio field_variation" type="radio" name="tour_deposit" value="deposit" checked>';
			echo '&nbsp;<label>' . __( $deposit_text, 'woocommerce-deposits' ) . '</label>';
			echo '</p>';
			echo '<p>';
			echo '<input class="input-radio field_variation" type="radio" name="tour_deposit" value="full" ' . $disable_full_amount . '>';
			echo '&nbsp;<label>' . __( $full_text, 'woocommerce-deposits' ) . '</label>';
			echo '</p>';
			echo '</div>';
		}
	}
}
