<?php
/**
 * Related Tours
 * @author: Physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}
$posts_per_page = 3;
$terms          = get_the_terms( $product->get_id(), 'tour_phys' );

if ( $terms ) {
	$term_ids       = wp_list_pluck( $terms, 'term_id' );
	$option_related = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
		'posts_per_page'      => $posts_per_page,
		'orderby'             => 'rand',
		'post__not_in'        => array( $product->get_id() ),
		'tax_query'           => array(
			array(
				'taxonomy' => 'tour_phys',
				'field'    => 'slug',
				'terms'    => $term_ids,
				'operator' => 'IN',
			),
		),
	);

	$args = apply_filters( 'tb_related_tour_args', $option_related );

	$products = new WP_Query( $args );

	if ( $products->have_posts() ) : ?>

		<div class="related tours-default">

			<h2><?php _e( 'Related Tours', 'travel-booking' ); ?></h2>
			<ul class="tours-default">
				<?php
				while ( $products->have_posts() ) :
					$products->the_post();
					?>

					<?php tb_get_file_template( 'content-tour.php', false ); ?>

				<?php endwhile; // end of the loop. ?>
			</ul>
		</div>

		<?php
	endif;

	wp_reset_postdata();
}
