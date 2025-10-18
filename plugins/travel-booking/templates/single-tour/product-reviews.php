<?php

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

$product = wc_get_product( false );

if ( ! comments_open() || ! $product ) {
	return;
}

$average_rating = $product->get_average_rating();
$count          = $product->get_review_count();
$review_tour    = TravelPhysReviewTour::instance();
$product_id     = $product->get_id();
?>
<div id="reviews" class="woocommerce-Reviews">
	<div class="review-top-section">
		<div class="header">
			<h2 class="title"><?php esc_html_e( 'Review', 'travel-booking' ); ?></h2>
			<?php
			if ( is_user_logged_in() || get_option( 'require_login_to_submit_review' ) !== 'yes' ) {
				?>
				<button type="button" id="tour-add-new-review">
					<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12.3751 21.1746H4.47194C4.37679 21.1758 4.28236 21.158 4.1942 21.1222C4.10605 21.0863 4.02597 21.0332 3.95869 20.9659C3.8914 20.8987 3.83828 20.8186 3.80244 20.7304C3.76661 20.6423 3.7488 20.5478 3.75006 20.4527V12.5496C3.75006 10.2621 4.65877 8.06827 6.27627 6.45076C7.89377 4.83326 10.0876 3.92456 12.3751 3.92456V3.92456C13.5077 3.92456 14.6293 4.14765 15.6757 4.5811C16.7221 5.01455 17.673 5.64986 18.4739 6.45076C19.2748 7.25167 19.9101 8.20248 20.3435 9.24892C20.777 10.2953 21.0001 11.4169 21.0001 12.5496V12.5496C21.0001 13.6822 20.777 14.8038 20.3435 15.8502C19.9101 16.8966 19.2748 17.8475 18.4739 18.6484C17.673 19.4493 16.7221 20.0846 15.6757 20.518C14.6293 20.9515 13.5077 21.1746 12.3751 21.1746V21.1746Z" stroke="#01AA90" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						<path d="M12.75 12.9246C12.75 13.1317 12.5821 13.2996 12.375 13.2996C12.1679 13.2996 12 13.1317 12 12.9246C12 12.7175 12.1679 12.5496 12.375 12.5496C12.5821 12.5496 12.75 12.7175 12.75 12.9246Z" fill="#01AA90" stroke="#01AA90" stroke-width="1.5" />
						<path d="M7.875 14.0496C8.49632 14.0496 9 13.5459 9 12.9246C9 12.3032 8.49632 11.7996 7.875 11.7996C7.25368 11.7996 6.75 12.3032 6.75 12.9246C6.75 13.5459 7.25368 14.0496 7.875 14.0496Z" fill="#01AA90" />
						<path d="M16.875 14.0496C17.4963 14.0496 18 13.5459 18 12.9246C18 12.3032 17.4963 11.7996 16.875 11.7996C16.2537 11.7996 15.75 12.3032 15.75 12.9246C15.75 13.5459 16.2537 14.0496 16.875 14.0496Z" fill="#01AA90" />
					</svg>
					<?php esc_html_e( 'Write a Review', 'travel-booking' ); ?>
				</button>
				<?php
			}
			?>
		</div>
		<div class="statistic">
			<div class="statistic-general">
				<div class="review-average-rating">
					<div class="average-rating">
						<?php
						printf( '%s/5', $average_rating );
						?>
					</div>
					<div class="rating">
						<?php
						echo wc_get_rating_html( $average_rating );
						?>
					</div>
				</div>
				<div class="review-count">
					<?php
					printf( esc_html( _n( '%s Review', '%s Reviews', $count, 'travel-booking' ) ), $count );
					?>
				</div>
			</div>
			<div class="statistic-detail">
				<?php
				for ( $i = 5; $i > 0; $i-- ) {
					$review_count = $review_tour->get_review_count_by_rating( $i, $product_id );
					if ( $count === 0 ) {
						$percent = 0;
					} else {
						$percent = ( $review_count / $count ) * 100;
					}
					?>
					<div class="statistic-detail-item" data-rating="<?php echo esc_attr( $i ); ?>">
						<div class="rating-label">
							<?php
							printf( esc_html( _n( '%s star', '%s stars', $i, 'travel-booking' ) ), $i );
							?>
						</div>
						<div class="full-width">
							<div class="progress">
								<div class="progress-bar" role="progressbar" style="width:<?php echo esc_attr( $percent ); ?>%"></div>
							</div>
						</div>

						<div class="rating-number">
							<?php echo esc_html( $review_count ); ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<div id="comments">
		<div class="tour-commentlist-sort-filter">
			<?php
			global $wp_rewrite;
			$origin_link = get_the_permalink() . '/' . $wp_rewrite->comments_pagination_base . '-1';

			?>
			<div class="gallery-filter">
				<?php
				$link = $origin_link;
				if ( isset( $_GET['review_sort_by'] ) ) {
					$review_sort_by = $_GET['review_sort_by'];
					$link           = add_query_arg( 'review_sort_by', $review_sort_by, $origin_link );
				}
				$photos_only = $_GET['photos_only'] ?? '';
				?>
				<a class="<?php echo empty( $photos_only ) || $photos_only === 'no' ? 'active' : ''; ?>" href="<?php echo add_query_arg( 'photos_only', 'no', $link ) . '#tab-reviews'; ?>"><?php esc_html_e( 'All', 'travel-booking' ); ?></a>
				<a class="<?php echo $photos_only === 'yes' ? 'active' : ''; ?>" href="<?php echo add_query_arg( 'photos_only', 'yes', $link ) . '#tab-reviews'; ?>"><?php esc_html_e( 'With Photos Only', 'travel-booking' ); ?></a>
			</div>
			<div class="sort-by">
				<div class="label"><?php esc_html_e( 'Sort by', 'travel-booking' ); ?></div>
				<div class="option">
					<?php
					$review_sort_by = $_GET['review_sort_by'] ?? '';
					$toggle         = __( 'Oldest', 'travel-booking' );
					$icon_dropdown  = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
					<path d="M13 6.92456L8 11.9246L3 6.92456" stroke="#121212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				  </svg>';
					if ( $review_sort_by === 'newest' ) {
						$toggle = __( 'Newest', 'travel-booking' );
					} elseif ( $review_sort_by === 'top-review' ) {
						$toggle = __( 'Top Review', 'travel-booking' );
					}
					?>
					<div class="toggle" data-value="top-oldest">
					<?php
					echo esc_html( $toggle );
																echo $icon_dropdown;
					?>
																</div>
					<ul id="tour-sort-by">
						<?php
						$link = $origin_link;

						if ( isset( $_GET['photos_only'] ) ) {
							$photos_only = $_GET['photos_only'];

							$link = add_query_arg( 'photos_only', $photos_only, $origin_link );
						}
						?>
						<li class="<?php echo $review_sort_by === 'oldest' ? 'active' : ''; ?>">
							<a class="tour-sort-by-option" href="<?php echo add_query_arg( 'review_sort_by', 'oldest', $link ) . '#tab-reviews'; ?>">
								<?php esc_html_e( 'Oldest', 'travel-booking' ); ?></a>
						</li>
						<li class="<?php echo $review_sort_by === 'newest' ? 'active' : ''; ?>">
							<a class="tour-sort-by-option" href="<?php echo add_query_arg( 'review_sort_by', 'newest', $link ) . '#tab-reviews'; ?>">
								<?php esc_html_e( 'Newest', 'travel-booking' ); ?></a>
						</li>
						<li class="<?php echo $review_sort_by === 'top-review' ? 'active' : ''; ?>">
							<a class="tour-sort-by-option" href="<?php echo add_query_arg( 'review_sort_by', 'top-review', $link ) . '#tab-reviews'; ?>">
								<?php esc_html_e( 'Top Review', 'travel-booking' ); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php
		$review_args = array(
			'post_id' => get_the_id(),
			'status'  => 'approve',
			'type'    => 'review',
		);

		$comments = get_comments( $review_args );
		if ( ! empty( $comments ) ) :
			?>
			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ), $comments ); ?>
			</ol>

			<?php
			if ( count( $comments ) > 1 && get_option( 'page_comments' ) ) :
				$per_page = get_option( 'comments_per_page' ) ?? 10;
				$max_page = ceil( count( $comments ) / $per_page );
				$page     = get_query_var( 'cpage' );
				if ( ! $page ) {
					$page = 1;
				}
				$args = array(
					'base'         => add_query_arg( 'cpage', '%#%' ),
					'total'        => $max_page,
					'current'      => $page,
					'echo'         => true,
					'add_fragment' => '#comments',
					'prev_text'    => is_rtl() ? '&rarr;' : '&larr;',
					'next_text'    => is_rtl() ? '&larr;' : '&rarr;',
					'type'         => 'list',
				);
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( $args );
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>
