<?php
namespace TravelBooking;
use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Custom_Post_Type;
use TravelBooking\Modules\ArchiveTour\Init as ArchiveTour;
use TravelBooking\Modules\SingleTour\Init as SingleTour;

class Tour_Elementor {
	use SingletonTrait;

	const CATEGORY_ARCHIVE_TOUR = 'thim_ekit_archive_tours';
	const CATEGORY_SINGLE_TOUR  = 'thim_ekit_single_tours';
	const CATEGORY_RECOMMENDED  = 'thim_ekit_recommended';
	const TOUR_BOOKING          = 'tour_booking';
	const WIDGETS               = array(
		'single-tour'  => array(
			'tours-booking-form',
			'tours-faqs',
			'tours-comment',
			'tours-image',
			'tours-content',
			'tours-itinerary',
			'tours-price',
			'tours-rating',
			'tours-related',
			'tours-hightlight',
			'tours-weather',
		),
		'archive-tour' => array( 'archive-tours' ),
		'global'       => array(
			'list-tours',
			'search-selected',
			'search-tour',
			'attributes',
			// 'item-tours',
		),
		'loop-item'    => array(
			'loop-tour-ratting',
			'loop-tour-sale',
		),
	);
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'tour_enqueue_js_element' ) );
		$this->includes();
		add_filter( 'thim_ekit/rest_api/select_query_conditions', array( $this, 'tour_select_query_conditions' ), 10, 3 );
		add_filter( 'thim_ekit_elementor_category', array( $this, 'travel_add_elementor_widget_categories' ) );
		add_filter( 'thim_ekit/elementor/widgets/list', array( $this, 'add_widgets' ), 90 );
		add_filter( 'thim_ekit/elementor/widget/file_path', array( $this, 'change_widget_path' ), 90, 2 );
		add_filter( 'thim_ekit/rest_api/get_custom_post', array( $this, 'tours_custom_query_post_preview_el' ) );
		add_filter( 'thim-kits/data-loop-item/custom-query', array( $this, 'tours_custom_query_loop_item_el' ) );
		add_filter( 'thim_ekit/elementor/documents/preview_item', array( $this, 'change_documents_preview_item' ), 10, 2 );
		// add_action('elementor/widgets/register', array($this, 'travel_register_widget_elementor'));
		add_filter(
			'thim_ekit/admin/enqueue/localize',
			function ( $localize ) {
				$localize['loop_item']['post_type'][] = array(
					'label' => 'Tour',
					'value' => 'tours',
				);

				return $localize;
			},
			10,
			1
		);
	}

	public function tour_enqueue_js_element() {
		wp_register_style( 'travel-tour-weather', TOUR_BOOKING_PHYS_URL . 'inc/external-plugin/elementor/assets/css/weather.css', array(), '1.0.0' );
		wp_enqueue_style( 'tour-lightgallery', TOUR_BOOKING_PHYS_URL . 'inc/external-plugin/elementor/assets/css/lightgallery.min.css', array(), '1.0.1' );
		wp_enqueue_style( 'tour-element', TOUR_BOOKING_PHYS_URL . 'inc/external-plugin/elementor/assets/css/travel-booking-element.min.css', array(), '1.0.5.4' );
		wp_enqueue_script( 'tour-lightgallery', TOUR_BOOKING_PHYS_URL . 'inc/external-plugin/elementor/assets/js/lightgallery.min.js', array( 'jquery' ), '5.3.5', true );
		wp_register_script( 'flatWeatherJqueryPlugin', TOUR_BOOKING_PHYS_URL . 'inc/external-plugin/elementor/assets/js/flatWeatherJqueryPlugin.js', array( 'jquery' ), '1.0.1', true );
		wp_enqueue_script( 'tour-element', TOUR_BOOKING_PHYS_URL . 'inc/external-plugin/elementor/assets/js/travel-booking-element.js', array( 'jquery' ), '1.0.3.3', true );
		wp_localize_script(
			'tour-element',
			'ajax',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	public function includes() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/dynamic-tags/class-init.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/modules/archive-tour/class-init.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/modules/single-tour/class-init.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/function-tours-widget.php';
	}

	public function add_widgets( $widget_default ) {
		$widgets = self::WIDGETS;

		global $post;

		// Only register archive-post, post-title in Elementor Editor only template.
		if ( $post && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$type = get_post_meta( $post->ID, Custom_Post_Type::TYPE, true );
			if ( $type !== 'archive-tour' ) {
				unset( $widgets['archive-tour'] );
			}
			if ( $type !== 'single-tour' ) {
				unset( $widgets['single-tour'] );
			}
		}
		$widgets = array_merge_recursive( $widget_default, $widgets );
		// var_dump($widgets);
		return $widgets;
	}

	public function change_widget_path( $path, $widget ) {
		foreach ( self::WIDGETS as $key => $widgets ) {
			if ( in_array( $widget, $widgets ) ) {
				$path = TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/widgets/' . $key . '/' . $widget . '.php';
			}
		}
		return $path;
	}

	public function travel_add_elementor_widget_categories( $categories ) {
		return array(
			self::CATEGORY_ARCHIVE_TOUR => array(
				'title' => esc_html__( 'Archive Tours', 'travel-booking' ),
				'icon'  => 'fa fa-plug',
			),
			self::CATEGORY_SINGLE_TOUR  => array(
				'title' => esc_html__( 'Single Tours', 'travel-booking' ),
				'icon'  => 'fa fa-plug',
			),
			self::TOUR_BOOKING          => array(
				'title' => esc_html__( 'Travel Booking', 'travel-booking' ),
				'icon'  => 'fa  fa-globe',
			),
		) + $categories;
	}
	public function change_documents_preview_item( $preview, $type ) {

		if ( $type == SingleTour::instance()->tab ) {
			$preview = 'tours';
		}

		if ( get_the_ID() && $type === 'loop_item' ) {
			$preview = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );
		}

		return $preview;
	}
	public function travel_register_widget_elementor() {
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/widgets/global/list-tours.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/widgets/global/search-selected.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/widgets/global/search-tour.php';
	}
	public function tour_select_query_conditions( $output, $type, $search ) {
		if ( $type == 'tour_type' ) {
			$terms = get_terms(
				array(
					'hide_empty' => false,
					'fields'     => 'all',
					'taxonomy'   => 'tour_phys',
					'search'     => $search,
				)
			);
			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					$output[] = array(
						'id'    => $term->slug,
						'title' => htmlspecialchars_decode( $term->name ) . ' (Slug: ' . $term->slug . ')',
					);
				}
			}
		}
		if ( $type == 'woo_attributes' ) {
			$taxonomies = get_object_taxonomies( 'product', 'objects' );
			foreach ( $taxonomies as $tax ) {
				$tax_name = $tax->name;
				if ( 0 !== strpos( $tax_name, 'pa_' ) ) {
					continue;
				}
				$output[] = array(
					'id'    => $tax_name,
					'title' => htmlspecialchars_decode( str_replace( 'pa_', '', $tax_name ) ),
				);
			}
		}
		if ( $type == 'tour_ids' ) {
			$tour_ids = get_posts(
				array(
					'post_type'      => 'product',
					'posts_per_page' => 100,
					's'              => $search,
					'wc_query'       => 'tours',
					'tax_query'      => array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => array( 'tour_phys' ),
							'operator' => 'IN',
						),
					),
				)
			);

			if ( count( $tour_ids ) > 0 ) {
				foreach ( $tour_ids as $tour_id ) {
					$output[] = array(
						'id'    => $tour_id->ID,
						'title' => htmlspecialchars_decode( $tour_id->post_title ) . ' (ID: ' . $tour_id->ID . ')',
					);
				}
			}
		}

		return $output;
	}
	public function tours_custom_query_loop_item_el( $query_arg ) {
		$query = array();
		if ( $query_arg['post_type'] == 'tours' ) {
			$query = array(
				'post_status' => 'publish',
				'post_type'   => array( 'product' ),
				'wc_query'    => 'tours',
				'p'           => $query_arg['p'],
				'tax_query'   => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'tour_phys' ),
						'operator' => 'IN',
					),
				),
			);
		}
		return $query;
	}
	public function tours_custom_query_post_preview_el( $query_arg ) {
		$query_arg = array(
			'post_status' => 'publish',
			'post_type'   => array( 'product' ),
			'wc_query'    => 'tours',
			'tax_query'   => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'tour_phys' ),
					'operator' => 'IN',
				),
			),
		);
		return $query_arg;
	}
}
Tour_Elementor::instance();
