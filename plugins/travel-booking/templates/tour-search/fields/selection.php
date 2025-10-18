<?php
if ( ! isset( $data ) ) { 
	return;
}
$item_label = !empty($data['label']) ? $data['label'] : 'Selected';
?> 
<div class="tour-search-field selection <?php if ( ! empty( $data['extra_class'] ) ) {echo $data['extra_class'];}; ?>">
    <div class="item-filter-heading"><?php esc_html_e($item_label, 'travel-booking' ); ?></div>
    <ul class="list">
    </ul>
    <button type="button" class="clear"><?php esc_html_e( 'Clear Filter', 'travel-booking' ); ?></button>
</div>
