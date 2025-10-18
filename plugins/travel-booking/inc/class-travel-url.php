<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Travel url
 *
 * @Version 1.0.2
 * @author physcode
 */
class TravelPhysUrl {
	public static $tour_destination_slug = 'tour-destination';
	public static $tour_month_slug       = 'tour-month';

	public static function init() {
		if ( wp_doing_ajax() ) {
			return;
		}

		self::$tour_destination_slug = apply_filters( 'travel-phys/tour-destination-slug', self::$tour_destination_slug );
		self::$tour_month_slug       = apply_filters( 'travel-phys/tour-month-slug', self::$tour_month_slug );

		if ( is_admin() ) {
			add_filter( 'post_row_actions', array( __CLASS__, 'change_link_tour_view_actions_phys' ), 11, 2 );
			add_filter( 'get_sample_permalink_html', array( __CLASS__, 'link_tour_backend' ), 11, 5 );
			add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menus' ), 50 );
			add_filter( 'term_link', array( __CLASS__, 'rewrite_link_attribute' ), 11, 3 );
		} else {
			add_filter( 'post_type_link', array( __CLASS__, 'link_tour' ), 11, 2 );
			//add_filter( 'rewrite_rules_array', array( __CLASS__, 'rewrite_rules' ), 11, 1 );
			add_filter( 'option_rewrite_rules', [ __CLASS__, 'update_option_rewrite_rules' ], 1 );
			add_action( 'init', array( __CLASS__, 'rewrite_slug_attribute_woo_by_phys' ), 99 );

			// Menu tour frontend
			add_filter( 'wp_nav_menu_objects', array( __CLASS__, 'filter_nav_menu_item_tour_phys' ), 11, 1 );
		}
	}

	/**
	 * Rewrite term link attribute of tour (Destination and Month).
	 *
	 * @param $termlink
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return array|mixed|string|string[]|null
	 */
	public static function rewrite_link_attribute( $termlink, $term, $taxonomy ) {
		try {
			if ( strpos( $taxonomy, 'pa_' ) === false ) {
				return $termlink;
			}

			$slug_attribute = str_replace( 'pa_', '', $taxonomy );
			switch ( $slug_attribute ) {
				case 'destination':
					$termlink = preg_replace( '/\/destination\//', '/' . self::$tour_destination_slug . '/', $termlink );
					break;
				case 'month':
					$termlink = preg_replace( '/\/month\//', '/' . self::$tour_month_slug . '/', $termlink );
			}
		} catch ( Throwable $e ) {
			error_log( $e->getMessage() );
		}

		return $termlink;
	}

	/**
	 * @deprecated 2.1.1
	 */
	/*public static function filter_post_type_link_tour_phys( $post_link, $post ) {
		$fixed_link = '';
		if ( 'product' == $post->post_type ) {
			$terms = get_the_terms( $post->ID, 'product_type' );

			if ( ! empty( $terms ) && $terms[0]->slug == 'tour_phys' ) {
				static $product_struct, $is_complex_struct, $cache = array();

				$cache_key = $post_link;
				if ( isset( $cache[ $cache_key ] ) ) {
					return $cache[ $cache_key ];
				}

				if ( null === $product_struct ) {
					if ( isset( $GLOBALS['wp_rewrite']->extra_permastructs['product'] ) ) {
						$product_struct_init = $GLOBALS['wp_rewrite']->extra_permastructs['product']['struct'];
						$mapper              = array(
							'`%product%`' => '{XPRODUCTX}',
							'`%\w+%`'     => '[^/]+',
						);
						$product_struct      = preg_replace( array_keys( $mapper ), $mapper, ( $product_struct_init && $product_struct_init[0] != '/' ? '/' : '' ) . $product_struct_init );
						$is_complex_struct   = strpos( $product_struct, '[^/]+' ) !== false;
					}
				}

				$what_replace   = str_replace( '{XPRODUCTX}', $post->post_name, $product_struct );
				$full_tour_slug = self::get_tour_base_slug( true ) . $post->post_name;
				if ( $is_complex_struct ) {
					$fixed_link = preg_replace( '`' . $what_replace . '`', $full_tour_slug, $post_link );
				} else {
					$fixed_link = str_replace( $what_replace, $full_tour_slug, $post_link );
				}
				$cache[ $cache_key ] = $fixed_link;

				return $fixed_link;
			}
		}

		return $post_link;
	}*/

	/**
	 * Set permalink for each tour on category tour type.
	 *
	 * @param string $post_link
	 * @param WP_Post $post
	 *
	 * @return string
	 */
	public static function link_tour( $post_link, $post ): string {
		$tour_slug = '';

		try {
			if ( 'product' !== $post->post_type ) {
				return $post_link;
			}

			$product = wc_get_product( $post );
			if ( $product->get_type() !== TB_PHYS_PRODUCT_TYPE ) {
				return $post_link;
			}

			$tour_slug = self::get_tour_base_slug();
			$post_link = site_url( $tour_slug . $post->post_name );
		} catch ( Throwable $e ) {
			error_log( $e->getMessage() );
		}

		if ( empty( $post_link ) ) {
			$post_link = '';
		}

		return apply_filters( 'travel-booking-phys/link/single-tour', $post_link, $post, $tour_slug );
	}

	/**
	 * Run only one time when reload page Frontend.
	 *
	 * @see get_option() hook in this function.
	 * @since 2.1.1
	 * @version 1.0.0
	 * @return mixed|array
	 */
	public static function update_option_rewrite_rules( $wp_rules ) {
		if ( ! is_array( $wp_rules ) ) {
			return $wp_rules;
		}

		$tour_base_url = trailingslashit( str_replace( '/', '', self::get_tour_base_slug() ) );

		$tour_rules    = [
			[ "^{$tour_base_url}(?!page)([^/]+)/?$" => 'index.php?product=$matches[1]' ],
			[ "^{$tour_base_url}page/([0-9]{1,})/?" => 'index.php?is_tour=1&paged=$matches[1]' ],
			[ "^{$tour_base_url}(?!page)([^/]+)/comment-page-([0-9]{1,})/?$" => 'index.php?product=$matches[1]&cpage=$matches[2]' ],
			[ "^{$tour_base_url}?$" => 'index.php?is_tour=1' ],
		];

		$tour_rules = apply_filters( 'travel-booking-phys/rewrite-rules', $tour_rules );

		foreach ( $tour_rules as $rule ) {
			$wp_rules = array_merge( $rule, $wp_rules );
		}

		return $wp_rules;
	}

	public static function get_tour_base_slug() {
		static $base_full;

		if ( is_null( $base_full ) ) {
			$tours_page_id = get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id );
			$base_url      = $tours_page_id && $tours_page_id != '' ? get_page_uri( $tours_page_id ) : __( 'tours', 'travel-booking' );
			$base_url      = apply_filters( 'travel_base_url_single_tour', $base_url );

			if ( $base_url ) {
				$base_url .= '/';
			}

			$front = $GLOBALS['wp_rewrite']->front ?? '';
			if ( $front != '/index.php/' ) {
				$front = '/';
			}
			$base_full = $base_url ? $front . $base_url : '';
		}

		return $base_full;
	}

	public static function rewrite_slug_attribute_woo_by_phys() {
		$taxonomies    = get_object_taxonomies( 'product', 'objects' );
		$attribute_arr = array();

		if ( empty( $taxonomies ) ) {
			return '';
		}

		foreach ( $taxonomies as $tax ) {
			$tax_name = $tax->name;
			if ( 0 !== strpos( $tax_name, 'pa_' ) ) {
				continue;
			}
			if ( ! in_array( $tax_name, $attribute_arr ) ) {
				$attribute_arr[ $tax_name ] = $tax_name;
			}
		}

		foreach ( $attribute_arr as $k => $attr ) {
			$taxonomy_attr = get_taxonomy( $attr );
			$attr_name     = str_replace( 'pa_', '', $attr );
			$slug_rewrite  = 'tour-' . $attr_name;
			if ( $slug_rewrite == 'tour-destination' ) {
				$slug_rewrite = self::$tour_destination_slug;
			} elseif ( $slug_rewrite == 'tour-month' ) {
				$slug_rewrite = self::$tour_month_slug;
			} else {
				$slug_rewrite = apply_filters( 'slug-tour-attr', $slug_rewrite );
			}

			$label                    = $attr_name;
			$taxonomy_data            = array(
				'labels'       => array(
					'name'              => $label,
					'singular_name'     => $taxonomy_attr->labels->singular_name,
					'search_items'      => sprintf( __( 'Search %s', 'woocommerce' ), $label ),
					'all_items'         => sprintf( __( 'All %s', 'woocommerce' ), $label ),
					'parent_item'       => sprintf( __( 'Parent %s', 'woocommerce' ), $label ),
					'parent_item_colon' => sprintf( __( 'Parent %s:', 'woocommerce' ), $label ),
					'edit_item'         => sprintf( __( 'Edit %s', 'woocommerce' ), $label ),
					'update_item'       => sprintf( __( 'Update %s', 'woocommerce' ), $label ),
					'add_new_item'      => sprintf( __( 'Add New %s', 'woocommerce' ), $label ),
					'new_item_name'     => sprintf( __( 'New %s', 'woocommerce' ), $label ),
					'not_found'         => sprintf( __( 'No &quot;%s&quot; found', 'woocommerce' ), $label ),
				),
				'show_in_menu' => false,
			);
			$taxonomy_data['rewrite'] = array(
				'slug'         => $slug_rewrite,
				'with_front'   => false,
				'hierarchical' => true,
			);
			register_taxonomy( $attr, apply_filters( "woocommerce_taxonomy_objects_{$attr}", array( 'product' ) ), apply_filters( "woocommerce_taxonomy_args_{$attr}", $taxonomy_data ) );

			flush_rewrite_rules();
			delete_transient( 'wc_attribute_taxonomies' );
		}
	}

	public static function filter_nav_menu_item_tour_phys( $menu_items ) {
		$isPagesTour = TravelPhysUtility::check_is_tour_archive() || TravelPhysUtility::check_is_tour_single();

		if ( ! $isPagesTour ) {
			return $menu_items;
		}

		if ( ! is_woocommerce() ) {
			return $menu_items;
		}

		$tour_page = get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id );
		$shop_page = (int) wc_get_page_id( 'shop' );

		foreach ( (array) $menu_items as $key => $menu_item ) {

			$classes = (array) $menu_item->classes;

			if ( TravelPhysUtility::check_is_tour_archive() && $tour_page == $menu_item->object_id && 'page' === $menu_item->object ) {
				$menu_items[ $key ]->current = true;
				$classes[]                   = 'current-menu-item';
				$classes[]                   = 'current_page_item';

			} elseif ( is_singular( 'product' ) && ! TravelPhysUtility::check_is_tour_single() ) {
				if ( $shop_page == $menu_item->object_id ) {
					$classes[] = 'current_page_parent';
				} else {
					unset( $classes[ array_search( 'current_page_parent', $classes ) ] );
				}
			} elseif ( TravelPhysUtility::check_is_tour_archive() && is_shop() && $shop_page == $menu_item->object_id && 'page' === $menu_item->object ) {
				$menu_items[ $key ]->current = false;
				unset( $classes[ array_search( 'current-menu-item', $classes ) ] );
				unset( $classes[ array_search( 'current_page_item', $classes ) ] );
			} elseif ( TravelPhysUtility::check_is_tour_single() ) {
				if ( $tour_page == $menu_item->object_id ) {
					$classes[] = 'current_page_parent';
				} else {
					unset( $classes[ array_search( 'current_page_parent', $classes ) ] );
				}
			}

			$menu_items[ $key ]->classes = array_unique( $classes );

		}

		return $menu_items;
	}

	public static function change_link_tour_view_actions_phys( $actions, $post ) {

		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product'
			&& isset( $_GET['product_type'] ) && $_GET['product_type'] == 'tour_phys' ) {

			$permalink_structure = get_option( 'permalink_structure' );

			if ( $permalink_structure != '' ) {
				$permalink_structure_woo = get_option( 'woocommerce_permalinks' );

				if ( key_exists( 'product_base', $permalink_structure_woo ) ) {
					$slug_product_base = str_replace( '/', '', $permalink_structure_woo['product_base'] );
					$slug_tour_base    = str_replace( '/', '', self::get_tour_base_slug() );
					$linkProduct       = str_replace( $slug_product_base, $slug_tour_base, get_permalink( $post->ID ) );

					$actions['view'] = sprintf(
						'<a href="%s" rel="bookmark">%s</a>',
						$linkProduct,
						__( 'View' )
					);
				}
			}
		}

		return $actions;
	}

	/**
	 * Link tour when edit Tour.
	 *
	 * @param $link
	 * @param $post_id
	 * @param $new_title
	 * @param $new_slug
	 * @param $post
	 *
	 * @return array|mixed|string|string[]
	 */
	public static function link_tour_backend( $link, $post_id, $new_title, $new_slug, $post ) {
		$product = wc_get_product( $post_id );
		if ( ! $product || $product->get_type() !== TB_PHYS_PRODUCT_TYPE ) {
			return $link;
		}

		$permalink_structure_woo = get_option( 'woocommerce_permalinks' );
		if ( key_exists( 'product_base', $permalink_structure_woo ) ) {
			$slug_product_base = str_replace( '/', '', $permalink_structure_woo['product_base'] );
			$slug_tour_base    = str_replace( '/', '', self::get_tour_base_slug() );
			$link              = str_replace( $slug_product_base, $slug_tour_base, $link );
		}

		return $link;
	}

	/**
	 * Show link tours in admin bar.
	 *
	 * @param $wp_admin_bar
	 *
	 * @return void
	 */
	public static function admin_bar_menus( $wp_admin_bar ) {
		$wp_admin_bar->add_node(
			array(
				'parent' => 'site-name',
				'id'     => 'tours-page',
				'title'  => esc_html__( 'View Tours', 'travel-booking' ),
				'href'   => get_permalink( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ),
			)
		);
	}
}

TravelPhysUrl::init();

