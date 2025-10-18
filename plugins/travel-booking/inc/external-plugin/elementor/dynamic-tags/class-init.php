<?php
namespace Tours\Elementor\DynamicTags;

use Thim_EL_Kit\SingletonTrait;

class Init_DynamicTags {
	use SingletonTrait;

	public function __construct() {
		$this->include_files();
		add_action( 'elementor/init', array( $this, 'include_files' ) );
		add_action( 'elementor/dynamic_tags/register_tags', array( $this, 'register_tags_tour' ) );
		$this->tour_hook_init();
	}
	public function tour_hook_init() {
		add_filter( 'thim-ekits\dynamic-tags\item-custom-field', array( $this, 'tour_add_custom_name_meta_field' ) );
		add_filter( 'thim-ekits\dynamic-tags\item-terms', array( $this, 'tour_add_category_name_dynamic_tags' ) );
	}

	public function include_files() {
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/dynamic-tags/tags/tour/item-code.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/dynamic-tags/tags/tour/item-location.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/dynamic-tags/tags/tour/item-duration.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/dynamic-tags/tags/tour/item-star.php';
		require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/dynamic-tags/tags/tour/item-price.php';
	}

	public function get_tag_classes_names() {
		return array(
			'Item_Code',
			'Item_Location',
			'Item_Duration',
			'Item_Star',
			'Item_Price',
		);
	}
	public function register_tags_tour( $dynamic_tags ) {
		$dynamic_tags->register_group( 'travel-tour', array( 'title' => esc_html__( 'Tours', 'travel-booking' ) ) );
		$tag_classes_names = $this->get_tag_classes_names();
		foreach ( $tag_classes_names as $tag_class_name ) {
			$dynamic_tags->register_tag( 'Tours\Elementor\DynamicTags\Tags\\' . $tag_class_name );
		}
	}
	public function tour_add_custom_name_meta_field( $options ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$options ['_tour_location_address']  = esc_html__( 'Location', 'travel-booking' );
			$options ['_tour_duration']          = esc_html__( 'Duration', 'travel-booking' );
			$options ['_tour_content_itinerary'] = esc_html__( 'Itinerary', 'travel-booking' );
			$options ['tour_code']               = esc_html__( 'Tour Code', 'travel-booking' );
			$options['language']                 = esc_html__( 'Tour Language', 'travel-booking' );
			$options['_tour_transport']          = esc_html__( 'Tour Transport', 'travel-booking' );
			$options['_tour_accommodation']      = esc_html__( 'Tour Accommodation', 'travel-booking' );
			$options['_tour_meals']              = esc_html__( 'Tour Meals', 'travel-booking' );
			$options['_tour_group_size']         = esc_html__( 'Tour Group size', 'travel-booking' );
			return $options;
		}
	}
	public function tour_add_category_name_dynamic_tags( $options ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$options ['tour_phys'] = esc_html__( 'Tour Type', 'travel-booking' );
			return $options;
		}
	}
}

Init_DynamicTags::instance();
