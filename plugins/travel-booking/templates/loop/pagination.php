<?php
/**
 * Pagination
 *
 * @author : Physcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;
?>

<?php
if ( $wp_query->max_num_pages > 1 ) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'total'              => $wp_query->max_num_pages,
		'current'            => $paged,
		'show_all'           => true,
		'end_size'           => 1,
		'mid_size'           => 2,
		'prev_next'          => true,
		'prev_text'          => '<i class="fa fa-long-arrow-left"></i>',
		'next_text'          => '<i class="fa fa-long-arrow-right"></i>',
		'type'               => 'list',
		'add_args'           => false,
		'add_fragment'       => '',
		'before_page_number' => '',
		'after_page_number'  => '',
	);
	if ( $args ) :
		?>
		<div class="woocommerce-pagination paging-navigation" role="navigation">
			<?php echo paginate_links( $args ); ?>
		</div>
		<?php
	endif;
}
?>
