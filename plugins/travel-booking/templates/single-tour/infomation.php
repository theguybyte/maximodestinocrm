<?php
/**
 * Information Tour
 *
 * @author physcode
 * @version 2.0.0
 */


$tabs = apply_filters( 'tour_booking_default_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">
		<ul class="tabs wc-tabs" role="tablist">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab" role="presentation">
					<a href="#tab-<?php echo esc_attr( $key ); ?>" role="tab" data-toggle="tab"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="tab-content">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div role="tabpanel" class="tab-pane woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
