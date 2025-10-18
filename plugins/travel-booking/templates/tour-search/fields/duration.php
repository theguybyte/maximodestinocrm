<?php
if ( ! isset( $data ) ) {
	return;
}
$durations = array(
//	'less_than_1_hour'      => esc_html__( 'Up to 1 hour', 'travel-booking' ),
//	'from_1_to_4_hours'     => esc_html__( '1 to 4 hours', 'travel-booking' ),
//	'from_4_hours_to_1_day' => esc_html__( '4 hours to 1 day', 'travel-booking' ),
	'less_than_1_day'     => esc_html__( 'Less than 1 day', 'travel-booking' ),
	'from_1_to_3_days'    => esc_html__( '1 to 3 days', 'travel-booking' ),
	'greater_than_3_days' => esc_html__( '3+ days', 'travel-booking' ),
);

$duration_value = $data['duration'] ?? '';

if ( empty( $duration_value ) ) {
	$duration_value = [];
} else {
	if ( is_string( $duration_value ) ) {
		$duration_value = explode( ',', $duration_value );
	}
}
$item_label = !empty($data['label']) ? $data['label'] : 'Duration';
?>
<div class="tour-search-field duration <?php if ( ! empty( $data['extra_class'] ) ) {echo $data['extra_class'];}; ?>">
   	<div class="item-filter-heading"><?php esc_html_e($item_label, 'travel-booking' ); ?></div>
	<div class="wrapper-content">
		<ul>
			<?php
			foreach ( $durations as $key => $value ) {
				?>
				<li class="duration-item">
					<input id="<?php echo esc_attr( $key ); ?>" type="checkbox" name="duration[]"
						value="<?php echo esc_attr( $key ); ?>" <?php checked( true, in_array( $key, $duration_value ), true ); ?>>
					<label for="<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $value ); ?>
					</label>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
</div>
