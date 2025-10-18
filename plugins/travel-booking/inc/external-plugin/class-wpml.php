<?php

/**
 * Class TravelPhys_WPML
 *
 * Handle WPML integration
 *
 * @version 1.0.0
 * @since 2.1.1
 */
class TravelPhys_WPML {
	protected static $instance;

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
	}

	protected function __construct() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		if ( ! is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'set_travel_options_with_multi_lang' ], 999 );
		add_filter( 'travel-booking-phys/link/single-tour', [ $this, 'link_single_tour' ], 10, 3 );
		add_filter( 'post_type_archive_link', array( $this, 'change_post_type_product_when_is_tour' ), 999, 2 );
	}

	/**
	 * Set option with multiple language
	 *
	 * @return void
	 */
	public function set_travel_options_with_multi_lang() {
		try {
			$current_lang = apply_filters( 'wpml_current_language', null );
			$default_lang = apply_filters( 'wpml_default_language', null );

			if ( $current_lang !== $default_lang ) {
				Tour_Settings_Tab_Phys::$_tours_show_page_id               .= '_' . $current_lang;
				Tour_Settings_Tab_Phys::$_page_redirect_after_tour_booking .= '_' . $current_lang;
				Tour_Settings_Tab_Phys::$_permalink_tour_category_base     .= '_' . $current_lang;
			}
		} catch ( Throwable $e ) {
			error_log( $e->getMessage() );
		}
	}

	/**
	 * Set link single tour with lang.
	 *
	 * @param $post_link
	 * @param $post
	 * @param $tour_base_slug
	 *
	 * @return mixed|string|null
	 */
	public function link_single_tour( $post_link, $post, $tour_base_slug ) {
		try {
			/**
			 * @var SitePress $sitepress
			 */
			global $sitepress;
			$current_lang = apply_filters( 'wpml_current_language', null );
			$default_lang = apply_filters( 'wpml_default_language', null );
			$lang_format  = $sitepress->get_setting( 'language_negotiation_type' );

			if ( $current_lang !== $default_lang ) {
				if ( $lang_format == 1 ) {
					$post_link = site_url( $current_lang . $tour_base_slug . $post->post_name );
				} elseif ( $lang_format == 3 ) {
					$post_link = add_query_arg( 'lang', $current_lang, $post_link );
				}
			}
		} catch ( Throwable $e ) {
			error_log( $e->getMessage() );
		}

		return $post_link;
	}

	/**
	 * Link tours on switch language menu.
	 *
	 * @param $link
	 * @param $post_type
	 *
	 * @return mixed|string
	 */
	public function change_post_type_product_when_is_tour( $link, $post_type ) {
		if ( get_query_var( 'is_tour' ) ) {
			$_page_id_show_tours = (int) get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id );

			$link = get_page_link( $_page_id_show_tours );
		}

		return $link;
	}
}

TravelPhys_WPML::instance();
