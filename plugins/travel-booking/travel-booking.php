<?php
/**
 * Plugin Name: Travel booking
 * Version: 2.2.2
 * Description: Option for Tour
 * Author: Physcode
 * Author URI: http://physcode.com/
 * Requires PHP: 7.4
 * Requires at least: 6.3
 * Text Domain: travel-booking
 * Domain Path: /languages/
 * Requires Plugins: woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelBookingPhyscode {
	private static $instance;
	public static $_version_woo;
	private static $_og_title          = '';
	public static $_folder_plugin_name = '';
	public static $_plugin_base_name   = '';
	public static $_debug              = 0;
	public static $_version            = 0;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct() {
		$is_request_action_heartbeat = $_POST['action'] ?? '';
		if ( 'heartbeat' === $is_request_action_heartbeat ) {
			return;
		}

		// Autoload
		include_once 'vendor/autoload.php';
		$this->plugin_defines();
		$this->includes();
		$this->hooks();
	}

	protected function plugin_defines() {
		self::$_folder_plugin_name = str_replace( array( '/', basename( __FILE__ ) ), '', plugin_basename( __FILE__ ) );
		self::$_plugin_base_name   = plugin_basename( __FILE__ );
		define( 'TOUR_BOOKING_PHYS_PATH', trailingslashit( WP_PLUGIN_DIR . '/' . self::$_folder_plugin_name ) );
		define( 'TOUR_BOOKING_PHYS_URL', plugin_dir_url( __FILE__ ) );
		define( 'TB_PHYS_TEMPLATE_PATH_DEFAULT', TOUR_BOOKING_PHYS_PATH . 'templates' . DIRECTORY_SEPARATOR );
		define( 'TB_PHYS_TEMPLATE_PATH_ADMIN', TB_PHYS_TEMPLATE_PATH_DEFAULT . 'admin' . DIRECTORY_SEPARATOR );
		define( 'TB_PHYS_PRODUCT_TYPE', 'tour_phys' );
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$default_headers = array(
			'Version'    => 'Version',
			'TextDomain' => 'Text Domain',
		);
		$plugin_info     = get_file_data( __FILE__, $default_headers, 'plugin' );
		self::$_version  = $plugin_info['Version'];
	}

	static function register_media_categories() {
		$args = array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'          => __( 'Media Categories', 'travel-booking' ),
				'singular_name' => __( 'Media Category', 'travel-booking' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
		);
		register_taxonomy( 'media_category', array( 'attachment' ), $args );
	}

	protected function includes() {
		// check plugin Woocommerce installed
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_install_plugin_woocommerce' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}

		// check version plugin woo
		$path_plugin_woo                     = WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';
		$default_headers                     = array(
			'Version'    => 'Version',
			'TextDomain' => 'Text Domain',
		);
		$data_plugin_woo                     = get_file_data( $path_plugin_woo, $default_headers, 'plugin' );
		TravelBookingPhyscode::$_version_woo = (float) $data_plugin_woo['Version'];

		add_filter( 'plugin_row_meta', array( $this, 'plugin_travel_row_meta_info' ), 11, 4 );

		include_once 'inc/model/ModelDataToGetPriceDatesTour.php';
		// add product type Tour
		include_once 'inc/product-type-tour.php';
		// add Tour Tab to Woo settings
		include_once 'inc/class-travel-settings.php';
		// add class travel tour search
		include_once 'inc/class-travel-tour-search.php';
		// widget
		include_once 'inc/widgets/class-travel-register-widgets.php';
		// sidebar
		include_once 'inc/class-travel-sidebar.php';
		// get template path
		include_once 'inc/tb-get-template.php';
		// get template loader
		include_once 'inc/class-travel-template-loader.php';
		// Query Travel
		include_once 'inc/class-travel-query.php';
		include_once 'inc/class-travel-url.php';
		include_once 'inc/class-travel-order.php';
		include_once 'inc/class-travel-review-tour.php';
		include_once 'inc/rest-api/class-tour-rest-controller.php';
		include_once 'inc/external-plugin/class-wpml.php';

		if ( ! is_admin() ) {
			$this->load_template_tour_frontend();

			include_once 'inc/class-travel-calculate.php';

			include_once 'inc/class-travel-cart.php';

			// ajax
			include_once 'inc/class-travel-ajax.php';

			include_once 'inc/class-travel-cart-totals.php';

			include_once 'inc/class-travel-checkout.php';

			// Webtomizer deposit
			include_once 'inc/external-plugin/class-webtomizer-deposit.php';
		}
		include_once 'inc/external-plugin/class-woo-pdf-invoices-packing-slips.php';
		include_once 'inc/external-plugin/class-woo-currency-switcher.php';
	}

	protected function hooks() {
		// Register Taxonomy
		add_action( 'after_setup_theme', array( __CLASS__, 'register_taxonomies' ), 20 );
		add_action( 'after_setup_theme', array( __CLASS__, 'register_media_categories' ), 20 );
		add_filter( 'pre_get_document_title', array( __CLASS__, 'title_list_tour_phys' ), 999, 1 );
		add_action( 'wp_head', array( __CLASS__, 'tour_title_setting' ), 1 );
		add_action( 'init', array( $this, 'init_hook' ) );

		// Elementor widget
		add_action(
			'thim_ekit/modules/handle',
			function () {
				include_once 'inc/external-plugin/elementor/TourElementor.php';
			}
		);

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_enqueue_scripts_on_admin' ) );
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_enqueue_scripts_on_frontend' ) );
		}
	}

	public function init_hook() {
		$this->load_plugin_textdomain();
		include_once 'inc/class-travel-utility.php';
		include_once 'inc/class-travel-variation.php';
		include_once 'inc/class-travel-faq.php';
		include_once 'inc/class-travel-hightlight.php';
		include_once 'inc/class-travel-tour-type-meta.php';

		// add tab Booking
		include_once 'inc/class-travel-tab-tour.php';

		if ( class_exists( 'Thim_EL_Kit' ) && defined( 'ELEMENTOR_VERSION' )
			&& version_compare( THIM_EKIT_VERSION, '1.3.0', '<=' ) ) {
			// Load Widgets support Elementor
			include_once 'inc/external-plugin/elementor/TourElementor.php';
		}
	}

	public static function register_taxonomies() {
		$permalink_tour_category_base_option = get_option( Tour_Settings_Tab_Phys::$_permalink_tour_category_base );
		$labels                              = array(
			'name'              => __( 'Tour Types', 'travel-booking' ),
			'singular_name'     => __( 'Tour Type', 'travel-booking' ),
			'search_items'      => __( 'Search Tour Types', 'travel-booking' ),
			'all_items'         => __( 'All Tour Types', 'travel-booking' ),
			'parent_item'       => __( 'Parent Tour Type', 'travel-booking' ),
			'parent_item_colon' => __( 'Parent Tour Type:', 'travel-booking' ),
			'edit_item'         => __( 'Edit Tour Type', 'travel-booking' ),
			'update_item'       => __( 'Update Tour Type', 'travel-booking' ),
			'add_new_item'      => __( 'Add New Tour Type', 'travel-booking' ),
			'new_item_name'     => __( 'New Tour Type Name', 'travel-booking' ),
			'menu_name'         => __( 'Tour Type', 'travel-booking' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'         => $permalink_tour_category_base_option != '' ? $permalink_tour_category_base_option : 'tour-category',
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'tour_phys', array( 'product' ), $args );
		//flush_rewrite_rules();
	}

	public static function tour_title_setting() {
		global $wp_query;
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$tour_show_page_id = get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id );

		if ( $wp_query->get( 'is_tour' ) && $tour_show_page_id ) {
			$title           = apply_filters(
				'title_page_tours_phys',
				get_the_title( $tour_show_page_id ) . ' - ' . get_bloginfo( 'name' ),
				get_the_title( $tour_show_page_id ),
				get_bloginfo( 'name' )
			);
			self::$_og_title = $title;
			$meta_array      = array(
				'og:title' => $title,
				'og:url'   => get_permalink( $tour_show_page_id ),
			);

			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
				add_filter( 'wpseo_opengraph_title', array( __CLASS__, 'change_og_title' ) );
				add_filter( 'wpseo_twitter_title', array( __CLASS__, 'change_og_title' ) );
				add_filter( 'wpseo_opengraph_url', array( __CLASS__, 'change_og_url' ) );
				add_filter( 'wpseo_twitter_url', array( __CLASS__, 'change_og_url' ) );
			} else {
				foreach ( $meta_array as $k => $v ) {
					echo '<meta property="', esc_attr( $k ), '" content="', esc_attr( $v ), '" />', "\n";
				}
			}
		} elseif ( is_single() ) {
			$meta_array = array(
				'og:image' => get_the_post_thumbnail_url( get_the_ID() ),
				'og:url'   => get_permalink( get_the_ID() ),
			);

			foreach ( $meta_array as $k => $v ) {
				echo '<meta property="', esc_attr( $k ), '" content="', esc_attr( $v ), '" />', "\n";
			}
		}
	}

	public static function title_list_tour_phys( $title ) {
		global $wp_query;
		if ( $wp_query->get( 'is_tour' ) ) {
			$title_tours = get_the_title( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) ? get_the_title( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) : apply_filters(
				'title_tour_page_default',
				'Tours'
			);
			$title       = $title_tours . ' - ' . get_bloginfo( 'name' );
		}

		return $title;
	}

	public static function change_og_title( $title ) {
		return self::$_og_title;
	}

	public static function change_og_url( $url ) {
		$tour_show_page_id = get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id );

		return get_permalink( $tour_show_page_id );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'travel-booking' );
		load_textdomain( 'travel-booking', WP_LANG_DIR . '/travel-booking/travel-booking-' . $locale . '.mo' );
		load_plugin_textdomain( 'travel-booking', false, plugin_basename( __DIR__ ) . '/languages' );
	}

	/**
	 * Load asset on Backend.
	 *
	 * @return void
	 */
	public function load_enqueue_scripts_on_admin() {
		// style
		wp_register_style(
			'style-tour-booking-tab',
			TOUR_BOOKING_PHYS_URL . 'assets/css/admin/tab-booking.css',
			array(),
			'2.0'
		);
		wp_enqueue_style( 'style-tour-booking-tab' );

		wp_register_style(
			'style-term-meta-page',
			TOUR_BOOKING_PHYS_URL . 'assets/css/admin/term-meta.css',
			array(),
			'2.0'
		);
		wp_enqueue_style( 'style-term-meta-page' );

		wp_register_style(
			'style-review-images',
			TOUR_BOOKING_PHYS_URL . 'assets/css/admin/review-images.css',
			array(),
			uniqid()
		);
		wp_enqueue_style( 'style-review-images' );

		// script
		//wp_register_script( 'tour-booking-js', TOUR_BOOKING_PHYS_URL . 'assets/js/admin/booking.min.js', array(), '2.0.4' );
		wp_register_script( 'tour-booking-js', TOUR_BOOKING_PHYS_URL . 'assets/js/admin/booking.js', array(), '2.1.2', [ 'strategy' => 'async' ] );
		wp_register_script( 'tour-booking-color-js', TOUR_BOOKING_PHYS_URL . 'assets/js/lib/jscolor.min.js', array(), '2.0.4' );
		wp_enqueue_script( 'tour-booking-js' );
	}

	/**
	 * Load asset on Frontend.
	 *
	 * @return void
	 */
	public function load_enqueue_scripts_on_frontend() {
		$wp_scripts = wp_scripts();
		wp_enqueue_style(
			'jquery-ui-smoothness',
			'//ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css',
			false,
			'2.0.1',
			false
		);
		wp_enqueue_style(
			'style-tour-booking',
			TOUR_BOOKING_PHYS_URL . 'assets/css/frontend/booking.css',
			array(),
			uniqid()
		);
		wp_register_style(
			'style-daterangepicker',
			TOUR_BOOKING_PHYS_URL . 'assets/css/daterangepicker.css',
			array(),
			'3.1'
		);

		wp_enqueue_style( 'tom-select', TOUR_BOOKING_PHYS_URL . 'assets/css/lib/tom-select/tom-select.min.css', array(), '1.0.0' );
		wp_enqueue_style( 'no-ui-slider', TOUR_BOOKING_PHYS_URL . 'assets/css/lib/nouislider/nouislider.min.css', array(), '1.0.0' );

		wp_enqueue_style(
			'tour-search',
			TOUR_BOOKING_PHYS_URL . 'assets/css/frontend/tour-search.css',
			array(),
			'2.1.3'
		);

		wp_register_style(
			'style-product-review',
			TOUR_BOOKING_PHYS_URL . 'assets/css/frontend/product-review.css',
			array(),
			'2.1.3'
		);

		wp_register_style( 'slider-range', TOUR_BOOKING_PHYS_URL . 'assets/css/nouislider.min.css', array(), '11.1.0' );
		wp_register_style( 'barrating', TOUR_BOOKING_PHYS_URL . 'assets/css/barrating.css', array(), '1.0.0' );
		//      wp_enqueue_script( 'jquery-ui-datepicker' );
		// jquery single tour
		wp_register_script(
			'jquery-cookie',
			TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/jquery.cookie.js',
			[],
			'1.4.1',
			[ 'strategy' => 'async' ]
		);
		wp_register_script(
			'moment-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/moment.min.js',
			[],
			'2.13.0',
			[ 'strategy' => 'defer' ]
		);
		wp_register_script(
			'daterangepicker-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/daterangepicker.js',
			array(),
			'3.1',
			[ 'strategy' => 'defer' ]
		);
		wp_register_script(
			'tour-booking-js-frontend',
			TOUR_BOOKING_PHYS_URL . 'assets/dist/js/frontend/booking.js',
			[ 'jquery', 'jquery-ui-datepicker', 'jquery-cookie', 'daterangepicker-js', 'moment-js' ],
			self::$_version,
			[ 'strategy' => 'defer' ]
		);

		/*wp_register_script(
			'tour-booking-js-frontend',
			TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/booking.js',
			array( 'jquery' ),
			uniqid(),
			true
		);*/

		// search Tour
		wp_register_script(
			'barrating-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/jquery.barrating.min.js',
			array(),
			'1.0.0',
			[ 'strategy' => 'async' ]
		);
		wp_register_script(
			'slider-range-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/nouislider.min.js',
			array(),
			'11.1.0',
			[ 'strategy' => 'async' ]
		);
		wp_register_script(
			'widget-search-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/widget-search.js',
			array(),
			'1.0.1',
			[ 'strategy' => 'async' ]
		);
		//sortorder
		wp_register_script(
			'sortorder-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/sortorder.js',
			array(),
			'1.0.0',
			[ 'strategy' => 'async' ]
		);

		//wp_register_script( 'tour-booking-js-frontend', TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/booking.js', array( 'jquery' ), '2.0.7', true );
		if ( get_post_type() == 'product' && is_archive() ) {
			wp_enqueue_script( 'sortorder-js' );
		}
		if ( get_post_type() == 'product' && is_single() ) {
			wp_enqueue_style( 'style-daterangepicker' );
			wp_enqueue_script( 'tour-booking-js-frontend' );
		}
		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			wp_enqueue_script( 'tour-booking-js-frontend' );
		}

		//product review
		wp_register_script(
			'product-tour-review-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/product-review.js',
			array( 'wp-api-fetch' ),
			uniqid(),
			true
		);
		if ( is_product() ) {
			global $post;

			$product = wc_get_product( $post->ID );

			if ( $product->get_type() === TB_PHYS_PRODUCT_TYPE ) {
				wp_enqueue_style( 'style-product-review' );
				wp_enqueue_script( 'product-tour-review-js' );

				$max_images    = get_option( 'tour_max_images', 10 );
				$max_file_size = get_option( 'tour_max_file_size', 10000 );

				wp_localize_script(
					'product-tour-review-js',
					'PRODUCT_REVIEW_GALLERY',
					array(
						'product_id'          => $post->ID,
						'is_enable'           => get_option( 'tour_enable_tour_review', 'no' ),
						'max_images'          => $max_images,
						'max_file_size'       => $max_file_size,
						'max_image_error'     => sprintf( esc_html__( 'The image number is greater than %s', 'travel-booking' ), $max_images ),
						'file_type_error'     => esc_html__( 'The image file type is invalid', 'travel-booking' ),
						'max_file_size_error' => sprintf( esc_html__( 'The maximum file size is %s KB', 'travel-booking' ), $max_file_size ),
						'is_enable_ajax'      => get_option( 'tour_enable_ajax', 'no' ),
					)
				);
			}
		}
		$thousand_separator = get_option( 'woocommerce_price_thousand_sep' );
		$decimal_separator  = get_option( 'woocommerce_price_decimal_sep' );
		// localize script args
		$args = apply_filters(
			'travel_booking_script_localize_array',
			array(
				'message_er_first_name'    => __( 'Enter first name', 'travel-booking' ),
				'message_er_last_name'     => __( 'Enter last name', 'travel-booking' ),
				'message_er_email'         => __( 'Email invalid', 'travel-booking' ),
				'message_er_phone'         => __( 'Enter phone', 'travel-booking' ),
				'message_er_date'          => __( 'Enter Date', 'travel-booking' ),
				'message_er_date_checkin'  => __( 'Enter Date checkin', 'travel-booking' ),
				'message_er_date_checkout' => __( 'Enter Date checkout', 'travel-booking' ),
				'tour_date_format'         => get_option( 'date_format_tour', 'Y/m/d' ),
				'thousand_separator'       => $thousand_separator,
				'decimal_separator'        => $decimal_separator,
			)
		);

		wp_localize_script( 'tour-booking-js-frontend', 'travel_booking', $args );

		wp_register_script(
			'tour-widget-js',
			TOUR_BOOKING_PHYS_URL . 'assets/js/frontend/widgets.js',
			array(),
			'1.0.1',
			[ 'strategy' => 'async' ]
		);

		wp_enqueue_script( 'tour-widget-js' );

		wp_localize_script(
			'tour-widget-js',
			'tour_widget',
			array(
				'rest_url' => get_rest_url(),
			)
		);
	}

	function load_template_tour_frontend() {
		add_action( 'travelbooking_before_main_content', 'travelbooking_output_content_wrapper', 10 );
		add_action( 'travelbooking_after_main_content', 'travelbooking_output_content_wrapper_end', 10 );

		add_action( 'travelbooking_result_count', 'travelbooking_result_count', 20 );
		add_action( 'travelbooking_result_count', 'travelbooking_catalog_ordering', 30 );

		add_action( 'travelbooking_before_loop', 'travelbooking_wrapper_before_loop_start', 10 );
		add_action( 'travelbooking_before_loop', 'tour_booking_breadcrumb', 20 );

		add_action( 'travelbooking_after_loop', 'travelbooking_wrapper_after_loop_end', 10 );

		add_action( 'tour_booking_single_title', 'tour_booking_single_title' );
		add_action( 'tour_booking_single_ratting', 'tour_booking_single_ratting' );
		add_action( 'tour_booking_single_code', 'tour_booking_single_code' );
		add_action( 'tour_booking_single_price', 'tour_booking_single_price' );
		add_action( 'tour_booking_single_booking', 'tour_booking_single_booking' );
		add_action( 'tour_booking_single_gallery', 'tour_booking_single_gallery' );
		add_filter( 'tour_booking_default_product_tabs', 'tour_booking_default_product_tabs' );
		add_filter( 'tour_booking_default_product_tabs', 'tour_booking_sort_product_tabs', 99 );
		add_action( 'tour_booking_single_information', 'tour_booking_single_information' );
		add_action( 'tour_booking_single_related', 'tour_booking_single_related' );
		add_action( 'travelbooking_sidebar', 'travelbooking_get_sidebar', 10 );
	}

	public static function plugin_travel_row_meta_info( $links, $file ) {
		if ( self::$_plugin_base_name === $file ) {
			$row_meta = array(
				'docs'    => '<a href="' . esc_url( 'https://docs.physcode.com/travel-booking-plugin-documentation/' ) . '" aria-label="' . esc_attr__(
					'View Travel booking documentation',
					'travel-booking'
				) . '"><span class="dashicons  dashicons-media-document"></span>' . esc_html__(
					'Docs',
					'travel-booking'
				) . '</a>',
				'support' => '<a href="' . esc_url( 'https://help.physcode.com' ) . '" aria-label="' . esc_attr__(
					'Visit premium customer support',
					'travel-booking'
				) . '"><span class="dashicons  dashicons-smiley"></span>' . esc_html__(
					'Support',
					'travel-booking'
				) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	public function show_note_errors_install_plugin_woocommerce() {
		?>
		<div class="notice notice-error is-dismissible">
			<p>
				<?php
				_e(
					'Please active plugin Woocommerce before active plugin Travel Booking',
					'travel-booking'
				);
				?>
			</p>
		</div>
		<?php
	}
}

$travelBookingPhyscode = TravelBookingPhyscode::instance();
