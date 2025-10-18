<?php
/**
 * Hotel breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/hotel-booking/global/breadcrumb.php.
 *
 * @Version       1.0.0
 * @Author        physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;
?>

<nav class="tours-breadcrumb">
	<?php
	$title_tours = get_the_title( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) ? get_the_title( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) : apply_filters( 'title_tours_page_default', 'tours' );
	$title       = $title_tours . ' - ' . get_bloginfo( 'name' );
	echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="home">' . esc_html__( 'Home', 'travel-booking' ) . '</a> &nbsp;/&nbsp; ';
	if ( $wp_query->get( 'is_tour' ) ) {
		echo $title_tours;
	} elseif ( is_single() ) {
		echo '<a href="' . get_the_permalink( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) . '"> ' . $title_tours . '</a> &nbsp;/&nbsp; ';
		echo get_the_title();
	}
	?>
</nav>
