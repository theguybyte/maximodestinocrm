<?php

/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       4.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce;
$rating_html = wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
?>

<?php if ($rating_html) : ?>
	<div class="item_rating"><?php echo $rating_html; ?></div>
<?php else : ?>
	<div class="item_rating">
		<div class="star-rating" title="">
			<span style="width:0"></span>
		</div>
	</div>
<?php endif; ?>