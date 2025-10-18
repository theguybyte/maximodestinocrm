<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Thim_EL_Kit\GroupControlTrait;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;

class Thim_Ekit_Widget_Attributes extends Widget_Base {
	use GroupControlTrait;

	public function get_name() {
		return 'thim-ekits-attributes';
	}

	public function get_title() {
		return esc_html__( 'Tours Attributes', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::TOUR_BOOKING );
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}
	public function get_style_depends(): array {
		return array( 'e-swiper' );
	}
	public function get_script_depends(): array {
		return array( 'swiper' );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'travel-booking' ),
			)
		);
		$this->add_control(
			'attributes_woo',
			array(
				'label'       => esc_html__( 'Select Attribute', 'travel-booking' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_attributes_woo(),
			)
		);
		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Style', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'list'   => esc_html__( 'List', 'travel-booking' ),
					'slider' => esc_html__( 'Slider', 'travel-booking' ),

				),
				'default' => 'list',
			)
		);
		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Limit', 'travel-booking' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'step'    => 1,
				'default' => 6,
			)
		);
		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'default'   => 3,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-list-attr-columns: repeat({{VALUE}}, 1fr)',
				),
				'condition' => array(
					'style' => 'list',
				),
			)
		);
		$this->register_content_image_thumbnail();
		$this->end_controls_section();
		$this->register_search_form_options();
		$this->register_pagination_options();
		$this->register_style_item_controls();
		// $this->_register_control_slider_options();
		$this->_register_settings_slider(
			array(
				'style' => 'slider',
			)
		);
		$this->_register_style_inner_thumbnail();
		$this->_register_style_content();

		$this->_register_setting_slider_dot_style(
			array(
				'style'                   => 'slider',
				'slider_show_pagination!' => 'none',
			)
		);

		$this->_register_setting_slider_nav_style(
			array(
				'style'             => 'slider',
				'slider_show_arrow' => 'yes',
			)
		);
		$this->_register_style_pagination();
		// $this->_register_setting_slider_nav_style();
		// $this->_register_setting_slider_dot_style();
	}

	protected function register_search_form_options() {
		$this->start_controls_section(
			'section_search_form',
			array(
				'label'     => esc_html__( 'Search Form', 'travel-booking' ),
				'condition' => array(
					'style' => 'list',
				),
			)
		);
		$this->add_control(
			'show_search',
			array(
				'label'     => esc_html__( 'Show Search', 'travel-booking' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'placeholder',
			array(
				'label'     => esc_html__( 'Placeholder', 'travel-booking' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Search country or city',
				'condition' => array(
					'show_search' => 'yes',
				),
			)
		);
		$this->add_control(
			'selected_icon',
			array(
				'label'            => esc_html__( 'Icon', 'travel-booking' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'render_type'      => 'template',
				'skin_settings'    => array(
					'inline' => array(
						'none' => array(
							'label' => 'Default',
							'icon'  => 'eicon-close',
						),
						'icon' => array(
							'icon' => 'eicon-star',
						),
					),
				),
				'condition'        => array(
					'show_search' => 'yes',
				),
			)
		);
		$this->add_control(
			'show_sortby',
			array(
				'label'     => esc_html__( 'Show sort by', 'travel-booking' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);
		$this->end_controls_section();
	}

	protected function register_pagination_options() {
		$this->start_controls_section(
			'section_attr_pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'travel-booking' ),
				'condition' => array(
					'style' => 'list',
				),
			)
		);

		$this->add_control(
			'attr_pagination_type',
			array(
				'label'              => esc_html__( 'Pagination', 'travel-booking' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => array(
					''        => esc_html__( 'None', 'travel-booking' ),
					'numbers' => esc_html__( 'Numbers', 'travel-booking' ),
				),
				'frontend_available' => true,
			)
		);

		// $this->add_control(
		//     'attr_pagination_page_limit',
		//     array(
		//         'label'     => esc_html__('Page Limit', 'travel-booking'),
		//         'type' => \Elementor\Controls_Manager::NUMBER,
		//         'min' => 0,
		//         'max' => 100,
		//         'step' => 1,
		//         'default' => 5,
		//         'condition' => [
		//             'attr_pagination_type' => 'numbers',
		//         ],
		//     )
		// );
		$this->add_control(
			'pagination_align',
			array(
				'label'     => __( 'Alignment', 'travel-booking' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'travel-booking' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'travel-booking' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'travel-booking' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-archive-post__pagination' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'attr_pagination_type' => 'numbers',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_image_thumbnail() {
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail_size',
				'default'   => 'full',
				'condition' => array(
					'style!' => 'default',
				),
			)
		);
		$meta_thumbnail = new \Elementor\Repeater();
		$meta_thumbnail->add_control(
			'meta_data_img',
			array(
				'label'   => esc_html__( 'Select Item', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array(
					'title'   => esc_html__( 'Title', 'travel-booking' ),
					'count'   => esc_html__( 'Count', 'travel-booking' ),
					'content' => esc_html__( 'Content', 'travel-booking' ),
					'button'  => esc_html__( 'Button', 'travel-booking' ),
				),
			)
		);
		$meta_thumbnail->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'travel-booking' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h3',
				'condition' => array(
					'meta_data_img' => 'title',
				),
			)
		);

		$meta_thumbnail->add_control(
			'excerpt_lenght',
			array(
				'label'     => esc_html__( 'Excerpt Lenght', 'travel-booking' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 25,
				'condition' => array(
					'meta_data_img' => 'content',
				),
			)
		);
		$meta_thumbnail->add_control(
			'text_count',
			array(
				'label'     => esc_html__( 'Label', 'travel-booking' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Tours',
				'condition' => array(
					'meta_data_img' => 'count',
				),
			)
		);
		$meta_thumbnail->add_control(
			'excerpt_more',
			array(
				'label'     => esc_html__( 'Excerpt More', 'travel-booking' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '...',
				'condition' => array(
					'meta_data_img' => 'content',
				),
			)
		);
		$meta_thumbnail->add_control(
			'bt_name_content',
			array(
				'label'     => esc_html__( 'Name Button', 'travel-booking' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'See all tours',
				'condition' => array(
					'meta_data_img' => 'button',
				),
			)
		);
		$meta_thumbnail->add_control(
			'list_thumbnail_position',
			array(
				'label'              => esc_html__( 'Position', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => array(
					''         => esc_html__( 'Default', 'elementor' ),
					'absolute' => esc_html__( 'Absolute', 'elementor' ),
				),
				'frontend_available' => true,
			)
		);
		$meta_thumbnail->add_control(
			'always_show',
			array(
				'label'     => esc_html__( 'Show & Hidden when hover', 'travel-booking' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				// 'separator' => 'before',
				'condition' => array(
					'list_thumbnail_position' => 'absolute',
				),
			)
		);
		$this->add_control(
			'repeater_meta_inner',
			array(
				'label'       => esc_html__( 'Data Item', 'travel-booking' ),
				'label_block' => true,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $meta_thumbnail->get_controls(),
				'default'     => array(
					array(
						'meta_data_img' => 'title',
					),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ meta_data_img.replace("_", " ") }}}</span>',
				'separator'   => 'after',
			)
		);
	}

	protected function register_style_item_controls() {
		$this->start_controls_section(
			'section_style_item',
			array(
				'label' => esc_html__( 'Item', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-list-attr-column-gap: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'style' => 'list',
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-list-attr-row-gap: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'style' => 'list',
				),
			)
		);
		$this->add_control(
			'itembg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} ..tours_type_item' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors'  => array(
					'{{WRAPPER}} .tours_type_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .tours_type_item',
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tours_type_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'selector' => 'body {{WRAPPER}} .tours_type_item',
			)
		);
		$this->end_controls_section();
	}

	protected function _register_style_content() {
		$this->start_controls_section(
			'asection_style_content',
			array(
				'label' => esc_html__( 'Content', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				// 'condition' => array(
				//     'show_content' => 'yes',
				// ),
			)
		);
		$this->add_responsive_control(
			'list_align_content',
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
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .attr-content-item' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'list_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .attr-content-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Title', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .item__title a' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .item__title  a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => 'body {{WRAPPER}} .item__title ',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'body {{WRAPPER}} .item__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'heading_excerpt_style',
			array(
				'label'     => esc_html__( 'Excerpt', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .item-attr-des' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .item-attr-des',
			)
		);

		$this->add_control(
			'excerpt_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .item-attr-des' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'heading_count_style',
			array(
				'label'     => esc_html__( 'Count', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .count-attr ' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .count-attr',
			)
		);

		$this->add_control(
			'count_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .count-attr' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'heading_button_style',
			array(
				'label'     => esc_html__( 'Button', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors'  => array(
					' {{WRAPPER}} .content-item .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .content-item .btn',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .content-item .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => esc_html__( 'Width', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 250,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'body {{WRAPPER}} .content-item .btn' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body {{WRAPPER}} .content-item .btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .content-item .btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .content-item .btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_border_border!' => array( 'none', '' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .content-item .btn' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .content-item .btn',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'travel-booking' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .content-item .btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .content-item .btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_border_border!' => array( 'none', '' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .content-item .btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function _register_style_inner_thumbnail() {
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => esc_html__( 'Image', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'heading_image_style',
			array(
				'label'     => esc_html__( 'Image', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => 'body {{WRAPPER}} .list-attri-thumbnail img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'body {{WRAPPER}} .list-attri-thumbnail img,body {{WRAPPER}} .list-attri-thumbnail.overlay .tours-type__item__image::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow: hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => 'body {{WRAPPER}} .list-attri-thumbnail img',
			)
		);
		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'body {{WRAPPER}} .list-attri-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}; position: relative;',
				),
			)
		);
		$this->add_control(
			'heading_overlay_style',
			array(
				'label'     => esc_html__( 'Overlay', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'bg_overlay',
				'types'    => array( 'gradient', 'classic' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .tours-type__item__image::before',
			)
		);

		// $this->control_gradient_style();
		$this->add_control(
			'heading_position_style',
			array(
				'label'     => esc_html__( 'Position Item', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'attr_item_position',
			array(
				'label'                => esc_html__( 'Position Vertical', 'thim-elementor-kit' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'top',
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => ' eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				// 'condition'   => array(
				//     'attr-popover-toggle' => 'yes',
				// ),
				'render_type'          => 'ui',
				'selectors'            => array(
					'{{WRAPPER}} .content-item' => '{{VALUE}}',

				),
				'selectors_dictionary' => array(
					'top'    => 'top: 30px;',
					'center' => 'top: 50%;transform: translateY(-50%);',
					'bottom' => 'bottom: 10px; top:auto;',
				),
			)
		);
		$this->add_control(
			'attr_item_align_x',
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
				'default'   => 'flex-start',
				'toggle'    => true,
				'condition' => array(
					// 'attr-popover-toggle' => 'yes',
					'style' => 'slider',
				),
				'selectors' => array(
					'{{WRAPPER}}  .content-item ' => 'text-align: {{VALUE}};padding: 0 20px;transform: translateY(0%);',
				),
				// 'selectors_dictionary' => [
				//     'left' => 'left:0px;',
				//     'center' => 'left:50%; transform: translateX(-50%);',
				//     'right' => 'right:0px; left: auto;',
				// ],

			)
		);
		$this->add_control(
			'attr_item_align_s',
			array(
				'label'                => esc_html__( 'Alignment', 'travel-booking' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
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
				'default'              => 'flex-start',
				'toggle'               => true,
				'condition'            => array(
					// 'attr-popover-toggle' => 'yes',
					'style' => array( 'list', 'default' ),
				),
				'selectors'            => array(
					'{{WRAPPER}}  .content-item ' => ' {{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'   => 'left:10px;rigt:auto;text-align:left;',
					'center' => 'left:50%; transform: translateX(-50%);text-align:center;',
					'right'  => 'right:10px; left: auto;text-align:right;',
				),

			)
		);
		$this->add_control(
			'attr_custom_ofset',
			array(
				'label'     => esc_html__( 'Custom offset', 'travel-booking' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'travel-booking' ),
				'label_off' => esc_html__( 'No', 'travel-booking' ),
				'default'   => 'no',
			)
		);
		$this->add_responsive_control(
			'attr_offset_x_end',
			array(
				'label'      => esc_html__( 'Horizontal Orientation', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 0.1,
					),
					'%'  => array(
						'min' => - 200,
						'max' => 200,
					),
					'vw' => array(
						'min' => - 200,
						'max' => 200,
					),
					'vh' => array(
						'min' => - 200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}}   .content-item' => 'right: {{SIZE}}{{UNIT}};left:auto',
					'body.rtl {{WRAPPER}} .content-item' => 'left: {{SIZE}}{{UNIT}};right:auto;',
				),
				'condition'  => array(
					'attr_custom_ofset' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'attr_offset_y_end',
			array(
				'label'      => esc_html__( 'Vertical Orientation', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => - 200,
						'max' => 200,
					),
					'vh' => array(
						'min' => - 200,
						'max' => 200,
					),
					'vw' => array(
						'min' => - 200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					'{{WRAPPER}}  .content-item' => 'bottom: {{SIZE}}{{UNIT}};top: auto;',
				),
				'condition'  => array(
					'attr_custom_ofset' => 'yes',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _register_style_pagination() {
		$this->start_controls_section(
			'section_style_pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'travel-booking' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style' => 'list',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .page-numbers',
			)
		);

		$this->add_control(
			'pagination_color_heading',
			array(
				'label'     => esc_html__( 'Colors', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'pagination_colors' );

		$this->start_controls_tab(
			'pagination_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr .page-numbers:not(.dots)' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_bg',
			array(
				'label'     => esc_html__( 'Background', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr .page-numbers:not(.dots)' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'travel-booking' ),
			)
		);

		$this->add_control(
			'pagination_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr .page-numbers:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_hover_bg',
			array(
				'label'     => esc_html__( 'Background', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr .page-numbers:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_active',
			array(
				'label' => esc_html__( 'Active', 'travel-booking' ),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr .page-numbers.current' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_active_bg',
			array(
				'label'     => esc_html__( 'Background', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr .page-numbers.current' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_responsive_control(
			'pagination_spacing_top',
			array(
				'label'     => esc_html__( 'Space Top(px)', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pagination-archiver-attr' => 'margin-top:{{SIZE}}px;',
				),
			)
		);
		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'     => esc_html__( 'Space Between', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .pagination-archiver-attr .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .pagination-archiver-attr .page-numbers:not(:last-child)'  => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pagination-archiver-attr .page-numbers:not(:first-child)'       => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pagination-archiver-attr .page-numbers:not(:last-child)'        => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$attr_arr = array();
		if ( $settings['attributes_woo'] ) {
			$attr_arr = $settings['attributes_woo'];
		}
		$data        = $html = '';
		$class_style = 'pain';

		// check attributes remove
		$taxonomies = get_object_taxonomies( 'product', 'objects' );
		if ( empty( $taxonomies ) ) {
			return '';
		}
		$flag = false;
		foreach ( $taxonomies as $tax ) {
			$tax_name = $tax->name;
			if ( 0 !== strpos( $tax_name, 'pa_' ) ) {
				continue;
			}
			if ( is_array( $attr_arr ) && in_array( $tax_name, $attr_arr ) ) {
				$flag = true;
			}
		}
		//end check attributes remove
		if ( is_array( $attr_arr ) && count( $attr_arr ) > 0 && $flag == true ) {
			$class       = 'thim-ekits-tour__attributes';
			$class_inner = 'thim-ekits--tour__attributes__inner';
			$class_item  = 'thim-ekits--tour__attributes__item';
			if ( $settings['style'] == 'list' ) {
				$this->render_form_search_html( $settings, $attr_arr );
			}
			if ( isset( $settings['style'] ) && $settings['style'] == 'slider' ) {
				$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
				$class       .= ' thim-ekits-sliders ' . $swiper_class;
				$class_inner  = 'swiper-wrapper';
				$class_item  .= ' swiper-slide';
				$this->render_nav_pagination_slider( $settings );
			}
			echo '<div class="' . $class . '" >
					<div class="attributes-type-' . $class_style . ' ' . $class_inner . '">';
			if ( $settings['style'] == 'list' ) {
				$page             = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$per_page         = ! empty( $settings['limit'] ) ? $settings['limit'] : '5';
				$offset           = ( $page - 1 ) * $per_page;
				$term_args        = array(
					'taxonomy'   => $attr_arr,
					'hide_empty' => false,
					'number'     => $per_page,
					'offset'     => $offset,
				);
				$number_of_series = count(
					get_terms(
						array(
							'taxonomy'   => $attr_arr,
							'hide_empty' => false,
						)
					)
				);
			} else {
				$term_args = array(
					'taxonomy'   => $attr_arr,
					'hide_empty' => false,
				);
			}
			if ( isset( $_GET['sortby'] ) ) {
				if ( $_GET['sortby'] == 'ASC' || $_GET['sortby'] == 'DESC' ) {
					$term_args['order'] = $_GET['sortby'];
				}
				if ( $_GET['sortby'] == 'popularity' ) {
					$term_args['order']      = 'DESC';
					$term_args['orderby']    = 'count';
					$term_args['hide_empty'] = 0;
				}
			}
			if ( is_product_taxonomy() ) {
				$term            = get_queried_object();
				$pattern         = '/^pa_/i';
				$check_attribute = preg_match( $pattern, $term->taxonomy );
				if ( $check_attribute && ! empty( get_queried_object()->term_id ) ) {
					$term_args['exclude'] = array( get_queried_object()->term_id );
				}
			}
			$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
			// $terms_off_attr = get_terms($attr, $term_args);
			$terms_off_attr = get_terms( $term_args );
			$i              = 1;
			$class          = '';
			foreach ( $terms_off_attr as $key => $term ) {
				if ( $term ) {
					echo '<div class="tours_type_item ' . $class_item . '">';
					$this->render_image_list_attri( $settings, $term, $i );
					echo '</div> ';
				}
				++$i;
			}
			echo '</div>';
			if ( $settings['attr_pagination_type'] != '' && $settings['style'] == 'list' ) {
				$big = 999999999;
				echo '<div class="pagination-archiver-attr">';
				echo paginate_links(
					array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $paged,
						'prev_text' => __(
							'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M15 19.5L7.5 12L15 4.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>'
						),
						'next_text' => __(
							'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M9 4.5L16.5 12L9 19.5" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>'
						),
						'total'     => ceil( $number_of_series / $per_page ), // like 10 items per page
					)
				);
				echo '</div> ';
			}
			echo '</div>';
		}
	}

	protected function render_image_list_attri( $settings, $term, $i ) {
		$attributes_html = ( isset( $settings['open_new_tab'] ) && $settings['open_new_tab'] == 'yes' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		$link_image      = get_tax_meta( $term->term_id, 'phys_tour_type_thumb', true ) ? get_tax_meta( $term->term_id, 'phys_tour_type_thumb', true ) : '';
		$text_color      = get_tax_meta( $term->term_id, 'phys_text_color', true ) ? get_tax_meta( $term->term_id, 'phys_text_color', true ) : '';
		$custom_link     = get_tax_meta( $term->term_id, 'phys_custom_link', true ) ? get_tax_meta( $term->term_id, 'phys_custom_link', true ) : get_term_link( $term->term_id, $term->taxonomy );
		$css             = $text_color ? ' style="color:' . $text_color . '"' : '';
		if ( $settings['thumbnail_size_size'] == 'custom' ) {
			$size_iamge = array( $settings['thumbnail_size_custom_dimension']['width'], $settings['thumbnail_size_custom_dimension']['height'] );
		} else {
			$size_iamge = $settings['thumbnail_size_size'];
		}
		$thumnail_html = $thumnail_html_hover = '';
		$content_html  = '';
		if ( is_array( $settings['repeater_meta_inner'] ) ) {
			foreach ( $settings['repeater_meta_inner'] as $item ) {
				switch ( $item['meta_data_img'] ) {
					case 'title':
						if ( $item['list_thumbnail_position'] == 'absolute' ) {
							if ( $item['always_show'] == 'yes' ) {
								$thumnail_html_hover .= archive_render_atr_title( $item, $term, $custom_link );
							} else {
								$thumnail_html .= archive_render_atr_title( $item, $term, $custom_link );
							}
						} else {
							$content_html .= archive_render_atr_title( $item, $term, $custom_link );
						}
						break;
					case 'content':
						if ( $item['list_thumbnail_position'] == 'absolute' ) {
							if ( $item['always_show'] == 'yes' ) {
								$thumnail_html_hover .= archive_render_atr_content( $item, $term );
							} else {
								$thumnail_html .= archive_render_atr_content( $item, $term );
							}
						} else {
							$content_html .= archive_render_atr_content( $item, $term );
						}
						break;
					case 'count':
						if ( $item['list_thumbnail_position'] == 'absolute' ) {
							if ( $item['always_show'] == 'yes' ) {
								$thumnail_html_hover .= archive_render_atr_count( $item, $term );
							} else {
								$thumnail_html .= archive_render_atr_count( $item, $term );
							}
						} else {
							$content_html .= archive_render_atr_count( $item, $term );
						}
						break;
					case 'button':
						if ( $item['list_thumbnail_position'] == 'absolute' ) {
							if ( $item['always_show'] == 'yes' ) {
								$thumnail_html_hover .= archive_render_atr_button( $item, $custom_link );
							} else {
								$thumnail_html .= archive_render_atr_button( $item, $custom_link );
							}
						} else {
							$content_html .= archive_render_atr_button( $item, $custom_link );
						}
						break;
				}
			}
		}
		if ( ! empty( $link_image ) && $link_image['id'] ) :

			?>
			<div class="list-attri-thumbnail overlay">
				<a href="<?php echo esc_url( $custom_link ); ?>" title="<?php echo $term->name; ?>"
					class="tours-type__item__image" <?php echo $attributes_html; ?>>
					<?php
					echo wp_get_attachment_image(
						$link_image['id'],
						$size_iamge,
						false,
						array(
							'alt' => $term->name,
						)
					);
					?>
				</a>
				<?php
				if ( $thumnail_html != '' || $thumnail_html_hover != '' ) :
					?>
					<div class="content-item">
						<?php echo $thumnail_html; ?>
						<?php if ( $thumnail_html_hover != '' ) : ?>
							<div class="content-item-hover">
								<?php echo $thumnail_html_hover; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $content_html != '' ) : ?>
			<div class="attr-content-item">
				<?php echo $content_html; ?>
			</div>
		<?php endif; ?>
			<?php
		endif;
	}

	protected function get_attributes_woo() {
		$taxonomies    = get_object_taxonomies( 'product', 'objects' );
		$attribute_arr = array();
		// $attribute_arr[] = 'Select Attribute';
		if ( empty( $taxonomies ) ) {
			return '';
		}

		foreach ( $taxonomies as $tax ) {
			$tax_name = $tax->name;
			if ( 0 !== strpos( $tax_name, 'pa_' ) ) {
				continue;
			}
			if ( ! in_array( $tax_name, $attribute_arr ) ) {
				$attribute_arr[ $tax_name ] = $tax_name;
			}
		}

		return $attribute_arr;
	}

	protected function render_form_search_html( $settings, $attr_arr ) {
		$placeholder = ! empty( $settings['placeholder'] ) ? 'placeholder="' . $settings['placeholder'] . '"' : '';
		if ( ( $settings['show_search'] == 'yes' ) || ( $settings['show_sortby'] == 'yes' ) ) :
			?>
			<div class="arrt-search-form-wrapper">
				<?php if ( $settings['show_search'] == 'yes' ) : ?>
					<div class="arrt-search-form">
						<form action="#" method="get" class="search-attributes"
								data-attr='<?php echo json_encode( $attr_arr ); ?>'
								data-layout='<?php echo json_encode( $settings['repeater_meta_inner'] ); ?>'>
							<button type="submit" class="button-attributes">
								<?php
								if ( ! empty( $settings['selected_icon']['value'] ) ) :
									\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
								endif;
								?>
							</button>
							<input type="text" name="attri_search" <?php echo $placeholder; ?>>
						</form>
					</div>
				<?php endif; ?>
				<?php if ( $settings['show_sortby'] == 'yes' ) : ?>
					<div class="arrt-sortby">
						<h4><?php esc_html_e( 'Sort by', 'travel-booking' ); ?></h4>
						<form class="tour-ordering-attributes" method="get" action="#">
							<select name="sort_attributes" class="sort_attributes">
								<option
									value="ASC" 
									<?php
									if ( isset( $_GET['sortby'] ) && $_GET['sortby'] == 'ASC' ) {
										echo 'selected';}
									?>
									><?php esc_html_e( 'Ascending', 'travel-booking' ); ?></option>
								<option
									value="DESC" 
									<?php
									if ( isset( $_GET['sortby'] ) && $_GET['sortby'] == 'DESC' ) {
										echo 'selected';}
									?>
									><?php esc_html_e( 'Descending', 'travel-booking' ); ?></option>
								<option
									value="popularity" 
									<?php
									if ( isset( $_GET['sortby'] ) && $_GET['sortby'] == 'popularity' ) {
										echo 'selected';}
									?>
									><?php esc_html_e( 'Popularity', 'travel-booking' ); ?></option>
							</select>
						</form>
					</div>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}
}
