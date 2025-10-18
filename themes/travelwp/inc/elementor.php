<?php
require_once TRAVELWP_THEME_DIR . '/inc/group-control-trait.php';

class Travelwp_Elementor_Extend {
	private static $instance = null;

	public function __construct() {
		// add widget categories
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );

		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ), 200 );
		add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'register_icon_custom_elementor' ) );
 	}


	public function register_categories( $elements_manager ) {
		$categories                      = [];
		$categories['travelwp-elements'] =
			[
				'title' => esc_html__( 'TravelWP', 'travelwp' ),
				'icon'  => 'fa fa-plug',
			];

		$old_categories = $elements_manager->get_categories();
		$categories     = array_merge( $categories, $old_categories );

		$set_categories = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	public function register_widgets( $widgets_manager ) {

		$widgets_all = array(
			'text-typed', // 0
			'deals-discounts',// 1
			'gallery',// 2
			'list-attributes',// 3
			'list-tours',// 4
			'search-tour',// 5
			'tours-review', // 6
			'list-category',// 7
			'item-tour'// 8
		);
		if ( ! class_exists( 'TravelBookingPhyscode' ) ) {
			unset( $widgets_all [3] ); // unset list-attributes
			unset( $widgets_all[4] ); // unset list-tours
			unset( $widgets_all[5] ); // unset search-tour
			unset( $widgets_all[6] ); // unset tours-review
			unset( $widgets_all[8] ); // unset tours-review
		}
		if (class_exists('Thim_EL_Kit')) {
 			array_push($widgets_all, 'post-load-ajax');
		}
		if (class_exists('Thim_EL_Kit') &&  class_exists( 'TravelBookingPhyscode' )) {
 			array_push($widgets_all, 'destination-content', 'anchor-tabs');
		}


		if ( ! empty( $widgets_all ) ) {
			foreach ( $widgets_all as $widget ) {
				// register widget for EL
				$file = TRAVELWP_THEME_DIR . "/inc/elementor/$widget.php";

				if ( file_exists( $file ) ) {
					require_once $file;
					$class = ucwords( str_replace( '-', '_', $widget ) );
					$class = sprintf( '\Elementor\Physc_%s_Element', $class );
					if ( class_exists( $class ) ) {
						$widgets_manager->register( new $class() );
					}
				}
			}
		}

	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function register_icon_custom_elementor( $font ) {
		$font['travelwp-fonts'] = array(
			'name'          => 'icon-travelwp',
			'label'         => esc_html__( 'Icon - TravelWP Theme', 'travelwp' ),
			'url'           => TRAVELWP_THEME_URI . '/assets/css/flaticon.css',
			'prefix'        => 'flaticon-',
			'displayPrefix' => 'flaticon',
			'labelIcon'     => 'eicon-global-settings',
			'ver'           => '1.0',
			'fetchJson'     => TRAVELWP_THEME_URI . '/assets/js/flaticon.json',
			'native'        => true,
		);

		return $font;
	}

}

Travelwp_Elementor_Extend::get_instance();
