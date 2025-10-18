<?php

namespace Elementor;

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

class Thim_Ekit_Widget_Search_Tour extends Thim_Ekit_Widget_Search_Selected {
	/**
	 * @return string
	 */
	public function get_name() {
		return 'thim-ekits-search-tour';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return 'Filter Search Tour';
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-filter';
	}

	/**
	 * @return string[]
	 */
	public function get_categories() {
		return array(\TravelBooking\Tour_Elementor::TOUR_BOOKING);
	}
	protected function register_controls() {
		$this->_register_control_filter_area();
		$this->_resgister_style_filter_field_item();
		$this->_resgister_style_filter_field_heading();
		$this->_resgister_style_filter_field_label();
		$this->_resgister_style_filter_field_checkbox();
		$this->_resgister_style_filter_field_radio();
		$this->_resgister_style_filter_field_ranger();
		$this->_resgister_style_filter_field_ratting();
		$this->_resgister_style_filter_reponsive_mobile();
		$this->_resgister_style_filter_field_selected_list('show');
		$this->_resgister_style_filter_field_button_reset('show');
		$this->_resgister_style_filter_field_button_submit();
		$this->_resgister_style_filter_field_button_reset_all();
	}

	protected function _register_control_filter_area() {
		$this->start_controls_section(
			'section_filter_area',
			array(
				'label' => esc_html__('Filter Area', 'travel-booking'),
			)
		);
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'filter_type',
			array(
				'label'   => esc_html__('Select Type', 'travel-booking'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'price',
				'options' => array(
					'name'       => esc_html__('Name', 'travel-booking'),
					'attributes' => esc_html__('Attributes', 'travel-booking'),
					'date_time'  => esc_html__('Date time', 'travel-booking'),
					'duration'   => esc_html__('Duration', 'travel-booking'),
					'tour_type'  => esc_html__('Tour types', 'travel-booking'),
					'price'      => esc_html__('Price', 'travel-booking'),
					'rating'     => esc_html__('Rating', 'travel-booking'),
					'selected'   => esc_html__('Selected', 'travel-booking'),
					'reset'      => esc_html__('Button Reset', 'travel-booking'),
					'button'     => esc_html__('Button Submit', 'travel-booking'),
				),
			)
		);
		$repeater->add_control(
			'filter_item_label',
			array(
				'label'       => esc_html__('Label', 'travel-booking'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Type your label here', 'travel-booking'),
				'condition'   => array(
					'filter_type!' => ['button', 'reset'],
				),

			)
		);
		$repeater->add_control(
			'filter_item_name_button',
			array(
				'label'     => esc_html__('Name button', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__('Name button', 'travel-booking'),
				'condition' => array(
					'filter_type' => ['button', 'reset'],
				),
			)
		);
		$repeater->add_control(
			'filter_item_button_icon',
			array(
				'label'       => esc_html__('Icon', 'textdomain'),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => true,
				'condition'   => array(
					'filter_type!' => ['name', 'date_time', 'attributes', 'selected', 'price', 'duration', 'tour_type', 'rating'],
				),
			)
		);
		$repeater->add_control(
			'filter_attributes_type',
			array(
				'label'       => esc_html__('Show attribute', 'travel-booking'),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'all',
				'options'     => $this->_get_all_attribute_product(),
				'description' => sprintf(__('You can configure it in %s.', 'travelwp'), '<a href="' . admin_url('/admin.php?page=wc-settings&tab=tour_settings_phys&section=search_tours') . '" target="_blank">' . __('Tour Search settings', 'travel-booking') . '</a>'),
				'condition'   => array(
					'filter_type' => 'attributes',
				),
			)
		);
		$repeater->add_control(
			'filter_attributes_icon',
			array(
				'label'     => esc_html__('Icon attribute', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'skin'      => 'inline',
				'condition' => array(
					'filter_type' => 'attributes',
				),
			)
		);
		$repeater->add_control(
			'filter_item_placeholder',
			array(
				'label'       => esc_html__('Placeholder', 'travel-booking'),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Type your placeholder here', 'travel-booking'),
				'condition'   => array(
					'filter_type' => array('name', 'date_time', 'attributes'),
				),
			)
		);
		$repeater->add_responsive_control(
			'filter_item_width',
			array(
				'label'     => esc_html__('Width Content(%)', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'default'   => 100,
				'selectors' => array(
					'{{WRAPPER}} .wrapper-search-fields {{CURRENT_ITEM}}' => 'flex-basis: {{VALUE}}%;',
				),
			)
		);
		$this->add_control(
			'filter_list',
			array(
				'label'       => esc_html__('Repeater Field filter', 'travel-booking'),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'filter_type' => array('button'),
					),
				),
				// 'title_field' => '<span style="text-transform: capitalize;">{{{ filter_type.replace("_", " ") }}}</span>',
				'title_field' => '<span style="text-transform: capitalize;">{{{ filter_type }}}</span>',
			)
		);

		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_item() {
		$this->start_controls_section(
			'section_filter_field_item_style',
			array(
				'label' => esc_html__('Item', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'filter_item_space_bottom',
			array(
				'label'     => esc_html__('Space(px)', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .tour-search-field' => 'margin-bottom: {{VALUE}}px;',
					'body {{WRAPPER}} .tour-search-field:last-child' => 'margin-bottom:0;',
				),
			)
		);
		$this->add_responsive_control(
			'filter_item_padding',
			array(
				'label'      => esc_html__('Padding', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em'),

				'selectors'  => array(
					'{{WRAPPER}} .tour-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'filter_item__border',
				// 'exclude'  => array('color'),
				'selector' => '{{WRAPPER}} .tour-search-field',
			)
		);
		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_heading() {
		$this->start_controls_section(
			'section_filter_field_heading_style',
			array(
				'label' => esc_html__('Title', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_filter_heading_typography',
				'label'    => esc_html__('Typography', 'travel-booking'),
				'selector' => '{{WRAPPER}} .item-filter-heading',
			)
		);
		$this->add_control(
			'filter_title_color',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .item-filter-heading' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'filter_title_space_bottom',
			array(
				'label'     => esc_html__('Space(px)', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .item-filter-heading'  => 'margin-bottom: {{VALUE}}px !important;;',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _resgister_style_filter_field_label() {
		$this->start_controls_section(
			'section_filter_field_label_style',
			array(
				'label' => esc_html__('Label', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_field_label_typography',
				'label'    => esc_html__('Typography', 'travel-booking'),
				'selector' => '{{WRAPPER}} label',
			)
		);
		$this->add_responsive_control(
			'filter_field_label_space',
			array(
				'label'     => esc_html__('Space(px)', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 1000,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .item,{{WRAPPER}}  .wrapper-content li' => 'margin-bottom: {{VALUE}}px;',
					'body {{WRAPPER}} .item:last-child,{{WRAPPER}}  .wrapper-content li:last-child' => 'margin-bottom:0;',
				),
			)
		);
		$this->add_responsive_control(
			'filter_field_label_padding',
			array(
				'label'      => esc_html__('Padding', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em'),
				'selectors'  => array(
					'body {{WRAPPER}} label,{{WRAPPER}}  .wrapper-content li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs('tabs_filter_field_label_style');
		$this->start_controls_tab(
			'tab_filter_field_label_normal',
			array(
				'label' => esc_html__('Normal', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_label_color',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_filter_field_label_hover',
			array(
				'label' => esc_html__('Hover', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_label_hover',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} label:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_filter_field_label_active',
			array(
				'label' => esc_html__('Active', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_label_active',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input:checked+label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function _resgister_style_filter_field_checkbox() {
		$this->start_controls_section(
			'section_filter_field_checkbox_style',
			array(
				'label' => esc_html__('Checkbox', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'filter_field_checkbox_size',
			array(
				'label'      => esc_html__('Box size', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}  ul li input[type=checkbox]' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;min-width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		$this->add_responsive_control(
			'filter_field_checkbox_space',
			array(
				'label'      => esc_html__('Offset Right', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ul li input[type=checkbox]' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'filter_field_checkbox_box_border',
				'exclude'  => array('color'),
				'selector' => '{{WRAPPER}} ul li input[type=checkbox]',
			)
		);
		$this->add_control(
			'filter_field_checkbox_box__border_radius',
			array(
				'label'      => esc_html__('Border Radius', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors'  => array(
					'{{WRAPPER}}  ul li input[type=checkbox]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'filter_field_checkbox_checked_size',
			array(
				'label'      => esc_html__('Checked Size', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ul li input[type=checkbox]:before' => 'font-size: {{SIZE}}{{UNIT}} !important;width: {{SIZE}}{{UNIT}}!important;height: {{SIZE}}{{UNIT}}!important;',
				),
			)
		);
		$this->start_controls_tabs('tabs_filter_field_checkbox_style');
		$this->start_controls_tab(
			'tab_filter_field_checkbox_normal',
			array(
				'label' => esc_html__('Normal', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_checkbox_checked_color',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=checkbox]:checked:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ul li input[type=checkbox]:checked:before' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'filter_field_checkbox_background_color',
			array(
				'label'     => esc_html__('Background Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  ul li input[type=checkbox]' => 'background-color: {{VALUE}};outline: 0;',
				),
			)
		);

		$this->add_control(
			'filter_field_checkbox_border_color',
			array(
				'label'     => esc_html__('Border Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'filter_field_checkbox_box_border_border!' => array('none', ''),
				),
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=checkbox]' => 'border-color: {{VALUE}};outline: 0;',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_filter_field_checkbox_hover',
			array(
				'label' => esc_html__('Hover', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_checkbox_hover_background_color',
			array(
				'label'     => esc_html__('Background Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li:hover input[type=checkbox]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_field_checkbox_hover_border_color',
			array(
				'label'     => esc_html__('Border Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'filter_field_checkbox_box_border_border!' => array('none', ''),
				),
				'selectors' => array(
					'{{WRAPPER}} ul li:hover input[type=checkbox]' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_filter_field_checkbox_focus',
			array(
				'label' => esc_html__('Focus', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_checkbox_fosus_background_color',
			array(
				'label'     => esc_html__('Background Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=checkbox]:checked' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_field_checkbox_fosus_border_color',
			array(
				'label'     => esc_html__('Border Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'filter_field_checkbox_box_border_border!' => array('none', ''),
				),
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=checkbox]:checked' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_radio() {
		$this->start_controls_section(
			'section_filter_field_radio_style',
			array(
				'label' => esc_html__('Radio', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'filter_field_radio_size',
			array(
				'label'      => esc_html__('Box size', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ul li input[type=radio]' => 'width: {{SIZE}}{{UNIT}}!important;height: {{SIZE}}{{UNIT}}!important;min-width: {{SIZE}}{{UNIT}}!important;',
				),
			)
		);
		$this->add_responsive_control(
			'filter_field_radio_space',
			array(
				'label'      => esc_html__('Offset Right', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ul li input[type=radio]' => 'margin-right: {{SIZE}}{{UNIT}}!important;',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'filter_field_radio_box_border',
				'exclude'  => array('color'),
				'selector' => '{{WRAPPER}} ul li input[type=radio]',
			)
		);
		$this->add_control(
			'filter_field_radio_box__border_radius',
			array(
				'label'      => esc_html__('Border Radius', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors'  => array(
					'{{WRAPPER}} ul li input[type=radio]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'filter_field_radio_checked_size',
			array(
				'label'      => esc_html__('Checked Size', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ul li input[type=radio]:after' => 'font-size: {{SIZE}}{{UNIT}};width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs('tabs_filter_field_radio_style');
		$this->start_controls_tab(
			'tab_filter_field_radio_normal',
			array(
				'label' => esc_html__('Normal', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_radio_checked_color',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=radio]::after' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'filter_field_radio_background_color',
			array(
				'label'     => esc_html__('Background Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  ul li input[type=radio]' => 'background-color: {{VALUE}};outline: 0;',
				),
			)
		);

		$this->add_control(
			'filter_field_radio_border_color',
			array(
				'label'     => esc_html__('Border Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'filter_field_radio_box_border_border!' => array('none', ''),
				),
				'selectors' => array(
					'{{WRAPPER}}  ul li input[type=radio]' => 'border-color: {{VALUE}};outline: 0;',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_filter_field_radio_hover',
			array(
				'label' => esc_html__('Hover', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_radio_hover_background_color',
			array(
				'label'     => esc_html__('Background Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li:hover input[type=radio]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_field_radio_hover_border_color',
			array(
				'label'     => esc_html__('Border Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'filter_field_radio_box_border_border!' => array('none', ''),
				),
				'selectors' => array(
					'{{WRAPPER}} ul li:hover input[type=radio]' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_filter_field_radio_focus',
			array(
				'label' => esc_html__('Focus', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_field_radio_fosus_background_color',
			array(
				'label'     => esc_html__('Background Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=radio]:focus,{{WRAPPER}} ul li input[type=radio]:checked' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_field_radio_fosus_border_color',
			array(
				'label'     => esc_html__('Border Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'filter_field_radio_box_border_border!' => array('none', ''),
				),
				'selectors' => array(
					'{{WRAPPER}} ul li input[type=radio]:focus,{{WRAPPER}} ul li input[type=radio]:checked' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_ranger() {
		$this->start_controls_section(
			'section_filter_field_ranger_style',
			array(
				'label' => esc_html__('Ranger', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'filter_field_ranger_line_color',
			array(
				'label'     => esc_html__('Line Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} #tour-price-range .noUi-target' => 'background: {{VALUE}};',
					'{{WRAPPER}} #fromSlider,{{WRAPPER}} #toSlider' => 'background: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'filter_field_ranger_active_line_color',
			array(
				'label'     => esc_html__('Active Line Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .noUi-connect' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'filter_field_ranger_button_line_color',
			array(
				'label'     => esc_html__('Button Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} #tour-price-range .noUi-handle' => 'background: {{VALUE}};',
					'{{WRAPPER}} .sliders_control input[type="range"]::-webkit-slider-thumb' => 'background: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _resgister_style_filter_field_ratting() {
		$this->start_controls_section(
			'section_filter_field_ratting_style',
			array(
				'label' => esc_html__('Ratting', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'filter_field_ratting_star_heading',
			array(
				'label'     => esc_html__('Star', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		$this->add_control(
			'filter_field_ratting_star_color',
			array(
				'label'     => esc_html__('Star Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .star i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'filter_field_ratting_star_empty_color',
			array(
				'label'     => esc_html__('Star empty Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .star i.fas' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'filter_field_ratting_star_size',
			array(
				'label'      => esc_html__('Star Size', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em', 'rem'),
				'selectors'  => array(
					'{{WRAPPER}} .star i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'filter_field_ratting_space_between',
			array(
				'label'      => esc_html__('Space Between', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'default'    => array(
					'unit' => 'px',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .star i' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body:not(.rtl) {{WRAPPER}} .star i:last-child' => 'margin-right:0;',
					'body.rtl {{WRAPPER}} .star i'       => 'margin-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .star i:last-child' => 'margin-left:0;',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _resgister_style_filter_reponsive_mobile() {
		$this->start_controls_section(
			'section_filter_form_toggle_style',
			array(
				'label' => esc_html__('Responsive mobile', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'heading_form_position',
			array(
				'label'     => esc_html__('Form Position', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'filter_form_toggle_offset_orientation_h',
			array(
				'label'        => esc_html__('Horizontal Orientation', 'travel-booking'),
				'type'         => Controls_Manager::CHOOSE,
				'toggle'       => false,
				'default'      => 'left',
				'options'      => array(
					'left'  => array(
						'title' => esc_html__('Left', 'travel-booking'),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__('Right', 'travel-booking'),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type'  => 'ui',
				'prefix_class' => 'travel-filter-form-toggle-offset-',
			)
		);
		// $this->add_responsive_control(
		//  'filter_form_toggle_indicator_offset_h',
		//  array(
		//      'label'       => esc_html__('Offset(px)', 'travel-booking'),
		//      'type'        => Controls_Manager::NUMBER,
		//      'label_block' => false,
		//      'min'         => -200,
		//      'step'        => 1,
		//      'selectors'   => array(
		//          '{{WRAPPER}}' => '--travel-toggle-offset:{{VALUE}}px',
		//      ),
		//  )
		// );
		$this->add_responsive_control(
			'filter_form_toggle_width',
			array(
				'label'      => esc_html__('Width Content', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', '%'),
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'body {{WRAPPER}} .show-filter-toggle .wrapper-search-fields' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'filter_form_toggle_max_height',
			array(
				'label'      => esc_html__('Max Height', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'vh'),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'vh' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'vh',
					'size' => 100,
				),
				'selectors'  => array(
					'body {{WRAPPER}} .show-filter-toggle .wrapper-search-fields' => 'max-height: {{SIZE}}{{UNIT}};overflow: auto;',
				),
			)
		);
		$this->add_control(
			'heading_button_mobile',
			array(
				'label'     => esc_html__('Button', 'travel-booking'),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_button_toggle_typography',
				'label'    => esc_html__('Typography', 'travel-booking'),
				'selector' => '{{WRAPPER}} .filter-button-toggle-wp',
			)
		);
		$this->_resgister_option_general_style_filter_fields('filter_button_toggle', '.filter-button-toggle-wp');
		$this->start_controls_tabs('tabs_filter_button_toggle_style');
		$this->start_controls_tab(
			'tab_filter_button_toggle_normal',
			array(
				'label' => esc_html__('Normal', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_button_toggle_color',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter-button-toggle-wp' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'filter_button_toggle_bgcolor',
			array(
				'label'     => esc_html__('Background', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter-button-toggle-wp' => 'background: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_button_toggle_hover',
			array(
				'label' => esc_html__('Hover', 'travel-booking'),
			)
		);
		$this->add_control(
			'filter_button_toggle_color_hover',
			array(
				'label'     => esc_html__('Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter-button-toggle-wp:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'filter_button_toggle_bgcolor_hover',
			array(
				'label'     => esc_html__('Background', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .filter-button-toggle-wp:hover' => 'background: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_button_submit() {
		$this->start_controls_section(
			'section_filter_field_button_submit_style',
			array(
				'label' => esc_html__('Button Submit', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'width_btn',
			[
				'label' => esc_html__('Width', 'travel-booking'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .button-searh-tour' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'height_btn',
			[
				'label' => esc_html__('Height', 'travel-booking'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'vh', 'custom'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .button-searh-tour' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->resgister_option_general_style_filter_field_selected('filter_field_button_submit', '.button-searh-tour', '.no-class');
		$this->resgister_option_tabs_color_general_filter_selected('filter_field_button_submit', '.button-searh-tour', '.no-class');
		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_button_reset_all() {
		$this->start_controls_section(
			'section_filter_field_button_reset_all_style',
			array(
				'label' => esc_html__('Button Reset', 'travel-booking'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__('Width', 'travel-booking'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => ['px', '%', 'em', 'rem', 'vw', 'custom'],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .button-reset-searh-tour' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__('Height', 'travel-booking'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'vh', 'custom'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .button-reset-searh-tour' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->resgister_option_general_style_filter_field_selected('filter_field_button_reset_all', '.button-reset-searh-tour', '.no-class');
		$this->resgister_option_tabs_color_general_filter_selected('filter_field_button_reset_all', '.button-reset-searh-tour', '.no-class');
		$this->end_controls_section();
	}
	protected function _resgister_option_general_style_filter_fields($label, $class) {
		$this->add_responsive_control(
			$label . '_padding',
			array(
				'label'      => esc_html__('Padding', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em'),

				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			$label . '_margin',
			array(
				'label'      => esc_html__('Margin', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em'),
				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => $label . '_border',
				// 'exclude'  => array('color'),
				'selector' => '{{WRAPPER}} ' . $class,
			)
		);
		$this->add_control(
			$label . '_border_radius',
			array(
				'label'      => esc_html__('Border Radius', 'travel-booking'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}
	protected function _register_option_field_counter_style($label, $class) {
		$this->add_control(
			$label . 'filter_field_counter_items_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__('Counter', 'travel-booking'),
				'separator' => 'before',
			)
		);
		$this->add_control(
			$label . 'filter_field_counter_items_text_color',
			array(
				'label'     => esc_html__('Text Color', 'travel-booking'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			$label . 'filter_field_counter_items_font_size',
			array(
				'label'      => esc_html__('Font Size', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array('%', 'px', 'em'),
				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			$label . 'filter_field_counter_items_space',
			array(
				'label'      => esc_html__('Space', 'travel-booking'),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array('%', 'px', 'em'),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} ' . $class => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $class       => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	protected function _get_all_attribute_product() {
		$attributes = array('' => esc_html__('Select...', 'travel-booking'));
		if (get_option('tour_search_by_attributes')) {
			$option_attribute_to_search = get_option('tour_search_by_attributes');
			foreach ($option_attribute_to_search as $attribute_to_search) {
				$attributes[$attribute_to_search] = ucfirst(str_replace('pa_', '', $attribute_to_search));
			}
		}
		return $attributes;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if (is_array($settings['filter_list']) && ! empty($settings['filter_list'][0])) {
			wp_enqueue_style('style-daterangepicker');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('moment-js');
			wp_enqueue_script('daterangepicker-js');
			wp_enqueue_script('tour-search');
			wp_enqueue_style('slider-range');
			wp_enqueue_script('slider-range-js');
			// wp_enqueue_script('widget-search-js');
			if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
				$class_button_show = '';
			} else {
				$class_button_show = ' button-hidden-desktop ';
			}
?>
			<div class="filter-button-toggle-wp <?php echo $class_button_show; ?>">
				<?php echo esc_html__('Filter', 'travel-booking'); ?>
			</div>
			<?php
		}
		echo '<div class="tour-search travel-product-filter"> 
			<form method="get" action="' . home_url() . '" id="search_tour_form">
			<div class="wrapper-search-fields">';
		// echo '<span class="close-filter-product"><i class="fa fa-times" aria-hidden="true"></i></span>';
		echo '<div class="tour-search-fields-close close-filter-product"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15 19.5L7.5 12L15 4.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>'. esc_html__('Back', 'travel-booking').'</div>';
		echo ' <input type="hidden" name="tour_search" value="1">';
		$orderby = isset($_GET['orderbyt']) ? wc_clean($_GET['orderbyt']) : '';
		echo sprintf(' <input type="hidden" name="orderbyt" value="%s">', $orderby);
		foreach ($settings['filter_list'] as $key => $field) {
			$extra_class  = '';
			$extra_class .= ' elementor-repeater-item-' . $field['_id'];
			switch ($field['filter_type']) {
				case 'name':
					$this->html_render_field_tour_name($field);
					break;
				case 'attributes':
					$data = array(
						'extra_class'    => $extra_class,
						'tour_tax_param' => get_query_var('tourtax') ?? '',
						'placeholder'    => $field['filter_item_placeholder'] ?? '', 
						'show_attr'      => $field['filter_attributes_type'] ?? 'all',
						'label'          => $field['filter_item_label'],
						'icon_attr'      => $field['filter_attributes_icon'],
					);
					travel_get_template('tour-search/fields/destination', compact('data'));
					break;
				case 'date_time':
					$data = array(
						'extra_class' => ' elementor-repeater-item-' . $field['_id'],
						'date_range'  => $_GET['date_range'] ?? '',
						'placeholder' => $field['filter_item_placeholder'] ?? '',
						'label'       => $field['filter_item_label'],

					);
					travel_get_template('tour-search/fields/date-time', compact('data'));
					break;
				case 'tour_type':
					$data = array(
						'extra_class' => $extra_class,
					);
					$this->html_get_term_tour_type($field);
					break;
				case 'duration':
					$data = array(
						'extra_class' => $extra_class,
						'duration'    => $_GET['duration'] ?? '',
						'label'       => $field['filter_item_label'],
					);
					travel_get_template('tour-search/fields/duration', compact('data'));

					break;
				case 'price':
					$min_price_setting = get_option('advanced_search_min_price', 0);
					$max_price_setting = get_option('advanced_search_max_price', 100);
					$data              = array(
						'extra_class'    => $extra_class,
						'tour_min_price' => $_GET['tour_min_price'] ?? '',
						'tour_max_price' => $_GET['tour_max_price'] ?? '',
						'label'          => $field['filter_item_label'],
					);
					if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
						echo $this->travel_html_render_price_mode($field);
					} else {
						travel_get_template('tour-search/fields/price', compact('data'));
					}
					break;
				case 'selected':
					$data = array(
						'extra_class' => $extra_class,
						'label'       => $field['filter_item_label'],
					);
					travel_get_template('tour-search/fields/selection', compact('data'));
					break;
				case 'rating':
					$data = array(
						'extra_class' => $extra_class,
						'tour_rating' => $_GET['tour_rating'] ?? '',
						'label'       => $field['filter_item_label'],
					);
					travel_get_template('tour-search/fields/rating', compact('data'));
					break;
				case 'reset':
					echo $this->travel_html_render_button_reset($field);
					break;
				default:
					echo $this->travel_html_render_button_submit($field);
			}
		}
		$lang = isset($_GET['lang']) ? $_GET['lang'] : '';
		echo '<input type="hidden" name="lang" value="' . $lang . '">';
		echo '</div></form></div>';
	}
	protected function html_render_field_tour_name($settings) {
		$tour_name               = $_GET['name_tour'] ?? '';
		$filter_item_placeholder = $settings['filter_item_placeholder'] ?? 'Search Tour';
		$filter_item_label       = ! empty($settings['filter_item_label']) ? $settings['filter_item_label'] : 'Name';
		echo '<div class="tour-search-field name  elementor-repeater-item-' . $settings['_id'] . '">
			<div class="item-filter-heading">' . esc_html__($filter_item_label, 'travel-booking') . '</div>
			<input type="text" placeholder="' . __($filter_item_placeholder, 'travel-booking') . '" value="' . $tour_name . '" name="name_tour">
		</div>';
	}
	protected function html_get_term_tour_type($settings) {
		$tour_tax_param    = get_query_var('tourtax') ?? '';
		$taxonomy          = 'tour_phys'; // taxonomy slug
		$tour_terms        = get_terms($taxonomy);
		$filter_item_label = ! empty($settings['filter_item_label']) ? $settings['filter_item_label'] : 'Tour types';
		if ($tour_terms) {
			echo '<div class="tour-search-field tour-type  elementor-repeater-item-' . $settings['_id'] . '">
								<div class="item-filter-heading">' . esc_html__($filter_item_label, 'travel-booking') . '</div>';
			echo '<div class="wrapper-content"><ul>';
			foreach ($tour_terms as $term) {
				$checked = (is_array($tour_tax_param) && array_key_exists($taxonomy, $tour_tax_param) && $term->slug == $tour_tax_param[$taxonomy]) ? 'checked' : '';
			?>
				<li>
					<input id="term-<?php echo $term->slug; ?>" name="tourtax[tour_phys]" type="checkbox" value="<?php echo $term->slug; ?>" <?php echo $checked; ?>>
					<label for="term-<?php echo $term->slug; ?>"><?php echo $term->name; ?></label>
				</li>
		<?php
			}
			echo '</ul></div></div>';
		}
	}
	protected function travel_html_render_button_submit($field) {

		?>
		<button class="button button-searh-tour elementor-repeater-item-<?php echo $field['_id']; ?>" type="submit">
			<?php \Elementor\Icons_Manager::render_icon($field['filter_item_button_icon'], array('aria-hidden' => 'true')); ?>
			<?php
			if (! empty($field['filter_item_name_button'])) :
				esc_html_e($field['filter_item_name_button'], 'travel-booking');
			endif;
			?>
		</button>
	<?php
	}
	protected function travel_html_render_price_mode($field) {
		$filter_item_label = ! empty($field['filter_item_label']) ? $field['filter_item_label'] : 'Price';
		$html              = '<div class="tour-search-field price">
            <div class="item-filter-heading">' . $filter_item_label . '</div>
				<div class="wrapper-content">
				<div class="search-price">
					<div class="show-price">
						<span class="min"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>0</bdi></span></span>
						-
						<span class="max"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>500</bdi></span></span>
					</div>
				<div class="range_container">
						<div class="sliders_control">
							<input id="fromSlider" type="range" value="0" min="0" max="500" />
							<input id="toSlider" type="range" value="500" min="0" max="500" />
						</div>
					</div>
				</div>
			</div>
		</div>';
		return $html;
	}
	protected function travel_html_render_button_reset($field) {
	?>
		<button class="button button-reset-searh-tour elementor-repeater-item-<?php echo $field['_id']; ?>" type="submit">
			<?php \Elementor\Icons_Manager::render_icon($field['filter_item_button_icon'], array('aria-hidden' => 'true')); ?>
			<?php
			if (! empty($field['filter_item_name_button'])) :
				esc_html_e($field['filter_item_name_button'], 'travel-booking');
			endif;
			?>
		</button>
	<?php
	}
}
