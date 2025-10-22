<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Tours_Price extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-tours-price';
	}

	public function get_title() {
		return esc_html__( 'Tours Price', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-product-price';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_help_url() {
		return '';
	}
	protected function register_controls() {
		$this->start_controls_section(
			'general_settings',
			array(
				'label' => esc_html__( 'General Settings', 'travel-booking' ),
			)
		);
		$this->add_control(
			'before_price',
			array(
				'label'       => esc_html__( 'Before Price', 'travel-booking' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'From', 'travel-booking' ),
				'placeholder' => esc_html__( 'Type your text here', 'travel-booking' ),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_tour_before_price_style',
			array(
				'label' => esc_html__( 'Before', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'before_typography',
				'selector' => '{{WRAPPER}} .tour-before-price',
			)
		);
		$this->add_control(
			'before_price_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-before-price' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'space_before',
			array(
				'label'     => esc_html__( 'Space(px)', 'textdomain' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'step'      => 1,
				'default'   => 10,
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .tour-before-price' => 'margin-right: {{VALUE}}px;',
					'body.rtl {{WRAPPER}} .tour-before-price' => 'margin-left: {{VALUE}}px;',
				),
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_tour_price_style',
			array(
				'label' => esc_html__( 'Price', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'text_align',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .price .woocommerce-Price-amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .price .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'sale_heading',
			array(
				'label'     => esc_html__( 'Sale Price', 'travel-booking' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sale_price_typography',
				'selector' => '{{WRAPPER}} .price del .woocommerce-Price-amount',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		do_action( 'thim-ekit/modules/single-tour/before-preview-query' );
		$product = wc_get_product( false );

		if ( ! $product ) {
			return;
		}

		?>
		<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'thim-ekit-single-tour__price price test' ) ); ?>">
			<?php
			if ( ! empty( $settings['before_price'] ) ) {
				echo '<span class="tour-before-price">' . $settings['before_price'] . '</span>';
			}
			echo $product->get_price_html();
			?>
		</p>
		<?php
		do_action( 'thim-ekit/modules/single-tour/after-preview-query' );
	}
}