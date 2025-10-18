<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use Thim_EL_Kit\GroupControlTrait;

class Thim_Ekit_Widget_Tours_Booking_Form extends Widget_Base {
	use GroupControlTrait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}
	public function get_name() {
		return 'thim-ekits-tours-booking-form';
	}

	public function get_title() {
		return esc_html__( 'Tours Booking Form', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_keywords() {
		return array(
			'form',
			'booking',
			'travel',
		);
	}
	protected function register_controls() {
		$this->_register_content_style_options();
		$this->_register_button_style_options();
	}
	protected function _register_content_style_options() {
		$this->start_controls_section(
			'form_section_style_content',
			array(
				'label' => esc_html__( 'Content', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'form_heading_label_style',
			array(
				'label'     => esc_html__( 'Title', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form__content_typography',
				'selector' => 'body {{WRAPPER}} .form-block__title h4',
			)
		);
		$this->add_control(
			'form_item_content__color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .form-block__title h4' => 'color: {{VALUE}}',
				),
			)
		);
		$this->_register_input_style_options();
		// $this->_register_select_style_options();
		$this->end_controls_section();
	}
	protected function _register_input_style_options() {
		$this->add_control(
			'form_heading_input_style',
			array(
				'label'     => esc_html__( 'Input', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'form__input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors'  => array(
					' {{WRAPPER}} #tourBookingForm .form-field input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'form__input_height',
			array(
				'label'     => esc_html__( 'Height', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 250,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} #tourBookingForm .form-field input' => 'height: {{VALUE}}px',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'form__input_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} #tourBookingForm .form-field input',
			)
		);

		$this->add_responsive_control(
			'form__input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} #tourBookingForm .form-field input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form__input_typography',
				'selector' => 'body {{WRAPPER}} #tourBookingForm .form-field input',
			)
		);
		$this->start_controls_tabs( 'tabs_input_style' );

		$this->start_controls_tab(
			'form_tab_input_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);
		$this->add_control(
			'form__input_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} #tourBookingForm .form-field input::placeholder' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'form__input_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #tourBookingForm .form-field input' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'form__input__border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				// 'condition' => array(
				//  '_input_border_border!' => ['none', ''],
				// ),
				'selectors' => array(
					'{{WRAPPER}} #tourBookingForm .form-field input' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'form_tab_input_focus',
			array(
				'label' => esc_html__( 'Focus', 'travel-booking' ),
			)
		);
		$this->add_control(
			'form__input_color_focus',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} #tourBookingForm .form-field input:focus' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'form__input_focus_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #tourBookingForm .form-field input:focus' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'form__input_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				// 'condition' => array(
				//  '_input_border_border!' => ['none', ''],
				// ),
				'selectors' => array(
					'{{WRAPPER}} #tourBookingForm .form-field input:focus' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}

	protected function _register_button_style_options() {
		$this->start_controls_section(
			'form_section_style_button',
			array(
				'label' => esc_html__( 'Button', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->register_button_style( 'form_button', '#tourBookingForm .btn-booking' );
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="widget-tour-booking">';
		$data = array(
			'tour_show_only_form_enquiry' => 0,
		);
		travel_get_template( 'single-tour/booking', compact( 'data' ) );
		echo '</div>';
	}
}
