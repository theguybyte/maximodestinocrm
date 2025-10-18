<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utility
 *
 * @author  physcode
 * @version 1.0.1
 */
class TravelPhysUtility {
	protected static $instance;
	public static $_date_format_default = 'Y/m/d';
	public static $day_arr              = array();
	public static $month_arr            = array();

	protected function __construct() {
		self::$month_arr = apply_filters(
			'tour_month_arr',
			array(
				__( 'January', 'travel-booking' ),
				__( 'February', 'travel-booking' ),
				__( 'March', 'travel-booking' ),
				__( 'April', 'travel-booking' ),
				__( 'May', 'travel-booking' ),
				__( 'June', 'travel-booking' ),
				__( 'July', 'travel-booking' ),
				__( 'August', 'travel-booking' ),
				__( 'September', 'travel-booking' ),
				__( 'October', 'travel-booking' ),
				__( 'November', 'travel-booking' ),
				__( 'December', 'travel-booking' ),
			)
		);

		self::$day_arr = apply_filters(
			'tour_day_arr',
			array(
				__( 'Monday', 'travel-booking' ),
				__( 'Tuesday', 'travel-booking' ),
				__( 'Wednesday', 'travel-booking' ),
				__( 'Thursday', 'travel-booking' ),
				__( 'Friday', 'travel-booking' ),
				__( 'Saturday', 'travel-booking' ),
				__( 'Sunday', 'travel-booking' ),
			)
		);
	}

	public static function get_value_group_discount( $tour_id, $total_qty ) {
		$discount = 0;

		try {
			$tour_group_discount_data_obj     = json_decode( get_post_meta( $tour_id, '_tour_group_discount_data', true ) );
			$tour_group_discount_data_arr_tmp = array();

			foreach ( $tour_group_discount_data_obj as $item ) {
				if ( $total_qty >= ( $item->number_customer->value ) ) {
					$tour_group_discount_data_arr_tmp[ $item->number_customer->value ]                    = array();
					$tour_group_discount_data_arr_tmp[ $item->number_customer->value ]['number_customer'] = $item->number_customer->value;
					$tour_group_discount_data_arr_tmp[ $item->number_customer->value ]['discount']        = $item->discount->value;
				}
			};

			if ( count( $tour_group_discount_data_arr_tmp ) > 0 ) {
				arsort( $tour_group_discount_data_arr_tmp );
				//				echo '<pre>' . print_r( $tour_group_discount_data_arr_tmp, true ) . '</pre>';

				//				$discount = reset( $tour_group_discount_data_arr_tmp )['discount'];
			}
		} catch ( Exception $exception ) {
			echo $exception;
		}

		//		var_dump($discount);

		return $discount;
	}

	/**
	 * @param     int   $tour_id
	 * @param string $name_field
	 * @param    array    $field
	 *
	 * @return string
	 */
	public static function get_type_field( int $tour_id, string $name_field, array $field ) {
		$value = '';

		if ( $name_field == 'phys_tour_regular_price' ) {
			$value = get_post_meta( $tour_id, '_regular_price', true );
		} elseif ( $name_field == 'phys_tour_sale_price' ) {
			$value = get_post_meta( $tour_id, '_sale_price', true );
		} else {
			if ($name_field == 'language') {
				$key = $name_field;
			} else {
				$key = '_' . $name_field;
			}
			$value = get_post_meta( $tour_id, $key, true );
		}

		if ( is_string( $value ) ) {
			$value = htmlentities( $value );
		}

		$label       = $field['label'] ?? '';
		$type_field  = $field['type'] ?? '';
		$default     = $field['default'] ?? '';
		$options     = $field['options'] ?? '';
		$id          = $field['id'] ?? '';
		$class       = $field['class'] ?? '';
		$description = $field['description'] ?? '';

		if ( $value == '' ) {
			$value = $default;
		}

		$html_type_field = '';

		switch ( $type_field ) {
			case 'text':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __( $label, 'travel-booking' ) . '</label>';
				$html_type_field .= '<input type="text" id="' . $id . '" class="' . $class . '" name="' . $name_field . '" value="' . $value . '">';
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			case 'number':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __( $label, 'travel-booking' ) . '</label>';
				$html_type_field .= '<input type="number" id="' . $id . '" class="' . $class . '" name="' . $name_field . '" value="' . $value . '" min="' . ( $field['min'] ?? 0 ) . '">';
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			case 'select':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __( $label, 'travel-booking' ) . '</label>';
				$html_type_field .= '<select name="' . $name_field . '" id="' . $id . '">';
				foreach ( $options as $option ) {
					$selected = '';
					if ( $option === $value ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					$html_type_field .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
				}
				$html_type_field .= '</select>';
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			case 'select_multiple':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __( $label, 'travel-booking' ) . '</label>';
				$html_type_field .= '<select id="' . $id . '" class="' . $class . '" name="' . $name_field . '[]" multiple>';
				foreach ( $options as $option ) {
					$selected = '';
					if ( in_array( $option, $value ) ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					$html_type_field .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
				}
				$html_type_field .= '</select>';
				$html_type_field .= '</p>';
				break;
			case 'checkbox':
				foreach ($options as $option) {
					$html_type_field .= '<p><input type="checkbox" name="' . $name_field . '[]" value="' . $option . '"><span>' . $option . '</span></p>';
				}
				break;
			case 'radio':
				foreach ($options as $option) {
					$html_type_field .= '<p><input type="radio" name="' . $name_field . '" value="' . $option . '"><span>' . $option . '</span></p>';
				}
				break;
			case 'hidden':
				$html_type_field .= '<input type="hidden" id="' . $id . '" class="' . $class . '" name="' . $name_field . '" value="' . $value . '">';
				break;
			case 'image':
				$image_url = '';
				if (!empty($value) && wp_attachment_is_image($value)) {
					$image_url = wp_get_attachment_image_url($value, 'medium');
				}
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __($label, 'travel-booking') . '</label>';
				$html_type_field .= '<button type="button" class="button upload_image_button" data-target="#' . $id . '_id">' . __('Select Image', 'travel-booking') . '</button>';
				$html_type_field .= '<input type="hidden" id="' . $id . '_id" name="' . $name_field . '" value="' . $value . '">';
				if ($image_url) {
					$html_type_field .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($label) . '" style="max-width: 100%; height: auto; display: block; margin-top: 10px;">';
				}
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			default:
				do_action('type_field_travel_' . $type_field, $tour_id, $name_field, $field);
				break;
		}

		return $html_type_field;
	}

	/**
	 * @param int    $tour_id
	 * @param string $name_field
	 * @param array  $field
	 *
	 * @return string
	 */
	public static function show_html_field_admin($tour_id, $name_field, $field) {
		$value = '';

		if ($name_field == 'phys_tour_regular_price') {
			$value = get_post_meta($tour_id, '_regular_price', true);
		} elseif ($name_field == 'phys_tour_sale_price') {
			$value = get_post_meta($tour_id, '_sale_price', true);
		} else {
			$value = get_post_meta($tour_id, $name_field, true);
		}

		if (is_string($value)) {
			$value = htmlentities($value);
		}

		$label       = isset($field['label']) ? $field['label'] : '';
		$type_field  = isset($field['type']) ? $field['type'] : '';
		$default     = isset($field['default']) ? $field['default'] : '';
		$options     = isset($field['options']) ? $field['options'] : '';
		$id          = isset($field['id']) ? $field['id'] : '';
		$class       = isset($field['class']) ? $field['class'] : '';
		$description = isset($field['description']) ? $field['description'] : '';

		if ($value == '') {
			$value = $default;
		}

		$html_type_field = '';
		switch ($type_field) {
			case 'text':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __($label, 'travel-booking') . '</label>';
				$html_type_field .= '<input type="text" id="' . $id . '" class="' . $class . '" name="' . $name_field . '" value="' . $value . '">';
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			case 'select':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __($label, 'travel-booking') . '</label>';
				$html_type_field .= '<select name="' . $name_field . '" id="' . $id . '">';
				foreach ($options as $option) {
					$selected = '';
					if ($option === $value) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					$html_type_field .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
				}
				$html_type_field .= '</select>';
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			case 'select_multiple':
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __($label, 'travel-booking') . '</label>';
				$html_type_field .= '<select id="' . $id . '" class="' . $class . '" name="' . $name_field . '[]" multiple>';
				foreach ($options as $option) {
					$selected = '';
					if (in_array($option, $value)) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					$html_type_field .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
				}
				$html_type_field .= '</select>';
				$html_type_field .= '</p>';
				break;
			case 'checkbox':
				foreach ($options as $option) {
					$html_type_field .= '<p><input type="checkbox" name="' . $name_field . '[]" value="' . $option . '"><span>' . $option . '</span></p>';
				}
				break;
			case 'radio':
				foreach ($options as $option) {
					$html_type_field .= '<p><input type="radio" name="' . $name_field . '" value="' . $option . '"><span>' . $option . '</span></p>';
				}
				break;
			case 'hidden':
				$html_type_field .= '<input type="hidden" id="' . $id . '" class="' . $class . '" name="' . $name_field . '" value="' . $value . '">';
				break;
			case 'image':
				$image_url = '';
				if (!empty($value) && wp_attachment_is_image($value)) {
					$image_url = wp_get_attachment_image_url($value, 'medium');
				}
				$html_type_field .= '<p class="form-field">';
				$html_type_field .= '<label>' . __($label, 'travel-booking') . '</label>';
				$html_type_field .= '<button type="button" class="button upload_image_button" data-target="#' . $id . '_id">' . __('Select Image', 'travel-booking') . '</button>';
				$html_type_field .= '<input type="hidden" id="' . $id . '_id" name="' . $name_field . '" value="' . $value . '">';
				if ($image_url) {
					$html_type_field .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($label) . '" style="max-width: 100%; height: auto; display: block; margin-top: 10px;">';
				}
				$html_type_field .= '<span class="field-desc"><i>' . $description . '</i></span>';
				$html_type_field .= '</p>';
				break;
			default:
				do_action('html_field_travel_' . $type_field, $tour_id, $name_field, $field);
				break;
		}

		return $html_type_field;
	}
	function enqueue_media_uploader_script() {
		wp_enqueue_media();
	?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('.upload_image_button').click(function(e) {
					e.preventDefault();

					var button = $(this);
					var target = $(button.data('target'));

					var frame = wp.media({
						title: 'Select or Upload an Image',
						button: {
							text: 'Use this image'
						},
						multiple: false // Set to true to allow multiple files to be selected
					});

					frame.on('select', function() {
						var attachment = frame.state().get('selection').first().toJSON();
						target.val(attachment.id);
						button.siblings('img').remove(); // Remove previous image
						button.after('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; display: block; margin-top: 10px;">');
					});

					frame.open();
				});
			});
		</script>
	<?php
	}
	public static function get_years_near_now() {
		$years = array();

		$year_now = date('Y');

		array_push($years, $year_now);

		for ($i = 1; $i < 5; $i++) {
			$year_next = date('Y', strtotime('+' . $i . ' year'));
			array_push($years, $year_next);
		}

		return $years;
	}

	/**
	 * @param string $date_string
	 *
	 * @return string
	 */
	public static function convert_date_to_format_default( $date_string = '' ) {
		$date_convert   = '';
		$date           = '';
		$month          = '';
		$year           = '';
		$separator_date = '/';

		$date_format_current = get_option( 'date_format_tour', self::$_date_format_default );

		if ( $date_format_current === 'Y/m/d' ) {
			$date_arr = explode( '/', $date_string );

			$date  = $date_arr[2];
			$month = $date_arr[1];
			$year  = $date_arr[0];
		} elseif ( $date_format_current === 'Y-m-d' ) {
			$date_arr = explode( '-', $date_string );

			$date  = $date_arr[2];
			$month = $date_arr[1];
			$year  = $date_arr[0];
		} elseif ( $date_format_current === 'd/m/Y' ) {
			$date_arr = explode( '/', $date_string );

			$date  = $date_arr[0];
			$month = $date_arr[1];
			$year  = $date_arr[2];
		} elseif ( $date_format_current === 'd-m-Y' ) {
			$date_arr = explode( '-', $date_string );

			$date  = $date_arr[0];
			$month = $date_arr[1];
			$year  = $date_arr[2];
		} elseif ( $date_format_current === 'm/d/Y' ) {
			$date_arr = explode( '/', $date_string );

			$date  = $date_arr[1];
			$month = $date_arr[0];
			$year  = $date_arr[2];
		} elseif ( $date_format_current === 'm-d-Y' ) {
			$date_arr = explode( '-', $date_string );

			$date  = $date_arr[1];
			$month = $date_arr[0];
			$year  = $date_arr[2];
		} elseif ( $date_format_current === 'l, d F, Y' ) {
			$date_arr       = explode( ',', $date_string );
			$date_month_arr = explode( ' ', trim( $date_arr[1] ) );

			$date  = $date_month_arr[0];
			$month = array_search( $date_month_arr[1], self::$month_arr ) + 1;
			$year  = $date_arr[2];
		}

		$date_convert .= $year . $separator_date . $month . $separator_date . $date;

		return $date_convert;
	}

	public static function tour_format_price( $price, $class_for_span_price = '' ) {
		$args = apply_filters(
			'wc_price_args',
			array(
				'ex_tax_label'       => false,
				'currency'           => '',
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
			)
		);

		$unformatted_price = $price;
		$negative          = $price < 0;
		$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf(
			$args['price_format'],
			'<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( $args['currency'] ) . '</span>',
			'<span class="' . $class_for_span_price . '">' . $price . '</span>'
		);
		$return          = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

		if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
			$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

		/**
		 * Filters the string of price markup.
		 *
		 * @param string $return            Price HTML markup.
		 * @param string $price             Formatted price.
		 * @param array  $args              Pass on the args.
		 * @param float  $unformatted_price Price as float to allow plugins custom formatting.
		 */
		return apply_filters( 'tour_format_price', $return, $price, $args, $unformatted_price );
	}

	public static function get_current_url() {
		$schema = is_ssl() ? 'https://' : 'http://';

		return $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	public static function check_is_tour_archive() {
		return get_query_var( 'is_tour' );
	}

	public static function check_is_tour_single() {

		return get_query_var( 'is_single_tour' );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

TravelPhysUtility::getInstance();
