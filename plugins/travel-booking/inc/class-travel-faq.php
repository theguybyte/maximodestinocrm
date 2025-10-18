<?php
/**
 * Class TravelPhysFaq
 *
 * @version 1.0.0
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysFaq {
	protected static $instance;
	public static $_fields_faq = array();
	public static $tour_faq_item_structure_fields = array();

	protected function __construct() {
		self::$tour_faq_item_structure_fields = apply_filters(
			'tour_faq_item_structure_fields',
			array(
				'label_faq'   => array(
					'label' => __( 'Label', 'travel-booking' ),
					'types' => 'input_text',
				),
				'content_faq' => array(
					'label' => __( 'Content', 'travel-booking' ),
					'types' => 'textarea',
				),
			)
		);
		self::$_fields_faq                    = apply_filters(
			'fields_tab_tour_faq',
			array(
				'phys_tour_faq_title'   => array(
					'label'   => __( 'Title', 'travel-booking' ),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'phys_tour_faq_options' => array(
					'type'    => 'json',
					'default' => '{}',
				),
			)
		);

		self::add_hook();
	}

	protected function add_hook() {
		add_action( 'update_tour_meta_fields', array( $this, 'save_tour_fields_faq' ) );
		add_action( 'tour_booking_single_faq', array( $this, 'tour_booking_single_faq' ) );
	}

	public function tour_booking_single_faq() {
		tb_get_file_template( 'single-tour' . DIRECTORY_SEPARATOR . 'faq.php' );
	}

	public static function save_tour_fields_faq( $tour_id ) {
		foreach ( self::$_fields_faq as $k => $v ) {
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

TravelPhysFaq::getInstance();
