<?php
namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Search_Selected extends Widget_Base {
	/**
	 * @return string
	 */
	public function get_name() {
		return 'thim-ekits-search-selected';
	}
	/**
	 * @return string
	 */
	public function get_title() {
		return 'Search Selected';
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
		return array( \TravelBooking\Tour_Elementor::TOUR_BOOKING );
	}

	protected function register_controls() {
		$this->_resgister_style_filter_field_selected_list( null );
		$this->_resgister_style_filter_field_button_reset( null );
	}
	protected function _resgister_style_filter_field_button_reset( $conditon ) {
		// if(!isset($conditon) || $conditon == null){
		//  return;
		// }
		$this->start_controls_section(
			'section_filter_field_button_reset_style',
			array(
				'label' => esc_html__( 'Button Clear Filter', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->resgister_option_general_style_filter_field_selected( 'filter_field_button_reset', '.clear', '.no-class' );
		$this->resgister_option_tabs_color_general_filter_selected( 'filter_field_button_reset', '.clear', '.no-class' );
		$this->end_controls_section();
	}
	protected function _resgister_style_filter_field_selected_list( $conditon ) {
		// if(!isset($conditon) || $conditon == null){
		//  return;
		// }
		$this->start_controls_section(
			'section_filter_field_selected_list_style',
			array(
				'label' => esc_html__( 'Selected Lits', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->resgister_option_general_style_filter_field_selected( 'filter_field_selected_list', '.list-item', '.woocommerce-Price-amount' );
		$this->resgister_option_tabs_color_general_filter_selected( 'filter_field_selected_list', '.list-item', '.woocommerce-Price-amount' );
		$this->add_control(
			'filter_field_selected_list_item_remove_heading',
			array(
				'label'     => esc_html__( 'Icon Remove', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		$this->add_responsive_control(
			'filter_field_selected_list_item_remove_width',
			array(
				'label'      => esc_html__( 'Icon size', 'travel-booking' ),
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
					'body {{WRAPPER}} svg.remove' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'body {{WRAPPER}} .remove'    => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'filter_field_selected_list_item_remove_space',
			array(
				'label'      => esc_html__( 'Icon Space', 'travel-booking' ),
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
					'body:not(.rtl) {{WRAPPER}} .remove' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .remove'       => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'filter_field_selected_list_item_remove_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  svg.remove path' => 'stroke: {{VALUE}};',
					'{{WRAPPER}}  .remove'         => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'filter_field_selected_list_item_remove_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .list-item:hover svg.remove path' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .list-item:hover .remove' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
	}
	protected function resgister_option_general_style_filter_field_selected( $label, $class, $class2 ) {
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => $label . '_typography',
				'label'    => esc_html__( 'Typography', 'travel-booking' ),
				'selector' => '{{WRAPPER}} ' . $class . ',{{WRAPPER}} ' . $class2,
			)
		);
		$this->add_responsive_control(
			$label . '_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors'  => array(
					'{{WRAPPER}} ' . $class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'exclude'  => array( 'color' ),
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
	protected function resgister_option_tabs_color_general_filter_selected( $label, $class, $class2 ) {
		$this->start_controls_tabs( 'tabs_' . $label . '_style' );
		$this->start_controls_tab(
			'tab_' . $label . '_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);
		$this->add_control(
			$label . '_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  ' . $class . ',{{WRAPPER}} ' . $class2 => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			$label . '_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  ' . $class => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$label . '_border_color' . $label,
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					$label . '_border_border!' => array( 'none', '' ),
				),
				'selectors' => array(
					'{{WRAPPER}}  ' . $class => 'border-color: {{VALUE}};',
				),
			)
		);
		// $this->add_control(
		//     $label.'_icon_color',
		//     array(
		//         'label'     => esc_html__('Icon Color', 'travel-booking'),
		//         'type'      => Controls_Manager::COLOR,
		//         'default'   => '',
		//         'selectors' => array(
		//             '{{WRAPPER}}  '.$class.' i' => 'color: {{VALUE}};',
		//         ),
		//     )
		// );
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_' . $label . '_hover',
			array(
				'label' => esc_html__( 'Hover', 'travel-booking' ),
			)
		);
		$this->add_control(
			$label . '_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  ' . $class . ':hover,{{WRAPPER}} ' . $class2 . ':hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			$label . '_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$label . '_hover_border_color_',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					$label . '_border_border!' => array( 'none', '' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $class . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->_render_content_template();
	}
	protected function _render_content_template() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<div class="wcbt-product-filter-selection wrapper">
				<ul class="list">
					<li class="list-item" data-field="tour_type" data-value="escorted_tour">
						<span class="title"><?php esc_html_e( 'Escorted Tour', 'travel-booking' ); ?></span>
						<span class="remove">x</span>
					</li>
					<li class="list-item" data-field="rating" data-value="5">
						<span class="title"><?php esc_html_e( 'Five stars', 'travel-booking' ); ?></span>
						<span class="remove">x</span>
					</li>
				</ul>
				<button type="button" class="clear"><?php esc_html_e( 'Clear Filter', 'travel-booking' ); ?></button>
			</div>
			<?php
		} else {
			$data = array( 'true' );
			travel_get_template( 'tour-search/fields/selection', compact( 'data' ) );
		}
	}
}
