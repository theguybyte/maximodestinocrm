<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( '\Elementor\Thim_Ekit_Widget_Accordion' ) ) {
	include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/global/accordion.php';
}
class Thim_Ekit_Widget_Tours_Faqs extends Thim_Ekit_Widget_Accordion {

	public function get_name() {
		return 'thim-ekits-tours-faqs';
	}

	public function get_title() {
		return esc_html__( 'Tours Faqs', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_keywords() {
		return array(
			'faq',
			'travel',
		);
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'content',
			array(
				'label' => esc_html__( 'Content', 'travel-booking' ),
			)
		);
		$this->add_control(
			'icon',
			array(
				'label'                  => esc_html__( 'Icon', 'travel-booking' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'default'                => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
				'recommended'            => array(
					'fa-solid'   => array(
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					),
					'fa-regular' => array(
						'caret-square-down',
					),
				),
				'separator'              => 'before',
			)
		);

		$this->add_control(
			'icon_active',
			array(
				'label'                  => esc_html__( 'Active Icon', 'travel-booking' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'exclude_inline_options' => 'svg',
				'default'                => array(
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				),
				'recommended'            => array(
					'fa-solid'   => array(
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					),
					'fa-regular' => array(
						'caret-square-up',
					),
				),
				'skin'                   => 'inline',
				'condition'              => array(
					'icon[value]!' => '',
				),
			)
		);
		$this->end_controls_section();
		$this->_resgister_style_faqs_header();
		$this->register_controls_style_item();
		$this->register_controls_style_title();
		$this->register_controls_style_content();
	}
	protected function _resgister_style_faqs_header() {
		$this->start_controls_section(
			'section_faqs_heading_style',
			array(
				'label' => esc_html__( 'Header', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'faqs_heading_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'travel-booking' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'travel-booking' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'travel-booking' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'travel-booking' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .single-tour-faq-title' => 'text-align: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'faqs_header_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .single-tour-faq .single-tour-faq-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'faqs_header_typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} .single-tour-faq .single-tour-faq-title',
			)
		);
		$this->add_responsive_control(
			'faqs_header_space',
			array(
				'label'     => esc_html__( 'Space(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 5,
				'selectors' => array(
					'body {{WRAPPER}} .single-tour-faq .single-tour-faq-title'  => 'margin-bottom: {{VALUE}}px;',
				),
			)
		);
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		$data     = array(
			'icon'          => $settings['icon'],
			'icon_active'   => $settings['icon_active'],
			'check_faqs_el' => true,
		);
		$tour_id  = get_the_ID();
		$fields   = get_post_meta( $tour_id, 'phys_tour_faq_options', true );
		$fields   = json_decode( $fields, true );
		if ( ! empty( $fields ) && is_array( $fields ) && count( $fields ) != 0 ) {
			travel_get_template( 'single-tour/faq', compact( 'data' ) );
		}
		//
		// do_action('tour_booking_single_faq');
	}
}
