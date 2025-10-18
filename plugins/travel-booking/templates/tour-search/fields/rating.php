<?php
if ( ! isset( $data ) ) {
	return;
}
$rating_value = $data['tour_rating'] ?? '';
$item_label   = ! empty( $data['label'] ) ? $data['label'] : 'Rating';
?>
<div class="tour-search-field rating <?php if ( ! empty( $data['extra_class'] ) ) {
	echo $data['extra_class'];
}; ?>">
    <div class="item-filter-heading"><?php esc_html_e( $item_label, 'travel-booking' ); ?></div>
    <div class="wrapper-content">
        <ul>
            <li>
                <input type="radio" name="tour_rating" value="all" <?php checked( 'all', $rating_value, true ); ?>>
                <div class="star">
					<?php esc_html_e( 'All Rating', 'travel-booking' ); ?>
                </div>
            </li>
			<?php
			for ( $j = 4; $j > 0; $j -- ) { ?>
                <li>
                    <input type="radio" name="tour_rating"
                           value="<?php echo $j; ?>" <?php checked( $j, $rating_value, true ); ?>>
                    <div class="star">
						<?php
						for ( $i = 0; $i < 4; $i ++ ) {
							if ( $i < $j ) {
								echo '<i class="fa fa-star"></i>';
							} else {
								echo '<i class="fas fa-star"></i>';
							}
						}
						?>
                    </div>
                    <span>&amp; up</span>
                </li>
				<?php
			}
			?>
        </ul>
    </div>
</div>
