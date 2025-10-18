<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Travel Settings
 *
 * @author  physcode
 * @version 1.2.7
 */
class Tour_Settings_Tab_Phys {
	public static $_tours_show_page_id               = 'tours_show_page_id';
	public static $_permalink_tour_category_base     = 'permalink_tour_category_base';
	public static $_page_redirect_after_tour_booking = 'page_redirect_after_tour_booking';
	public static $_personal_information             = 'travel_personal_information_option';
	public static $_personal_information_enable      = 'travel_personal_information_enable';

	public static function init() {
		add_action( 'init', array( __CLASS__, 'set_tours_options_with_multi_lang' ), 99 );

		// create tab
		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 21 );
		// add fields
		add_action( 'woocommerce_admin_field_multi_select_attribute', array( __CLASS__, 'multi_select_attribute' ) );

		add_action(
			'woocommerce_admin_field_fields_personal_information',
			array(
				__CLASS__,
				'fields_personal_information',
			)
		);
		add_action( 'woocommerce_settings_tabs_tour_settings_phys', array( __CLASS__, 'settings_tab' ) );
		// save fields
		add_action( 'woocommerce_update_options_tour_settings_phys', array( __CLASS__, 'update_settings' ) );
		// show settings link on list plugin
		add_filter( 'plugin_action_links', array( __CLASS__, 'tour_settings_link' ), 10, 2 );

		add_filter( 'display_post_states', array( __CLASS__, 'add_display_post_states' ), 11, 2 );
	}

	/**
	 * Set key options with multi lang (WPML, Polylang)
	 *
	 * @return void
	 */
	public static function set_tours_options_with_multi_lang() {
		$current_lang = '';
		$default_lang = '';

		if ( class_exists( 'SitePress' ) ) {
			$current_lang = apply_filters( 'wpml_current_language', null );
			$default_lang = apply_filters( 'wpml_default_language', null );
		}

		if ( function_exists( 'pll_default_language' ) ) {
			$default_lang = pll_default_language();
			$current_lang = pll_current_language();
		}

		if ( $current_lang !== $default_lang ) {
			self::$_tours_show_page_id               .= '_' . $current_lang;
			self::$_page_redirect_after_tour_booking .= '_' . $current_lang;
			self::$_permalink_tour_category_base     .= '_' . $current_lang;
		}
	}

	public static function add_display_post_states( $post_states, $post ) {
		if ( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) == $post->ID ) {
			$post_states['travel_page_for_tours'] = __( 'Tours Page', 'travel-booking' );
		}

		return $post_states;
	}

	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['tour_settings_phys'] = __( 'Tours', 'travel-booking' );

		return $settings_tabs;
	}

	public static function settings_tab() {
		woocommerce_admin_fields( self::get_setting_tours() );
	}

	public static function get_setting_tours() {
		// create session
		self::create_section();
		self::get_attribute_of_woo_phys();
		global $current_section;
		switch ( $current_section ) {
			case 'search_tours':
				$settings = array(
					'section_title'        => array(
						'title' => __( 'Settings search tours', 'travel-booking' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'tour_options_phys',
					),
					'search_by_attributes' => array(
						'title'   => __( 'Search by attribute', 'travel-booking' ),
						'desc'    => '<br/>' . __( 'Search tour by attribute set on Woocommerce', 'travel-booking' ),
						'id'      => 'tour_search_by_attributes',
						'type'    => 'multi_select_attribute',
						'default' => array(),
						//                      'options' => self::get_attribute_of_woo_phys(),
					),
					'section_end'          => array(
						'type' => 'sectionend',
						'id'   => 'tour_options_phys',
					),
				);
				break;
			case 'single_tour':
				$settings = array(
					'section_title'                  => array(
						'title' => __( 'Review', 'travel-booking' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'tour_options_phys',
					),
					'tour_enable_tour_review'        => array(
						'title'   => __( 'Enable Tour Review Popup', 'travel-booking' ),
						'desc'    => '<br/>' . __( 'Enable tour review popup', 'travel-booking' ),
						'id'      => 'tour_enable_tour_review',
						'type'    => 'checkbox',
						'default' => '',
					),
					'require_login_to_submit_review' => array(
						'title'   => __( 'Require Login', 'travel-booking' ),
						'desc'    => '<br/>' . __( 'Require login to submit a review', 'travel-booking' ),
						'id'      => 'require_login_to_submit_review',
						'type'    => 'checkbox',
						'default' => 'no',
					),
					'require_approved_by_admin'      => array(
						'title'   => __( 'Require Approved by Admin', 'travel-booking' ),
						'desc'    => '<br/>' . __( 'Review must be manually approved by Admin', 'travel-booking' ),
						'id'      => 'require_approved_by_admin',
						'type'    => 'checkbox',
						'default' => 'no',
					),
					'max_images'                     => array(
						'title'             => __( 'Maximum Images', 'travel-booking' ),
						'desc'              => '<br/>' . __( 'Maximum Images', 'travel-booking' ),
						'id'                => 'tour_max_images',
						'type'              => 'number',
						'custom_attributes' => array(
							'min' => 1,
						),
						'default'           => 10,
					),
					'max_file_size'                  => array(
						'title'             => __( 'Maximum File Size (KB)', 'travel-booking' ),
						'desc'              => '<br/>' . __( 'Maximum File Size', 'travel-booking' ),
						'id'                => 'tour_max_file_size',
						'type'              => 'number',
						'custom_attributes' => array(
							'min' => 1,
						),
						'default'           => 10000,
					),
					'tour_enable_ajax'               => array(
						'title'   => __( 'Enable Ajax', 'travel-booking' ),
						'desc'    => '<br/>' . __( 'Enable ajax sort and filter', 'travel-booking' ),
						'id'      => 'tour_enable_ajax',
						'type'    => 'checkbox',
						'default' => '',
					),
					'weather_api'                    => array(
						'title' => __( 'Weather API key', 'travel-booking' ),
						'desc'  => '<a href="' . esc_url( 'https://openweathermap.org/api' ) . '" target="_blank">' . esc_html__( 'Find your API key', 'travel-booking' ) . '</a>',
						'id'    => 'tour_weather_api',
						'type'  => 'text',
					),
					'section_end'                    => array(
						'type' => 'sectionend',
						'id'   => 'tour_options_phys',
					),
				);
				break;
			case 'permalink_tours':
				$settings = array(
					'section_title'                      => array(
						'title' => __( 'Settings permalink tours', 'travel-booking' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'tour_options_phys',
					),
					self::$_permalink_tour_category_base => array(
						'title'   => __( 'Tour category base', 'travel-booking' ),
						'id'      => self::$_permalink_tour_category_base,
						'type'    => 'text',
						'default' => 'tour-category',
					),
					'section_end'                        => array(
						'type' => 'sectionend',
						'id'   => 'tour_options_phys',
					),
				);
				break;
			case self::$_personal_information:
				$settings = array(
					'section_title'                     => array(
						'title' => __( 'Setting Personal information', 'travel-booking' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'tour_options_phys',
					),
					self::$_personal_information_enable => array(
						'title'   => __( 'Enable', 'travel-booking' ),
						'id'      => self::$_personal_information_enable,
						'type'    => 'text',
						'default' => '0',
					),
					self::$_personal_information        => array(
						'title'   => __( 'Tours Personal Information', 'travel-booking' ),
						'id'      => self::$_personal_information,
						'type'    => 'fields_personal_information',
						'default' => '{}',
					),
					'section_end'                       => array(
						'type' => 'sectionend',
						'id'   => 'tour_options_phys',
					),
				);
				break;
			default:
				$tour_page_id   = get_option( self::$_tours_show_page_id );
				$desc_tour_page = '';
				if ( ! empty( $tour_page_id ) ) {
					$desc_tour_page = sprintf(
						'<a href="%2$s">%1$s</a>',
						__( 'View Page', 'travel-booking' ),
						get_permalink( $tour_page_id )
					);
				}

				$page_page_id_redirect = get_option( self::$_page_redirect_after_tour_booking );
				$desc_page_redirect    = '';
				if ( ! empty( $page_page_id_redirect ) ) {
					$desc_page_redirect = sprintf(
						'<a href="%2$s">%1$s</a>',
						__( 'View Page', 'travel-booking' ),
						get_permalink( $page_page_id_redirect )
					);
				}

				$settings = array(
					'section_title'                    => array(
						'title' => __( 'Tour Pages', 'travel-booking' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'catalog_options',
					),
					'page_tour'                        => array(
						'title'    => __( 'Tours Page', 'travel-booking' ),
						'desc'     => $desc_tour_page,
						'id'       => self::$_tours_show_page_id,
						'type'     => 'single_select_page',
						'default'  => '',
						'class'    => 'wc-enhanced-select-nostd',
						'css'      => 'min-width:300px;',
						'desc_tip' => __( 'This sets the base page of your tour - this is where your tour archive will be.', 'travel-booking' ),
					),
					'page_redirect_after_tour_booking' => array(
						'title'             => __( 'Redirect to page after Booking tour', 'travel-booking' ),
						'id'                => self::$_page_redirect_after_tour_booking,
						'type'              => 'single_select_page',
						'desc'              => $desc_page_redirect,
						'default'           => '',
						'option_none_value' => 6,
						'class'             => 'wc-enhanced-select-nostd',
						'css'               => 'min-width:300px;',
					),
					'location_option'                  => array(
						'title'    => __( 'Location Options', 'travel-booking' ),
						'desc_tip' => __( 'Use google Map API or google map iframe', 'travel-booking' ),
						'id'       => 'location_option',
						'default'  => 'google_api',
						'type'     => 'select',
						'options'  => array(
							'google_api'    => __( 'Google API Key', 'travel-booking' ),
							'google_iframe' => __( 'Google Map iframe', 'travel-booking' ),
						),
						'css'      => 'min-width:350px;',
					),
					'google_api_key'                   => array(
						'title'    => __( 'Google API Key', 'travel-booking' ),
						'desc_tip' => __( 'Use show google map in tab Location of single tour', 'travel-booking' ),
						'desc'     => '<br/>' . __( 'How to get API Key https://developers.google.com/maps/documentation/javascript/get-api-key', 'travel-booking' ),
						'id'       => 'google_api_key',
						'default'  => '',
						'type'     => 'text',
						'css'      => 'min-width:350px;',
					),
					'show_adults_children'             => array(
						'title'   => __( 'Separate Ticket for Adult, Children', 'travel-booking' ),
						'desc'    => '<br/>',
						'id'      => 'show_adults_children',
						'type'    => 'select',
						'default' => 0,
						'options' => array(
							1 => 'Yes',
							0 => 'No',
						),
						'class'   => 'wc-enhanced-select-nostd',
						'css'     => 'min-width:300px;',
					),
					'price_children'                   => array(
						'title'    => __( 'Price percent child/adult (%)', 'travel-booking' ),
						'desc'     => '<br/>',
						'desc_tip' => __( 'If you want set price children for each Tour, You can set set value Price Child on Tour when "Show number adults and children" enable', 'travel-booking' ),
						'id'       => 'price_children',
						'type'     => 'number',
						'default'  => '70',
						'css'      => 'max-width:60px;',
					),
					'date_format_tour'                 => array(
						'title'   => __( 'Date format', 'travel-booking' ),
						'id'      => 'date_format_tour',
						'type'    => 'select',
						'default' => 'm/d/Y',
						'options' => array(
							'Y/m/d'     => 'Y/m/d',
							'Y-m-d'     => 'Y-m-d',
							'd/m/Y'     => 'd/m/Y',
							'd-m-Y'     => 'd-m-Y',
							'm/d/Y'     => 'm/d/Y',
							'm-d-Y'     => 'm-d-Y',
							'l, d F, Y' => 'Full - D, d M, Y',
						),
						'css'     => 'max-width:141px;',
					),
					'section_end'                      => array(
						'type' => 'sectionend',
						'id'   => 'tour_options_phys',
					),
				);
				break;
		}

		return apply_filters( 'tour_settings_metabox_phys', $settings );
	}

	public static function create_section() {
		if ( ! empty( $_POST ) ) {
			return;
		}

		$sections = array(
			''                           => __( 'General', 'travel-booking' ),
			'search_tours'               => __( 'Search Tours', 'travel-booking' ),
			'single_tour'                => __( 'Single Tour', 'travel-booking' ),
			'permalink_tours'            => __( 'Permalink Tours', 'travel-booking' ),
			self::$_personal_information => __( 'Travel Personal Information', 'travel-booking' ),
		);
		echo '<ul class="subsubsub">';
		global $current_section;
		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=tour_settings_phys&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
	}

	public static function update_settings() {
		if ( ! isset( $_POST['tour_search_by_attributes'] ) ) {
			$_POST['tour_search_by_attributes'] = array();
		}

		woocommerce_update_options( self::get_setting_tours() );
	}

	public static function multi_select_attribute() {
		$attributes = self::get_attribute_of_woo_phys();
		$selections = get_option( 'tour_search_by_attributes' );

		if ( $selections == '' ) {
			$selections = array();
		}
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for=""><?php echo esc_html( 'Attributes' ); ?></label>
			</th>
			<td class="">
				<select name="tour_search_by_attributes[]" id="" multiple class="wc-enhanced-select">
					<?php
					foreach ( $attributes as $k => $attribute ) {
						if ( in_array( $k, $selections ) ) {
							echo '<option value="' . $k . '" selected>' . $attribute . '</option>';
						} else {
							echo '<option value="' . $k . '">' . $attribute . '</option>';
						}
					}
					?>
				</select>
				<a class="select_all button" href="#"><?php _e( 'Select all', 'travel-booking' ); ?></a>
				<a class="select_none button" href="#"><?php _e( 'Select none', 'travel-booking' ); ?></a>
			</td>
		</tr>
		<?php
	}

	public static function get_attribute_of_woo_phys() {
		$taxonomies = get_object_taxonomies( 'product', 'objects' );

		$attribute_arr = array();
		if ( empty( $taxonomies ) ) {
			return '';
		}

		foreach ( $taxonomies as $tax ) {
			$tax_name  = $tax->name;
			$tax_label = $tax->label;
			if ( 0 !== strpos( $tax_name, 'pa_' ) ) {
				continue;
			}
			if ( ! in_array( $tax_name, $attribute_arr ) ) {
				$attribute_arr[ $tax_name ] = $tax_label;
			}
		}

		return apply_filters( 'attribute_off_woo_phys', $attribute_arr );
	}

	public static function fields_personal_information() {
		echo tb_get_template_admin( 'settings/personal-information.php' );
	}

	public static function tour_settings_link( $links, $file ) {
		if ( $file == 'travel-booking/travel-booking.php' ) {
			$settings = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=tour_settings_phys' ) . '" title="' . __( 'Open the settings page for this plugin', 'travel-booking' ) . '">' . __( 'Settings', 'travel-booking' ) . '</a>';
			array_unshift( $links, $settings );
		}

		return $links;
	}
}

Tour_Settings_Tab_Phys::init();
