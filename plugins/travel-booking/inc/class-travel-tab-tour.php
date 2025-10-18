<?php
/**
 * Class TravelPhysTab
 * @version 1.2.6
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TravelPhysTab {
	private static $_instance                      = null;
	public static $_fields_tab_tour_booking        = array();
	public static $_fields_tab_tour_dates_price    = array();
	public static $_fields_tab_tour_group_discount = array();
	public static $_fields_tab_tour_infomations 	= array();
	public static $field_arr                       = array(
		/*** Tab Tour Data ***/
		'tour_content_itinerary' => '',
		'tour_location_address'  => '',
		'tour_location_lat'      => '',
		'tour_location_long'     => '',
		'google_map_iframe'      => '',
		/*** End ***/
	);
	public static $currency = '';

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->initial_tab_tour_booking();

		// add tab
		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'travel_data_tabs' ), 11, 1 );

		// show template fields
		add_action( 'woocommerce_product_data_panels', array( $this, 'tour_booking_options_physcode' ) );

		// type field for display
		add_action(
			'type_field_travel_set_price_dates',
			array(
				__CLASS__,
				'type_field_travel_set_price_dates',
			),
			10,
			3
		);
		add_action( 'type_field_travel_calendar', array( __CLASS__, 'type_field_travel_calendar' ), 10, 3 );
		add_action(
			'type_field_travel_select_array_key_val',
			array(
				__CLASS__,
				'type_field_travel_select_array_key_val',
			),
			10,
			3
		);

		// save fields
		add_action(
			'woocommerce_process_product_meta',
			array(
				__CLASS__,
				'save_all_field_tour_custom_phys',
			),
			9999,
			1
		);
	}

	public static function initial_tab_tour_booking() {
		self::$currency = get_option( 'woocommerce_currency' );

		self::$_fields_tab_tour_booking = apply_filters(
			'fields_tab_tour_booking',
			array(
				/*'phys_tour_calculate_by'               => array(
					'label'   => __( 'Date calculate by day/night', 'travel-booking' ),
					'type'    => 'select_array_key_val',
					'default' => 'Night',
					'options' => array(
						'night' => 'Night',
						'day'   => 'Day',
					),
				),*/
				'phys_tour_regular_price'              => array(
					'label'   => __( 'Regular price', 'travel-booking' ) . '&nbsp;(' . self::$currency . ')',
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'phys_tour_sale_price'                 => array(
					'label'   => __( 'Sale price', 'travel-booking' ) . '&nbsp;(' . self::$currency . ')',
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'price_child'                          => array(
					'label'   => __( 'Child price', 'travel-booking' ) . '&nbsp;(' . self::$currency . ')',
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'tour_code'                            => array(
					'label'   => __( 'Tour Code', 'travel-booking' ),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),

				'tour_max_number_ticket_per_booking'   => array(
					'label'       => __( 'Max number ticket (adult) per booking', 'travel-booking' ),
					'type'        => 'text',
					'default'     => '',
					'description' => __( 'Set empty for no limit', 'travel-booking' ),
				),
				'tour_min_number_ticket_per_booking'   => array(
					'label'       => __( 'Min number ticket (adult) per booking', 'travel-booking' ),
					'type'        => 'text',
					'default'     => '1',
					'description' => __( 'min required = 1', 'travel-booking' ),
				),
				'tour_max_number_children_per_booking' => array(
					'label'       => __( 'Max number children per booking', 'travel-booking' ),
					'type'        => 'text',
					'default'     => '',
					'description' => __( 'Set empty for no limit', 'travel-booking' ),
				),
				'tour_min_number_children_per_booking' => array(
					'label'       => __( 'Min number children per booking', 'travel-booking' ),
					'type'        => 'text',
					'default'     => '',
					'description' => __( 'Set empty for no limit', 'travel-booking' ),
				),
				'tour_show_only_form_enquiry'          => array(
					'label'   => __( 'Show only form send enquiry', 'travel-booking' ),
					'type'    => 'select_array_key_val',
					'default' => '',
					'options' => array(
						0 => 'No',
						1 => 'Yes',
					),
				),
			)
		);

		self::$_fields_tab_tour_dates_price = apply_filters(
			'fields_tab_tour_dates_price',
			array(
				'phys_starts_after'               => array(
					'label'       => __( 'Booking starts after day', 'travel-booking' ),
					'type'        => 'number',
					'min'         => 0,
					'default'     => '0',
					'description' => __( 'Leave 0 for no limit', 'travel-booking' ),
				),
				'phys_enable_tour_fixed_duration' => array(
					'label'       => __( 'Fixed duration', 'travel-booking' ),
					'type'        => 'select_array_key_val',
					'default'     => 1,
					'options'     => array(
						0 => 'No',
						1 => 'Yes',
					),
					'description' => __( 'Set No, customer can choose date check out', 'travel-booking' ),
				),
				'tour_duration'                   => array(
					'label'       => __( 'Duration Text', 'travel-booking' ),
					'type'        => 'text',
					'class'       => '',
					'default'     => '',
					'description' => __( 'Only for displaying as tour information.', 'travel-booking' ),
				),
				'phys_tour_duration_number'       => array(
					'label'       => __( 'Duration (Days)', 'travel-booking' ),
					'type'        => 'text',
					'class'       => 'tour-option-duration',
					'default'     => '1',
					'description' => __( 'Ex: Fill "5" for five days (Only Number is Allowed)', 'travel-booking' ),
				),
				//				'phys_tour_calendar_type'         => array(
				//					'label'   => __( 'Type calendar display on form Booking', 'travel-booking' ),
				//					'type'    => 'select_array_key_val',
				//					'default' => '',
				//					'options' => array(
				//						0 => 'Date picker',
				//						1 => 'Date range picker'
				//					),
				//				),
				'phys_max_year_enable'            => array(
					'label'       => __( 'Max year enable', 'travel-booking' ),
					'type'        => 'select',
					'default'     => '',
					'options'     => TravelPhysUtility::get_years_near_now(),
					'description' => '',
					'id'          => 'phys_max_year_enable',
					'class'       => 'selectpicker',
				),
				'phys_dates_disable'              => array(
					'label'       => __( 'Disable Dates', 'travel-booking' ),
					'type'        => 'hidden',
					'default'     => '',
					'description' => '',
					'id'          => 'phys_dates_disable',
					'class'       => '',
				),
				'phys_datepicker_dates_disable'   => array(
					'label'       => __( 'Disable Dates', 'travel-booking' ),
					'type'        => 'calendar',
					'default'     => '',
					'description' => '',
					'id'          => 'phys_datepicker_dates_disable',
					'class'       => '',
				),
				'phys_price_dates_type'           => array(
					'type'        => 'hidden',
					'default'     => 'price_dates_range',
					'description' => '',
					'id'          => '',
					'class'       => '',
				),
				'phys_price_of_dates_option'      => array(
					'label'   => __( 'Set Price for Dates' . '&nbsp;(' . self::$currency . ')', 'travel-booking' ),
					'default' => '',
					'type'    => 'set_price_dates',
					'id'      => 'wrapper-phys-price-dates',
					'class'   => '',
				),
			)
		);

		self::$_fields_tab_tour_group_discount = apply_filters(
			'fields_tab_tour_group_discount',
			array(
				'tour_group_discount_enable'   => array(
					'label'       => __( 'Enable group discount', 'travel-booking' ),
					'type'        => 'select',
					'options'     => array(
						0 => 'No',
						1 => 'Yes',
					),
					'default'     => 'No',
					'description' => __(
						'Ex: if you create 2 discount boxes, and for the first box, you set up 5 customer with 10% discount and for another box, 10 customer with 20% discount. When customers book smaller than 10 travellers, they will get 10% off. However, if they book for 10, 11, 12 ( and so on ) customer, they will get 20% off.',
						'travel-booking'
					),
				),
				'tour_group_text_form_booking' => array(
					'label'       => __( 'Text Discount Form Booking', 'travel-booking' ),
					'type'        => 'text',
					'default'     => '',
					'description' => '',
				),
				'tour_group_discount_data'     => array(
					'default' => '',
				),
			)
		);
		self::$_fields_tab_tour_infomations = apply_filters(
			'fields_tab_tour_infomations',
			array(
				'language'  => array(
					'label'   => __('Language', 'travel-booking'),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'tour_transport'    => array(
					'label'   => __('Transport', 'travel-booking'),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'tour_accommodation'   => array(
					'label'   => __('Accommodation', 'travel-booking'),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'tour_meals'   => array(
					'label'   => __('Meals', 'travel-booking'),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
				'tour_group_size'   => array(
					'label'   => __('Group size', 'travel-booking'),
					'type'    => 'text',
					'default' => '',
					'options' => '',
				),
			)
		);
	}

	public static function travel_data_tabs( $tabs ) {
		// add tab
		$tabs['tour_booking_phys'] = array(
			'label'    => __( 'Tour Booking', 'travel-booking' ),
			'target'   => 'tour_booking_phys',
			'class'    => array( 'show_if_tour_phys' ),
			'priority' => 5,
		);
		$tabs['phys_tour_infomations'] = array(
			'label'    => __('Tour Infomation', 'travel-booking'),
			'target'   => 'phys_tour_infomations',
			'class'    => array('show_if_tour_phys'),
			'priority' => 7,
		);
		$tabs['tour_variation_phys'] = array(
			'label'    => __( 'Tour Variations', 'travel-booking' ),
			'target'   => 'tour_variation_phys',
			'class'    => array( 'show_if_tour_phys' ),
			'priority' => 10,
		);

		$tabs['phys_tour_dates_price'] = array(
			'label'    => __( 'Dates and Price', 'travel-booking' ),
			'target'   => 'phys_tour_dates_price',
			'class'    => array( 'show_if_tour_phys' ),
			'priority' => 15,
		);

		$tabs['tour_data_phys'] = array(
			'label'    => __( 'Tour Tabs', 'travel-booking' ),
			'target'   => 'tour_data_phys',
			'class'    => array( 'show_if_tour_phys' ),
			'priority' => 20,
		);

		$tabs['phys_tour_group_discount'] = array(
			'label'    => __( 'Group discount', 'travel-booking' ),
			'target'   => 'phys_tour_group_discount',
			'class'    => array( 'show_if_tour_phys' ),
			'priority' => 25,
		);
		$tabs['phys_tour_faq'] = array(
			'label'    => __( 'Tour FAQ', 'travel-booking' ),
			'target'   => 'phys_tour_faq',
			'class'    => array( 'show_if_tour_phys' ),
			'priority' => 30,
		);


		// hide tab
		array_push( $tabs['general']['class'], 'hide_if_tour_phys' );
		array_push( $tabs['shipping']['class'], 'hide_if_tour_phys' );
		array_push( $tabs['linked_product']['class'], 'hide_if_tour_phys' );
		array_push( $tabs['advanced']['class'], 'hide_if_tour_phys' );

		//		var_dump($tabs);

		return $tabs;
	}

	public function tour_booking_options_physcode() {
		tb_get_template_admin( 'metabox-tour-booking.php' );
		tb_get_template_admin( 'metabox-tour-data.php' );
		tb_get_template_admin( 'metabox-tour-variation.php' );
		tb_get_template_admin( 'metabox-tour-dates-price.php' );
		tb_get_template_admin( 'metabox-tour-group-discount.php' );
		tb_get_template_admin( 'metabox-tour-faq.php' );
		tb_get_template_admin('metabox-tour-infomations.php');
	}

	public static function type_field_travel_set_price_dates( $tour_id, $name_field, $field ) {
		$value      = get_post_meta( $tour_id, '_' . $name_field, true );
		$label      = isset( $field['label'] ) ? $field['label'] : '';
		$type_field = isset( $field['type'] ) ? $field['type'] : '';
		$default    = isset( $field['default'] ) ? $field['default'] : '';
		$options    = isset( $field['options'] ) ? $field['options'] : '';
		$id         = isset( $field['id'] ) ? $field['id'] : '';
		$class      = isset( $field['class'] ) ? $field['class'] : '';

		$price_of_dates_option_obj = '';
		if ( $value !== '' && $value !== '{}' ) {
			$price_of_dates_option_obj = json_decode( $value );
		}

		/*** get option Variation ***/
		$tour_variation_enable = get_post_meta( $tour_id, '_tour_variation_enable', true );

		// get fixed_duration
		$fixed_duration = get_post_meta( $tour_id, '_phys_enable_tour_fixed_duration', true );
		?>
		<hr/>
		<div class="form-field" id="<?php echo $id; ?>">
			<h4><?php echo __( $label, 'travel-booking' ); ?></h4>
			<div id="dates-range">
				<p>
					<button class="btn">Save</button>
					<button class="btn" id="add-new-price-dates-range">Add new</button>
				</p>
				<div class="wrapper-price-dates-range">
					<?php
					if ( is_object( $price_of_dates_option_obj ) && isset( $price_of_dates_option_obj->price_dates_range ) ) {
						$class_field = 'field_price_dates_range';

						foreach ( $price_of_dates_option_obj->price_dates_range as $item_id => $price_dates_range ) {
							$start_date = $price_dates_range->start_date;
							$end_date   = $price_dates_range->end_date;

							echo '<div class="wrapper-price-dates-range-item" data-item-id="' . $item_id . '">';
							echo '<div class="header-price-dates-item"><h4>Dates range</h4><span class="toggle-variation-item toggle-down" aria-hidden="true"></span></div>';
							echo '<div class="set_date"><input type="text" class="' . $class_field . '" name="start_date" value="' . $start_date . '" readonly><input type="text" class="' . $class_field . '" name="end_date" value="' . $end_date . '" readonly></div>';
							echo '<div class="price-dates-item">';

							if ( isset( $price_dates_range->prices ) ) {
								foreach ( $price_dates_range->prices as $name_field_price => $price ) {
									if ( $name_field_price == 'regular_price_dates' || $name_field_price == 'child_price_dates' ) {
										echo '<p class="normal"><span>' . $price->label . '</span><input type="text" name="' . $name_field_price . '" class="' . $class_field . '" value="' . $price->price . '"></p>';
									} else {
										// Variation
										foreach ( $price as $k_attr => $v_attr ) {
											echo '<div class="price-dates-variation-item" data-variation-id="' . $name_field_price . '">' .
												 '<span></span>' .
												 '<p class="variation-attr-item">' .
												 '<span>' . $v_attr->label . '</span>' .
												 '<input type="number" name="' . $k_attr . '" class="' . $class_field . '" value="' . $v_attr->price . '" >' .
												 '</p>' .
												 '</div>';
										}
									}
								}
							}
							echo '</div>';
							echo '<span class="remove-item-price-dates dashicons dashicons-no-alt" title="Remove item"></span>';
							echo '</div>';
						}
					}
					?>
				</div>
			</div>

			<input type="hidden" name="<?php echo $name_field; ?>" value="<?php echo htmlentities( $value ); ?>">
		</div>
		<?php
	}

	public static function type_field_travel_calendar( $tour_id, $name_field, $field ) {
		$label      = isset( $field['label'] ) ? $field['label'] : '';
		$value      = get_post_meta( $tour_id, '_' . $name_field, true );
		$type_field = isset( $field['type'] ) ? $field['type'] : '';
		$default    = isset( $field['default'] ) ? $field['default'] : '';
		$options    = isset( $field['options'] ) ? $field['options'] : '';
		$id         = isset( $field['id'] ) ? $field['id'] : '';
		$class      = isset( $field['class'] ) ? $field['class'] : '';

		$html_type_field  = '<div class="form-field">';
		$html_type_field .= '<span>' . $label . '</span>';
		$html_type_field .= '<div id="' . $id . '"></div>';
		$html_type_field .= '<div class="div-right">';
		$html_type_field .= '<button class="disable-all-dates">' . __(
			'Disable all dates',
			'travel-booking'
		) . '</button>';
		$html_type_field .= '<button class="enable-all-dates">' . __(
			'Enable all dates',
			'travel-booking'
		) . '</button>';
		$html_type_field .= '</div>';
		$html_type_field .= '</div>';
		echo $html_type_field;
	}

	public static function type_field_travel_select_array_key_val( $tour_id, $name_field, $field ) {
		$label       = isset( $field['label'] ) ? $field['label'] : '';
		$value       = get_post_meta( $tour_id, '_' . $name_field, true );
		$type_field  = isset( $field['type'] ) ? $field['type'] : '';
		$default     = isset( $field['default'] ) ? $field['default'] : '';
		$options     = isset( $field['options'] ) ? $field['options'] : '';
		$id          = isset( $field['id'] ) ? $field['id'] : '';
		$class       = isset( $field['class'] ) ? $field['class'] : '';
		$description = isset( $field['description'] ) ? $field['description'] : '';

		if ( $value == '' ) {
			$value = $default;
		}

		?>
		<p class="form-field">
			<label><?php echo __( $label, 'travel-booking' ); ?></label>
			<select name="<?php echo $name_field; ?>" id="<?php echo $id; ?>">
				<?php
				foreach ( $options as $k => $label ) {
					if ( $k == $value ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}

					echo '<option value="' . $k . '" ' . $selected . '>' . $label . '</option>';
				}
				?>
			</select>
			<span class="field-desc"><i><?php echo $description; ?></i></span>
		</p>
		<?php
	}

	public static function save_all_field_tour_custom_phys( $post_id ) {
		global $wpdb;

		$default_product_cat = get_option( 'default_product_cat' );

		if ( isset( $_POST['product-type'] ) && $_POST['product-type'] == 'tour_phys' ) {
			foreach ( self::$_fields_tab_tour_booking as $name_field => $field ) {
				$key = '_' . $name_field;
				if ( isset( $_POST[ $name_field ] ) ) {
					$value = $_POST[ $name_field ];

					if ( $name_field == '_regular_price' || $name_field == '_sale_price' ) {
						continue;
					}

					update_post_meta( $post_id, $key, $value );
				}
			}

			foreach ( self::$_fields_tab_tour_dates_price as $name_field => $field ) {
				$key = '_' . $name_field;
				if ( isset( $_POST[ $name_field ] ) ) {
					$value = $_POST[ $name_field ];
					update_post_meta( $post_id, $key, $value );
				}
			}

			foreach ( self::$field_arr as $name_field => $field ) {
				$key = '_' . $name_field;
				if ( isset( $_POST[ $name_field ] ) ) {
					$value = $_POST[ $name_field ];
					update_post_meta( $post_id, $key, $value );
				}
			}

			foreach ( self::$_fields_tab_tour_group_discount as $name_field => $field ) {
				$key = '_' . $name_field;
				if ( isset( $_POST[ $name_field ] ) ) {
					$value = $_POST[ $name_field ];
					update_post_meta( $post_id, $key, $value );
				}
			}
			foreach (self::$_fields_tab_tour_infomations as $name_field => $field) {
				if($name_field == 'language'){
					$key = $name_field;
				}else{
					$key = '_' . $name_field;
				}
				if (isset($_POST[$name_field])) {
					$value = $_POST[$name_field];
					update_post_meta($post_id, $key, $value);
				}
			}


			// Catalog visibility
			if ( isset( $_POST['_visibility'] ) ) {
				$catalog_visibility = wp_unslash( $_POST['_visibility'] );
				update_post_meta( $post_id, '_visibility', $catalog_visibility );
			}

			do_action( 'update_tour_meta_fields', $post_id );

			// Remove default product cat on Tour
			$wpdb->delete(
				$wpdb->term_relationships,
				array(
					'term_taxonomy_id' => $default_product_cat,
					'object_id'        => $post_id,
				)
			);
		}
	}

	/**
	 * Get child price tour
	 *
	 * @param int $tour_id
	 *
	 * @return mixed|void
	 */
	public function get_price_child_tour( $tour_id = 0 ) {
		$price_child = get_post_meta( $tour_id, '_price_child', true );

		return apply_filters( 'phys/travel/child_price', $price_child );
	}

	/**
	 * Get meta value of tour
	 *
	 * @param int $tour_id
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_tour_meta( $tour_id = 0, $key = '' ) {
		$val = get_post_meta( $tour_id, $key, true );

		return apply_filters( 'phys/travel/' . $key, $val );
	}
}

$tab_tour = TravelPhysTab::instance();
