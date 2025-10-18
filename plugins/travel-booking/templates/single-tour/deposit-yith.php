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

if ( is_plugin_active( 'yith-woocommerce-deposits-and-down-payments-premium/init.php' ) ) {
	global $product;
	$tour_id = get_the_ID();

	// initialize variable
	$yith_deposit_enable      = $yith_deposit_enable_all_tour = $yith_deposit_tour_enable = '';
	$yith_deposit_tour_force  = $yith_deposit_tour_checkbox_default_is_deposit = $yith_deposit_tour_type = '';
	$yith_deposit_tour_amount = $yith_deposit_tour_rate =  0;

	$optionYithDeposit     = TravelPhysYithDeposit::getOptionYithDeposit();
	$optionTourYithDeposit = TravelPhysYithDeposit::getOptionTourYithDeposit( $tour_id );
	extract( $optionYithDeposit );
	extract( $optionTourYithDeposit );

	$deposit_value = 0;
	$deposit_text  = wc_price( $yith_deposit_tour_amount );

	if ( $yith_deposit_enable == 'no' ) {
		return;
	}

	if ( $yith_deposit_tour_type == 'rate' ) {
		$deposit_text = $yith_deposit_tour_rate . '%';
	}

	if ( $yith_deposit_enable_all_tour == 'yes' || $yith_deposit_tour_enable == 'yes' ) {
		if ( $yith_deposit_tour_force == 'no' ) {
			$checkedDepositFull = '';
			$checkedDeposit     = '';

			if ( $yith_deposit_tour_checkbox_default_is_deposit == 'no' ) {
				$checkedDepositFull = 'checked="checked"';
			} else {
				$checkedDeposit = 'checked="checked"';
			}
			?>
			<div id="yith-wcdp-add-deposit-to-cart" class="yith-wcdp">
				<div class="yith-wcdp-single-add-to-cart-fields" data-deposit-type="amount" data-deposit-amount="50"
					 data-deposit-rate="10">
					<label>
						<input type="radio" name="payment_type"
							   value="full" <?php echo $checkedDepositFull ?>> <?php echo apply_filters( 'yith_wcdp_pay_full_amount_label', __( 'Pay full amount', 'yith-woocommerce-deposits-and-down-payments' ) ) ?>
					</label><br>
					<label>
						<input type="radio" name="payment_type"
							   value="deposit" <?php echo $checkedDeposit ?>> <?php echo apply_filters( 'yith_wcdp_pay_deposit_label', __( 'Pay deposit', 'yith-woocommerce-deposits-and-down-payments' ) ) ?>
						<span class="deposit-price"><?php echo $deposit_text ?></span>
					</label><br>

				</div>
			</div>
			<?php

		} else {
			echo '<i>' . wp_kses_post( apply_filters( 'yith_wcdp_deposit_only_message', sprintf( __( 'This action will let you pay a deposit of <span class="deposit-price">%s</span> for this product', 'yith-woocommerce-deposits-and-down-payments' ), $deposit_text ), $deposit_value ) ) . '</i>';
		}

		//		if ( apply_filters( 'yith_wcdp_virtual_on_deposit', true, null ) && $needs_shipping && $show_shipping_form ): ?>
		<!--			<div class="yith-wcdp-deposit-shipping --><?php //echo ( $deposit_forced ) ? 'forced' : '' ?><!--">-->
		<!--				<small>--><?php //echo apply_filters( 'yith_wcdp_deposit_needs_shipping_text', __( 'Please, select a shipping method for delivery of your product when balance is paid', 'yith-woocommerce-deposits-and-down-payments' ) ) ?><!--</small>-->
		<!--				<div class="yith-wcdp-shipping-form">-->
		<!--					<table>--><?php //wc_cart_totals_shipping_html(); ?><!--</table>-->
		<!--					--><?php //yith_wcdp_get_template( 'shipping-calculator.php' ) ?>
		<!--				</div>-->
		<!--				<div class="clear"></div>-->
		<!--			</div>-->
		<!--			?>-->
		<!--		--><?php //endif; ?><!----><?php
		//		do_action( 'yith_wcdp_after_add_deposit_to_cart', $product, isset( $variation ) ? $variation : false );
	}
}

