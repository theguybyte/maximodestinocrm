<?php

/**
 * Class TravelPhys_Polylang
 *
 * Handle Polylang integration
 *
 * @version 1.0.0
 * @since 2.1.1
 */
class TravelPhys_Polylang {
	protected static $instance;

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
	}

	protected function __construct() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'polylang/polylang.php' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'set_tour_options_with_multi_lang' ], 999 );
		add_filter( 'travel-booking-phys/rewrite-rules', [ $this, 'rewrite_rules' ], 10, 2 );
		add_filter( 'pll_the_language_link', [ $this, 'switch_lang' ], 10, 3 );
	}

	/**
	 * Set option with multiple language
	 *
	 * @return void
	 */
	public function set_tour_options_with_multi_lang() {
		try {
			$default_lang = pll_default_language();
			$current_lang = pll_current_language();

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
	 * Tour rules with polylang
	 *
	 * @param $wp_rules
	 * @param $tour_base_url
	 *
	 * @return string[]
	 */
	public function rewrite_rules( $wp_rules, $tour_base_url ) {
		$tour_rules = [];

		$default_lang = pll_default_language();
		$current_lang = pll_current_language();
		if ( $current_lang !== $default_lang ) {
			$tour_rules[] = [ "^($current_lang)/{$tour_base_url}(?!page)([^/]+)/?$" => 'index.php?lang=$matches[1]&product=$matches[2]' ];
			$tour_rules[] = [ "^($current_lang)/{$tour_base_url}page/([0-9]{1,})/?" => 'index.php?lang=$matches[1]&is_tour=1&paged=$matches[2]' ];
			$tour_rules[] = [ "^($current_lang)/{$tour_base_url}?$" => 'index.php?lang=$matches[1]&is_tour=1' ];
		}

		foreach ( $tour_rules as $rule ) {
			$wp_rules = array_merge( $rule, $wp_rules );
		}

		return $wp_rules;
	}

	public function switch_lang( $url, $slug, $locale ) {
		global $wp_query;
		if ( ! $wp_query->get( 'is_tour' ) ) {
			return $url;
		}

		$default_lang = pll_default_language();
		$current_lang = pll_current_language();

		$key_option = Tour_Settings_Tab_Phys::$_tours_show_page_id;

		if ( $default_lang === $current_lang ) {
			if ( $slug !== $default_lang ) {
				$key_option .= '_' . $slug;
				$url         = get_permalink( get_option( $key_option ) );
			} else {
				$url = add_query_arg( 'lang', $default_lang, $url );
			}
		} else {
			if ( $slug !== $current_lang ) {
				$key_option = str_replace( '_' . $current_lang, '', $key_option );
				$url        = add_query_arg( 'lang', $default_lang, get_permalink( get_option( $key_option ) ) );
			}
		}

		return $url;
	}
}

TravelPhys_Polylang::instance();
