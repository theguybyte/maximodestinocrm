<?php
/**
 * Class TravelPhysVariation
 *
 * @version 1.0.0
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysVariation {
	protected static $instance;
	public static $_fields_variation                    = array();
	public static $tour_variation_item_structure_fields = array();

	protected function __construct() {
		self::$tour_variation_item_structure_fields = apply_filters(
			'tour_variation_item_structure_fields',
			array(
				'label_variation' => array(
					'label' => __( 'Label Variation', 'travel-booking' ),
					'types' => 'input_text',
				),
				'enable'          => array(
					'label' => __( 'Enable item', 'travel-booking' ),
					'types' => array(
						1 => array( 'label' => __( 'Yes' ) ),
						0 => array( 'label' => __( 'No' ) ),
					),
				),
				'type_variation'  => array(
					'label' => __( 'Type Variation', 'travel-booking' ),
					'types' => array(
						'select'   => array( 'label' => __( 'Select' ) ),
						'checkbox' => array( 'label' => __( 'Checkbox' ) ),
						'radio'    => array( 'label' => __( 'Radio' ) ),
						'quantity' => array( 'label' => __( 'Quantity' ) ),
					),
				),
				'set_price'       => array(
					'label' => __( 'Set price or Only show', 'travel-booking' ),
					'types' => array(
						0 => array( 'label' => 'Only Show' ),
						1 => array( 'label' => 'Set price' ),
					),
				),
				'required'        => array(
					'label' => __( 'Required', 'travel-booking' ),
					'types' => array(
						1 => array( 'label' => 'Yes' ),
						0 => array( 'label' => 'No' ),
					),
				),

				/*** For Attribute ***/
				'variation_attr'  => array(
					'label' => array(
						'label' => __( 'Label', 'travel-booking' ),
						'types' => 'input_text',
					),
					'price' => array(
						'label' => __( 'Price', 'travel-booking' ),
						'types' => 'input_text',
					),
					'des'   => array(
						'label' => __( 'Description', 'travel-booking' ),
						'types' => 'input_textarea',
					),
				),
			)
		);
		self::$_fields_variation = apply_filters(
			'fields_tab_tour_variation',
			array(
				'_tour_variation_enable'   => array(
					'label'   => __( 'Enable tour variation', 'travel-booking' ),
					'type'    => 'select',
					'default' => 0,
					'options' => array(
						0 => 'No',
						1 => 'Yes',
					),
				),
				'_tour_variations_options' => array(
					'type'    => 'json',
					'default' => '{}',
				),
			)
		);

		self::add_hook();
	}

	protected function add_hook() {
		add_action( 'update_tour_meta_fields', array( __CLASS__, 'save_tour_fields_variation' ) );
		add_action(
			'tmpl_element_after_date_book_tour_booking_form',
			array(
				__CLASS__,
				'html_tour_variation_on_booking_form',
			)
		);
	}

	public static function getOptionVariation() {

		foreach ( self::$_fields_variation as $k => $v ) {
			${$k} = get_post_meta( get_the_ID(), '_' . $k, true ) !== '' ? get_post_meta(
				get_the_ID(),
				'_' . $k,
				true
			) : '';

		}

		$option = array(
			'webtomizer_deposit_disable'         => get_option( 'wc_deposits_site_wide_disable', 'no' ),
			'webtomizer_deposit_checkout_enable' => get_option( 'wc_deposits_checkout_mode_enabled', 'no' ),
			'webtomizer_deposit_text'            => get_option( 'wc_deposits_button_deposit' ),
			'webtomizer_full_text'               => get_option( 'wc_deposits_button_full_amount' ),
			'webtomizer_deposit_option_text'     => get_option( 'wc_deposits_deposit_option_text' ),
		);

		return $option;
	}

	public static function save_tour_fields_variation( $tour_id ) {
		foreach ( self::$_fields_variation as $k => $v ) {
			if ( isset( $_POST[ $k ] ) ) {
				update_post_meta( $tour_id, $k, $_POST[ $k ] );
			}
		}
	}

	public static function html_tour_variation_on_booking_form() {
		// Todo: Tungnx - get all data variation of tour and set data to send to template.
		tb_get_file_template( 'single-tour' . DIRECTORY_SEPARATOR . 'variation.php' );
	}

	/**
	 * @param object $tour_variations
	 * @param object $tour_variations_options
	 * @param object $price_dates_tour
	 * @param string $currency
	 *
	 * @return string
	 */
	public static function view_variation_detail( $tour_variations, $tour_variations_options, $price_dates_tour, $currency = '' ): string {
		if ( ! is_object( $tour_variations ) ) {
			return '';
		}

		$args_price_format = array( 'currency' => $currency );
		$class_item        = 'variation-item-details';

		do_action( 'before_tour_show_variation_details', $tour_variations, $tour_variations_options );

		$html_variation_view = '<div class="variation-details">';

		foreach ( $tour_variations as $k_variation => $tour_variant ) {
			$tour_variant_item = $tour_variations_options->{$k_variation};
			$label_variation   = $tour_variant_item->label_variation;

			foreach ( $tour_variant as $k_attr => $tour_variant_attr ) {
				$label_attr = $tour_variant_item->variation_attr->{$k_attr}->label;

				if ( isset( $price_dates_tour->{$k_variation}->{$k_attr} ) ) {
					if ( isset( $tour_variant_attr->quantity ) ) {
						$html_variation_view .= '<div class="' . $class_item . '"><strong>' . __(
							$label_variation,
							'travel-booking'
						) . '</strong>:&nbsp;' . $label_attr . '&nbsp;(' . wc_price(
							$price_dates_tour->{$k_variation}->{$k_attr},
							$args_price_format
						) . ')&nbsp;&times;' . $tour_variant_attr->quantity . '</div>';
					} else {
						$html_variation_view .= '<div class="' . $class_item . '"><strong>' . __(
							$label_variation,
							'travel-booking'
						) . '</strong>:&nbsp;' . $label_attr . '&nbsp;(' . wc_price(
							$price_dates_tour->{$k_variation}->{$k_attr},
							$args_price_format
						) . ')</div>';
					}
				} else {
					$html_variation_view .= '<div class="' . $class_item . '"><strong>' . __(
						$label_variation,
						'travel-booking'
					) . '</strong>:&nbsp;' . $label_attr . '</div>';
				}
			}
		}
		$html_variation_view .= '</div>';

		return apply_filters(
			'html_tour_variation_detail_phys',
			$html_variation_view,
			$tour_variations,
			$tour_variations_options,
			$price_dates_tour,
			$currency
		);
	}

	public function get_variation_options( $tour_id ) {
		$tour_variations_option     = get_post_meta( $tour_id, '_tour_variations_options', true );
		$tour_variations_option_obj = json_decode( $tour_variations_option );

		return apply_filters( 'phys/travel/tour_variations_options_obj', $tour_variations_option_obj );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

TravelPhysVariation::getInstance();
