<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Thim_EL_Kit\GroupControlTrait;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;

class Thim_Ekit_Widget_Tours_Hightlight extends Widget_Base {
	use GroupControlTrait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}
	public function get_name() {
		return 'thim-ekits-tours-hightlight';
	}

	public function get_title() {
		return esc_html__( 'Tours Hightlight', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-carousel';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}
	public function get_style_depends(): array {
		return array( 'e-swiper' );
	}
	public function get_script_depends(): array {
		return array( 'swiper' );
	}
	public function get_keywords() {
		return array(
			'hightlight',
			'travel',
		);
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}
	protected function register_controls() {
		$this->_register_settings_slider();
		$this->_register_style_item_slide();
		$this->_register_setting_slider_dot_style(
			array(
				'slider_show_pagination!' => 'none',
			)
		);
		$this->_register_setting_slider_nav_style(
			array(
				'slider_show_arrow' => 'yes',
			)
		);
	}
	protected function _register_style_item_slide() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Item', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'image_heading',
			array(
				'label'     => esc_html__( 'Image', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image_size',
				'include' => array(),
				'default' => 'woocommerce_single',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .thim-ekits-tours-hightlight__item img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-tours-hightlight__item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'image_slider_spacing',
			array(
				'label'       => esc_html__( 'Spacing (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => '10',
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-tours-hightlight__item img' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);
		$this->add_control(
			'label_heading',
			array(
				'label'     => esc_html__( 'Label', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'alignment',
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
					'{{WRAPPER}} .thim-ekits-tours-hightlight__item' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-tours-hightlight__item h6' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-tours-hightlight__item h6',
			)
		);
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		global $post;
		$fields = get_post_meta( get_the_ID(), 'phys_tour_hightlight_options', true );
		$fields = json_decode( $fields, true );
		if ( isset( $fields ) && is_array( $fields ) ) :
			$class        = 'thim-ekits-tours-hightlight';
			$class_inner  = 'thim-ekits-tours-hightlight__inner';
			$class_item   = 'thim-ekits-tours-hightlight__item';
			$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
			$class       .= ' thim-ekits-sliders ' . $swiper_class;
			$class_inner  = 'swiper-wrapper';
			$class_item  .= ' swiper-slide';
			$this->render_nav_pagination_slider( $settings );

			?>
			<div class="single-tour-hightlight <?php echo $class; ?>">
				<div class="<?php echo $class_inner; ?>">
					<?php
					foreach ( $fields as $field ) {
						?>
						<div class="<?php echo $class_item; ?>"> 
							<?php
							if ( ! empty( $field['image_hightlight'] ) ) {
								$image_size = $settings['image_size_size'] ?? 'full';
								if ( $image_size == 'custom' ) {
									$image_size = $settings['image_size_custom_dimension'];
									$image_size = array( $image_size['width'], $image_size['height'] );
								}
								echo wp_get_attachment_image( $field['image_hightlight'], $image_size );
							}
							if ( ! empty( $field['label_hightlight'] ) ) {
								echo sprintf( '<h6>%s</h6>', esc_html__( $field['label_hightlight'], 'travel-booking' ) );
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
	endif;
	}
}
