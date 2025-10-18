<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
class Thim_Ekit_Widget_Tours_Content extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-tours-content';
	}

	public function get_title() {
		return esc_html__( 'Tours Content', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_tour_content_style',
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
					'{{WRAPPER}} .thim-ekit-single-tour__content' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => esc_html__( 'Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-tour__content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-tour__content',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-tour/before-preview-query' );

		$product = wc_get_product( false );

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		?>

		<div class="thim-ekit-single-tour__content">
			<?php the_content(); ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-tour/after-preview-query' );
	}
}