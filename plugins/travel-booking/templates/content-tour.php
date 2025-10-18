<?php
/**
 * Content tour
 * @author : Physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php post_class(); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );

	//Tour Type
	$terms = get_the_terms( $product->get_id(), 'tour_phys' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		?>
        <ul class="tour-type-list">
			<?php
			foreach ( $terms as $tour_type ) {
				$term_id          = $tour_type->term_id;
				$meta_data        = get_term_meta( $term_id, 'tour_phys_meta_key', true );
				$color            = $meta_data['tour_phys_text_color'] ?? '';
				$background_color = $meta_data['tour_phys_background_color'] ?? '';
				?>
                <li class="tour-type-list-item">
                    <a style="color:<?php echo esc_attr( $color ); ?>;
                            background-color: <?php echo esc_attr( $background_color ); ?>"
                       href="<?php echo get_term_link( $term_id ); ?>"><?php echo esc_html( $tour_type->name ); ?></a>
                </li>
				<?php
			}
			?>
        </ul>
		<?php
	}
	?>
</li>
