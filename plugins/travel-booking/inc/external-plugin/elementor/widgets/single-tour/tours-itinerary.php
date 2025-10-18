<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Tours_Itinerary extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-tours-itinerary';
	}

	public function get_title() {
		return esc_html__( 'Tours Itinerary', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_keywords() {
		return array(
			'interary',
			'travel',
		);
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'section_product_content_style',
			array(
				'label' => esc_html__( 'Style', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'travel-booking' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'travel-booking' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'travel-booking' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'travel-booking' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'travel-booking' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tours-interary-items' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tours-interary-items' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .tours-interary-items',
			)
		);

		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		global $post;
		$tab_field_itinerary = get_post_meta( $post->ID, '_tour_content_itinerary', true );
		if ( empty( $tab_field_itinerary ) ) {
			return;
		}
		echo '<div class="tours-interary-items">';
		echo apply_filters( 'the_content', wpautop( $tab_field_itinerary ) );
		echo '</div>';
	}
}
