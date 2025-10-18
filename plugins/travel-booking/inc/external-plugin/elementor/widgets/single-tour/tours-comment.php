<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Thim_Ekit_Widget_Tours_Comment extends Widget_Base {
	public function get_name() {
		return 'thim-ekits-tours-comment';
	}

	public function get_title() {
		return esc_html__( 'Tours Comment', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_keywords() {
		return array(
			'comment',
			'travel',
		);
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'travel-booking' ),
			)
		);
		$this->add_control(
			'show_avatar',
			array(
				'label'        => esc_html__( 'Show Avatar', 'travel-booking' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'travel-booking' ),
				'label_off'    => esc_html__( 'Hide', 'travel-booking' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'thim-ekit-single-single-tours--show-avatar-',
			)
		);
		$this->end_controls_section();
		$this->_register_style_header_sc();
		$this->_register_style_header_popup_sc();
		$this->_register_style_list_cmt_sc();
		$this->register_style_pagination_controls();
	}
	protected function _register_style_header_sc() {
		if ( get_option( 'tour_enable_tour_review' ) === 'yes' ) {
			return;
		}
		$this->start_controls_section(
			'section_style_header',
			array(
				'label' => esc_html__( 'Header', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cmt_header_heading_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Reviews-title,{{WRAPPER}} .comment-reply-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cmt_header__typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} .woocommerce-Reviews-title,{{WRAPPER}} .comment-reply-title',
			)
		);
		$this->add_responsive_control(
			'cmt_header_heading_space',
			array(
				'label'     => esc_html__( 'Space(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => -100,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .woocommerce-Reviews-title,{{WRAPPER}} .comment-reply-title' => 'margin-bottom: {{VALUE}}px;',
				),
			)
		);
		$this->end_controls_section();
	}
	protected function _register_style_header_popup_sc() {
		if ( get_option( 'tour_enable_tour_review' ) === 'no' ) {
			return;
		}
		$this->start_controls_section(
			'section_style_header_popup',
			array(
				'label' => esc_html__( 'Header', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cmt_header_color',
			array(
				'label'     => esc_html__( 'Text Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .review-count,{{WRAPPER}} .rating-label,{{WRAPPER}}  .rating-number' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cmt_text__typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} .review-count,{{WRAPPER}} .rating-label,{{WRAPPER}}  .rating-number',
			)
		);
		$this->add_control(
			'cmt_header_label_heading_popup',
			array(
				'label'     => esc_html__( 'Heading', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'cmt_header_heading_color_popup',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .review-top-section .title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cmt_header__typography_popup',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} .review-top-section .title',
			)
		);
		$this->add_responsive_control(
			'cmt_header_heading_space_popup',
			array(
				'label'     => esc_html__( 'Space(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => -100,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .review-top-section .header' => 'margin-bottom: {{VALUE}}px;',
				),
			)
		);
		$this->add_control(
			'cmt_header_progress_heading',
			array(
				'label'     => esc_html__( 'Progress', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'cmt_header_progress__height',
			array(
				'label'     => esc_html__( 'Height(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .progress' => 'height: {{VALUE}}px;',
				),
			)
		);
		$this->add_control(
			'cmt_header_progress_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .progress' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cmt_header_progress_background_color_active',
			array(
				'label'     => esc_html__( 'Background Color Active', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .progress-bar' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cmt_header_progress_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .progress' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'cmt_header_star_heading',
			array(
				'label'     => esc_html__( 'Star', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'cmt_header_star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .statistic .star-rating span:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'cmt_header_empty_star_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .statistic .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'cmt_header_star_size',
			array(
				'label'     => esc_html__( 'Star Size', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'body {{WRAPPER}} .statistic  .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'cmt_header_total_heading',
			array(
				'label'     => esc_html__( 'Total', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cmt_header_total_typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} .average-rating',
			)
		);
		$this->add_control(
			'cmt_header_total_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .average-rating' => 'color: {{VALUE}};',
				),
			)
		);
		$this->_register_style_button_sc();
		$this->end_controls_section();
	}
	protected function _register_style_button_sc() {
		$this->add_control(
			'list_cmt_btn_heading',
			array(
				'label'     => esc_html__( 'Button', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_cmt_btn_typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a',
			)
		);
		$this->add_responsive_control(
			'list_cmt_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_cmt_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'list_cmt_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'list_cmt_btn_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a',
			)
		);
		$this->add_responsive_control(
			'list_cmt_btn_height',
			array(
				'label'     => esc_html__( 'Height(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 250,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'height: {{VALUE}}px;display: inline-block;',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_style_list_cmt_btn' );

		$this->start_controls_tab(
			'tab_list_cmt_btn_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);
		$this->add_control(
			'list_cmt_btn_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'color: {{VALUE}}',
					'{{WRAPPER}} #tour-add-new-review svg path' => 'stroke: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'list_cmt_btn_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_cmt_btn_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'list_cmt_btn_border_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review,{{WRAPPER}}  .gallery-filter a' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_list_cmt_btn_hover',
			array(
				'label' => esc_html__( 'Hover', 'travel-booking' ),
			)
		);
		$this->add_control(
			'list_cmt_btn_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review:hover ,{{WRAPPER}}  .gallery-filter a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} #tour-add-new-review:hover svg path' => 'stroke: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'list_cmt_btn_hover_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review:hover ,{{WRAPPER}}  .gallery-filter a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'list_cmt_btn_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'list_cmt_btn_border_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} #tour-add-new-review:hover ,{{WRAPPER}}  .gallery-filter a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}
	protected function _register_style_list_cmt_sc() {
		$this->start_controls_section(
			'section_style_list_cmt',
			array(
				'label' => esc_html__( 'Item Comment', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'style_list_cmt_space',
			array(
				'label'     => esc_html__( 'Space(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}}  #reviews li.review' => 'margin-bottom: {{VALUE}}px;margin-top: 0px;',
					'body {{WRAPPER}}  #reviews li.review:last-child' => 'margin-bottom: 0px;',
					'body {{WRAPPER}}  #reviews #comments .commentlist .review .comment_container' => 'margin-bottom: 0px!important;',
				),
			)
		);
		$this->add_control(
			'style_list_cmt_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} .commentlist .review .comment_container' => 'background: {{VALUE}} !important;',
				),
			)
		);
		$this->_resgister_option_general_style_fields( 'style_list_cmt', '.commentlist .review .comment_container' );
		$this->add_control(
			'list_cmt_avatar_heading',
			array(
				'label'     => esc_html__( 'Avatar', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'list_cmt_avatar_spacing_',
			array(
				'label'     => esc_html__( 'Spacing', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'body {{WRAPPER}} .avatar' => 'margin-right:{{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->_resgister_option_image_style_sc( true, 'list_cmt_avatar_', '.commentlist .review .comment_container .avatar' );
		$this->add_control(
			'list_cmt_star_heading',
			array(
				'label'     => esc_html__( 'Star', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->_resgister_option_star_style_sc( 'list_cmt_star_', '.comment-text' );
		$this->add_control(
			'list_cmt_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->_resgister_option_general_style_sc( 'list_cmt_title_', '.tour-review-title' );
		$this->add_control(
			'list_cmt_metadata_heading',
			array(
				'label'     => esc_html__( 'Meta Data', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->_resgister_option_general_style_sc( 'list_cmt_metadata_', '.meta' );
		$this->add_control(
			'list_cmt_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->_resgister_option_general_style_sc( 'list_cmt_content_', '.description p' );
		$this->add_control(
			'list_cmt_image_heading',
			array(
				'label'     => esc_html__( 'Image', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'list_cmt_image_spacing_',
			array(
				'label'     => esc_html__( 'Spacing', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'body {{WRAPPER}} .tour-review-images li' => 'margin-right:{{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->_resgister_option_image_style_sc( false, 'list_cmt_image_', '.tour-review-images img' );
		$this->end_controls_section();
	}
	protected function _resgister_option_image_style_sc( $condition, $label, $class ) {
		if ( $condition ) {
			$this->add_responsive_control(
				$label . '_width',
				array(
					'label'      => esc_html__( 'Width', 'travel-booking' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 5,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'body {{WRAPPER}} ' . $class => 'width: {{SIZE}}{{UNIT}}!important;',
						'body {{WRAPPER}} #reviews #comments .commentlist .review .comment_container .comment-text' => 'width: calc(100% - {{SIZE}}{{UNIT}} - 50px);',
					),
				)
			);
			$this->add_responsive_control(
				$label . '_max_width',
				array(
					'label'      => esc_html__( 'Max Width', 'travel-booking' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 5,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'body {{WRAPPER}} ' . $class => 'max-width: {{SIZE}}{{UNIT}}!important;',
					),
				)
			);
			$this->add_responsive_control(
				$label . '_height',
				array(
					'label'      => esc_html__( 'Height', 'travel-booking' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 5,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'body {{WRAPPER}} ' . $class => 'height: {{SIZE}}{{UNIT}};max-height: {{SIZE}}{{UNIT}}!important;',
					),
				)
			);
		}
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => $label . '_border',
				'selector' => '{{WRAPPER}} ' . $class,
				// 'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$label . '_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}
	protected function _resgister_option_star_style_sc( $label, $class ) {
		$this->add_control(
			$label . '_color',
			array(
				'label'     => esc_html__( 'Star Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} ' . $class . ' .star-rating span:before' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			$label . '_empty_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} ' . $class . ' .star-rating::before' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			$label . '_size',
			array(
				'label'     => esc_html__( 'Star Size', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'em',
				),
				'range'     => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'body {{WRAPPER}} ' . $class . '  .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}
	protected function _resgister_option_general_style_sc( $label, $class ) {
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => $label . '_typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} ' . $class,
			)
		);
		$this->add_control(
			$label . '_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class => 'color: {{VALUE}};',
				),
			)
		);
	}
	protected function _resgister_option_general_style_fields( $label, $class ) {
		$this->add_responsive_control(
			$label . '_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;; ',
				),
			)
		);
		$this->add_responsive_control(
			$label . '_margin',
			array(
				'label'      => esc_html__( 'Margin', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
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
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}
	protected function register_style_pagination_controls() {
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label' => esc_html__( 'Pagination', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'list_cmt_pagination_align',
			array(
				'label'        => esc_html__( 'Alignment', 'travel-booking' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'default'      => 'center',
				'prefix_class' => 'thim-ekit-archive-product--pagination--align--',
				'selectors'    => array(
					'body {{WRAPPER}} .woocommerce-pagination .page-numbers' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_cmt_pagination_gap',
			array(
				'label'          => esc_html__( 'Between item', 'travel-booking' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'em' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
					'em' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}}.thim-ekit-archive-product--pagination--align--left nav.woocommerce-pagination ul li'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.thim-ekit-archive-product--pagination--align--right nav.woocommerce-pagination ul li'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.thim-ekit-archive-product--pagination--align--center nav.woocommerce-pagination ul li' => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				),
			)
		);

		$this->add_control(
			'list_cmt_pagination_spacing',
			array(
				'label'     => esc_html__( 'Space Top(px)', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'body {{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}px',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'list_cmt_pagination_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span',
			)
		);

		$this->add_responsive_control(
			'list_cmt_pagination_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_cmt_pagination_typography',
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
			)
		);

		$this->start_controls_tabs( 'pagination_style_tabs' );

		$this->start_controls_tab(
			'pagination_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);

		$this->add_control(
			'list_cmt_pagination_link_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_cmt_pagination_link_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'travel-booking' ),
			)
		);

		$this->add_control(
			'list_cmt_pagination_link_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_cmt_pagination_link_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'list_cmt_pagination__style_active',
			array(
				'label' => esc_html__( 'Active', 'travel-booking' ),
			)
		);

		$this->add_control(
			'list_cmt_pagination_link_color_active',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'list_cmt_pagination_link_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		do_action( 'thim-ekit/modules/single-tour/before-preview-query' );
		$settings = $this->get_settings_for_display();
		$product  = wc_get_product( false );
		if ( ! $product ) {
			return;
		}
		if ( get_option( 'tour_enable_tour_review' ) === 'yes' ) {
			tb_get_file_template( 'single-tour/product-reviews.php' );
		} else {
			// echo comments_template();
			require_once TOUR_BOOKING_PHYS_PATH . 'inc/external-plugin/elementor/templates/comments.php';
		}
		do_action( 'thim-ekit/modules/single-tour/after-preview-query' );
	}
}
