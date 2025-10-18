<?php
if (!isset($data)) {
    return;
}
global $wpdb;

$tax_query  = array();
$meta_query = array();

$tax_query[] = array(
    'taxonomy' => 'product_type',
    'terms'    => array('tour_phys'),
    'field'    => 'slug',
    'operator' => 'IN',
);

$meta_query = new WP_Meta_Query($meta_query);
$tax_query  = new WP_Tax_Query($tax_query);

$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID'); 
$tax_query_sql  = $tax_query->get_sql($wpdb->posts, 'ID');

$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_post_type', array('product')))) . "')
							AND {$wpdb->posts}.post_status = 'publish'
							AND price_meta.meta_key IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_meta_keys', array('_price')))) . "')
							AND price_meta.meta_value > 0 ";
$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

$price_range = $wpdb->get_row($sql);
$item_label = !empty($data['label']) ? $data['label'] : 'Price';
?>
<div class="tour-search-field price" data-min="<?php echo esc_attr($price_range->min_price); ?>" data-max="<?php echo esc_attr($price_range->max_price); ?>">
    <div class="item-filter-heading"><?php esc_html_e($item_label, 'travel-booking'); ?> <a href="#" class="reset"><?php esc_html_e('Reset', 'travel-booking'); ?></a></div>
    <?php
    $start_price = $price_range->min_price;
    $end_price   = $price_range->max_price;

    if (!empty($data['tour_min_price']) && !empty($data['tour_max_price'])) {
        $start_price = $data['tour_min_price'];
        $end_price   = $data['tour_max_price'];
    }
    echo '<div class= "list-ranger-price">';
    echo sprintf('%s  %s', TravelPhysUtility::tour_format_price($start_price, 'tour-min-price'), TravelPhysUtility::tour_format_price($end_price, 'tour-max-price'));
    echo '</div>';
    ?>
    <div id="tour-price-range"></div>
    <input type="hidden" id="min-price" name="tour_min_price" value="<?php echo $start_price; ?>">
    <input type="hidden" id="max-price" name="tour_max_price" value="<?php echo $end_price; ?>">
</div>
<input type="hidden" name="tour_start_price_fitler" value="<?php echo $price_range->min_price; ?>">
<input type="hidden" name="tour_end_price_filter" value="<?php echo $price_range->max_price; ?>">