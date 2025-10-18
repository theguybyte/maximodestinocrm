<?php
/**
 * Class TravelPhysHightlight
 *
 * @version 1.0.0
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysHightlight { 
	protected static $instance;
	public static $_fields_hightlight = array();
	public static $tour_hightlight_item_structure_fields = array();

	protected function __construct() {
		self::$tour_hightlight_item_structure_fields = apply_filters(
			'tour_hightlight_item_structure_fields',
			array(
				'label_hightlight'   => array(
					'label' => __( 'Label', 'travel-booking' ), 
					'types' => 'input_text',
				),
				
				// 'content_hightlight' => array(
				// 	'label' => __( 'Content', 'travel-booking' ),
				// 	'types' => 'textarea',
				// ),
				'image_hightlight' => array(
					'label' => __('Image', 'travel-booking'),
					'types' => 'image',
				),
			)
		);
		self::$_fields_hightlight                    = apply_filters(
			'fields_tab_tour_hightlight',
			array(
				'phys_tour_hightlight_options' => array(
					'type'    => 'json',
					'default' => '{}',
				),
			)
		);

		self::add_hook();
	}

	protected function add_hook() {
		add_action( 'update_tour_meta_fields', array( $this, 'save_tour_fields_hightlight' ) );
	}
	public static function save_tour_fields_hightlight( $tour_id ) {
		foreach ( self::$_fields_hightlight as $k => $v ) {
			if ( isset( $_POST[ $k ] ) ) {
				update_post_meta( $tour_id, $k, $_POST[ $k ] );
			}
		}
	}

	public static function getInstance() { 
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

TravelPhysHightlight::getInstance();
