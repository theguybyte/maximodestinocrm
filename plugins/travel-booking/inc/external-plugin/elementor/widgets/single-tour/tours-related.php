<?php

namespace Elementor;
use Thim_EL_Kit\GroupControlTrait;
class Thim_Ekit_Widget_Tours_Related extends Widget_Base {
	use GroupControlTrait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}
	public function get_name() {
		return 'thim-ekits-tours-related';
	}

	public function get_title() {
		return esc_html__( 'Tours Related', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-product-related';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_help_url() {
		return '';
	}
	public function get_style_depends(): array {
		return array( 'e-swiper' );
	}
	public function get_script_depends(): array {
		return array( 'swiper' );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'section_Setting',
			array(
				'label' => esc_html__( 'Setting', 'travel-booking' ),
			)
		);
		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Skin', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'travel-booking' ),
					'slider'  => esc_html__( 'Slider', 'travel-booking' ),
				),
			)
		);
		$this->add_control(
			'build_loop_item',
			array(
				'label'     => esc_html__( 'Build Loop Item', 'travel-booking' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'     => esc_html__( 'Choose a template', 'travel-booking' ),
				'type'      => Controls_Manager::SELECT2,
				'default'   => '0',
				'options'   => array( '0' => esc_html__( 'None', 'travel-booking' ) ) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'tours' ),
				'condition' => array(
					'build_loop_item' => 'yes',
				),
			)
		);
		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Products Per Page', 'travel-booking' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 20,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'travel-booking' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'min'            => 1,
				'max'            => 6,
				'selectors'      => array(
					'{{WRAPPER}}' => '--grid-template-columns-related-tours: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'travel-booking' ),
					'title'      => esc_html__( 'Title', 'travel-booking' ),
					'price'      => esc_html__( 'Price', 'travel-booking' ),
					'popularity' => esc_html__( 'Popularity', 'travel-booking' ),
					'rating'     => esc_html__( 'Rating', 'travel-booking' ),
					'rand'       => esc_html__( 'Random', 'travel-booking' ),
					'menu_order' => esc_html__( 'Menu Order', 'travel-booking' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'travel-booking' ),
					'desc' => esc_html__( 'DESC', 'travel-booking' ),
				),
			)
		);

		$this->end_controls_section();
		$this->_register_style_layout();
		$this->register_heading_controls();
		$this->_register_settings_slider(
			array(
				'style' => 'slider',
			)
		);

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
		parent::register_controls();
	}
	protected function _register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label'     => esc_html__( 'Layout', 'travel-booking' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-related-tour-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 35,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-related-tour-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	protected function register_heading_controls() {
		$this->start_controls_section(
			'section_style_heading_product',
			array(
				'label' => esc_html__( 'Heading', 'travel-booking' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => esc_html__( 'Heading', 'travel-booking' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Hide', 'travel-booking' ),
				'label_on'     => esc_html__( 'Show', 'travel-booking' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'thim-ekit-single-product__related--show-heading-',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .title-related-tours' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'heading_typography',
				'selector'  => '{{WRAPPER}} .title-related-tours',
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'heading_text_align',
			array(
				'label'     => esc_html__( 'Text Align', 'travel-booking' ),
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
					'{{WRAPPER}} .title-related-tours' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'heading_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .title-related-tours' => 'margin-bottom: {{SIZE}}{{UNIT}};margin-top:0;',
				),
				'condition'  => array(
					'show_heading!' => '',
				),
			)
		);

		$this->end_controls_section();
	}
	public function render() {
		do_action( 'thim-ekit/modules/single-product/before-preview-query' );
		global $wp_query;
		$product = wc_get_product( false );
		if ( ! $product ) {
			return;
		}
		$settings = $this->get_settings_for_display();
		$terms    = get_the_terms( get_the_ID(), 'tour_phys' );

		$term_ids = array();
		foreach ( $terms as $term ) {
			$term_ids[] = $term->term_id;
		}
		if ( empty( $term_ids[0] ) ) {
			return;
		}
		$args        = array(
			'post_type'           => array( 'product' ),
			'wc_query'            => 'tours',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => 4,
			'orderby'             => $settings['orderby'] ?? 'date',
			'order'               => $settings['order'] ?? 'desc',
			'post__not_in'        => array( get_the_ID() ),
			'tax_query'           => array(
				array(
					'taxonomy' => 'tour_phys',
					'field'    => 'term_id',
					'terms'    => $term_ids,
					'operator' => 'IN',
				),
			),
		);
		$class       = 'thim-ekits-tours__related';
		$class_inner = 'thim-ekits-tours__related__inner tours';
		$class_item  = 'thim-ekits-tours__related__item';
		$tours_wp    = new \WP_Query( $args );
		?>
		<div class="thim-ekit-single-tours__related woocommerce">
			<?php
				$heading = apply_filters( 'tours_related_heading', __( 'Similar experiences', 'travel-booking' ) );
			if ( $heading && $settings['show_heading'] == 'yes' ) :
				?>
					<h2 class="title-related-tours"><?php echo esc_html( $heading ); ?></h2>
				<?php
				endif;
			if ( isset( $settings['style'] ) && $settings['style'] == 'slider' ) {
				$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
				$class       .= ' thim-ekits-sliders ' . $swiper_class;
				$class_inner  = 'swiper-wrapper';
				$class_item  .= ' swiper-slide';
				$this->render_nav_pagination_slider( $settings );
			}
			?>
				<div class="<?php echo esc_attr( $class ); ?>">
					<div class="<?php echo esc_attr( $class_inner ); ?>"> 
						<?php
						while ( $tours_wp->have_posts() ) :
							$tours_wp->the_post();
							?>
							<div class="tour <?php echo $class_item; ?>">
								<?php
								if ( ! empty( $settings['template_id'] ) ) {
									\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
								} else {
									tb_get_file_template( 'content-tour.php', false );
								}
								?>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
		</div>
		<?php
		do_action( 'thim-ekit/modules/single-product/after-preview-query' );
	}
}
