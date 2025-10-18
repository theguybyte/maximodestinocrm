<?php
if ( ! isset( $data ) ) {
	return;
}

$taxonomy   = 'tour_phys';
$tour_terms = get_terms( $taxonomy );

if ( empty( $tour_terms ) || is_wp_error( $tour_terms ) ) {
	return;
}
?>

<div class="tour-search-field type">
</div>

