<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! class_exists( '\Elementor\Thim_Ekit_Widget_Product_Image' ) ) {
	include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/single-product/product-image.php';
}

class Thim_Ekit_Widget_Tours_Image extends Thim_Ekit_Widget_Product_Image {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-tours-image';
	}

	public function get_title() {
		return esc_html__( 'Tours Image', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-product-images';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_help_url() {
		return '';
	}
	protected function register_controls() {

		$this->start_controls_section(
			'section_tour_layout_style',
			array(
				'label' => esc_html__( 'General', 'travel-booking' ),
			)
		);
		$this->add_control(
			'thumb_style',
			array(
				'label'   => esc_html__( 'Thumbnails Setting', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slides',
				'options' => array(
					'slides'  => esc_html__( 'Sliders', 'travel-booking' ),
					'columns' => esc_html__( 'Columns', 'travel-booking' ),
				),
			)
		);
		$this->add_control(
			'slides_options',
			array(
				'label'     => esc_html__( 'Slider Setting', 'travel-booking' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => esc_html__( 'Horizontal', 'travel-booking' ),
					'vertical'   => esc_html__( 'Vertical', 'travel-booking' ),
					//                  'carousel'       => esc_html__( 'Carousel', 'travel-booking' ),
				),
				'condition' => array(
					'thumb_style' => 'slides',
				),
			)
		);
		$this->add_responsive_control(
			'columns_options',
			array(
				'label'     => esc_html__( 'Select Columns', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 5,
				'step'      => 1,
				'default'   => 3,
				'selectors' => array(
					'{{WRAPPER}}' => '--ekits-tour-image-column: {{VALUE}}',
				),
				'condition' => array(
					'thumb_style'    => array( 'columns', 'slides', 'gallery' ),
					'slides_options' => 'horizontal',
				),
			)
		);
		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 24,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-gallery-column-gap: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'thumb_style' => 'columns',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 15,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-gallery-row-gap: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'thumb_style' => 'columns',
				),
			)
		);
		$this->add_control(
			'lightbox',
			array(
				'label'        => esc_html__( 'Show More Button', 'travel-booking' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'travel-booking' ),
				'label_off'    => esc_html__( 'Hide', 'travel-booking' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'thumb_style' => 'columns',
				),
			)
		);
		$this->add_responsive_control(
			'number_to_show',
			array(
				'label'     => esc_html__( 'Item to show', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 12,
				'step'      => 1,
				'default'   => 2,
				'condition' => array(
					'lightbox'    => 'yes',
					'thumb_style' => 'columns',
				),
			)
		);
		$this->add_control(
			'bt_show_title',
			array(
				'label'     => esc_html__( 'Title', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Gallery', 'travel-booking' ),
				'condition' => array(
					'lightbox'    => 'yes',
					'thumb_style' => 'columns',
				),
			)
		);
		$this->add_control(
			'bt_show_icon',
			array(
				'label'       => esc_html__( 'Icon', 'travel-booking' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'skin'        => 'inline',
				'show_label'  => true,
				'label_block' => true,
				'condition'   => array(
					'lightbox'    => 'yes',
					'thumb_style' => 'columns',
				),
			)
		);
		$this->add_responsive_control(
			'thumbnail_v_width',
			array(
				'label'      => esc_html__( 'Thumbnail Width', 'travel-booking' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 120,
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--ekits-tour-thumbnail-vertical-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'thumb_style'    => 'slides',
					'slides_options' => 'vertical',
				),
			)
		);
		$this->add_control(
			'thumbnail_v_pos',
			array(
				'label'        => esc_html__( 'Thumbnail Position', 'travel-booking' ),
				'type'         => Controls_Manager::CHOOSE,
				'toggle'       => false,
				'default'      => 'row-reverse',
				'options'      => array(
					'row-reverse' => array(
						'title' => esc_html__( 'Left', 'travel-booking' ),
						'icon'  => 'eicon-h-align-left',
					),
					'row'         => array(
						'title' => esc_html__( 'Right', 'travel-booking' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type'  => 'ui',
				'selectors'    => array(
					'{{WRAPPER}} .ekits-product-slides__vertical' => 'flex-direction: {{VALUE}}',
				),
				'prefix_class' => 'thim-ekits-tours-slides__vertica-',
				'condition'    => array(
					'thumb_style'    => 'slides',
					'slides_options' => 'vertical',
				),
			)
		);
		$this->add_control(
			'thumbnail_v_pos_fixed',
			array(
				'label'        => esc_html__( 'Position fixed', 'travel-booking' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'travel-booking' ),
				'label_off'    => esc_html__( 'No', 'travel-booking' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'thim-ekits-tours__thumbnail-position-fixed-',
				'condition'    => array(
					'thumb_style'    => 'slides',
					'slides_options' => 'vertical',
				),
			)
		);

		$this->end_controls_section();

		parent::_register_setting_thumb_slider_nav_style(
			esc_html__( 'Nav Feature', 'travel-booking' ),
			'feature',
			'.ekits-tour-slides__wrapper'
		);

		parent::_register_setting_thumb_slider_nav_style(
			esc_html__( 'Nav Thumbnail', 'travel-booking' ),
			'thumbnail',
			'.ekits-tour-thumbnails__wrapper'
		);

		parent::_register_style_image();
		parent::_register_style_thumbnail();
		$this->_register_style_gallery_button_sc();
	}
	protected function _register_style_gallery_button_sc() {
		$this->start_controls_section(
			'_section_button_style',
			array(
				'label'     => esc_html__( 'Show More', 'travel-booking' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'lightbox'       => 'yes',
					'columns_layout' => 'grid',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => '_gallery_button_typography',
				'selector' => '{{WRAPPER}} .more-photos-button',
			)
		);
		$this->add_responsive_control(
			'_gallery_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),

				'selectors'  => array(
					'{{WRAPPER}} .more-photos-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => '_gallery__button_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .more-photos-button',
			)
		);

		$this->add_responsive_control(
			'_gallery_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .more-photos-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'_gallery_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		$this->add_responsive_control(
			'_gallery_icon_title_width',
			array(
				'label'     => esc_html__( 'Icon size(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .more-photos-button i' => 'font-size: {{VALUE}}px;',
					'body {{WRAPPER}} .more-photos-button svg' => 'width: {{VALUE}}px;height: {{VALUE}}px;',
				),
			)
		);
		$this->add_responsive_control(
			'_gallery_icon_title_space',
			array(
				'label'     => esc_html__( 'Icon space(px)', 'travel-booking' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => -100,
				'max'       => 100,
				'step'      => 1,
				'selectors' => array(
					'body {{WRAPPER}} .more-photos-button i' => 'margin-right: {{VALUE}}px;',
					'body {{WRAPPER}} .more-photos-button svg' => 'margin-right: {{VALUE}}px;',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_gallery__button_style' );

		$this->start_controls_tab(
			'_gallery_tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'travel-booking' ),
			)
		);

		$this->add_control(
			'_gallery_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'_gallery_button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'_gallery_button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'_gallery_button_border_color!' => array( 'none', '' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'_gallery_icon_title_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .more-photos-button svg path' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_gallery_tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'travel-booking' ),
			)
		);

		$this->add_control(
			'_gallery_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'_gallery_button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'_gallery_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'_gallery_button_border_color!' => array( 'none', '' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'_gallery_icon_title_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .more-photos-button:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .more-photos-button:hover svg path' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	function ekits_get_gallery_image( $product ) {
		$settings          = $this->get_settings_for_display();
		$post_thumbnail_id = $product->get_image_id();
		$classthumb_style  = '';
		if ( isset( $settings['thumb_style'] ) && isset( $settings[ $settings['thumb_style'] . '_options' ] ) ) {
			$classthumb_style .= 'ekits-product-' . $settings['thumb_style'] . '__' . $settings[ $settings['thumb_style'] . '_options' ];
		}
		$wrapper_classes = apply_filters(
			'woocommerce_single_product_image_gallery_classes',
			array(
				'woocommerce-product-gallery',
				'ekits-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
				$classthumb_style,
				'images',
			)
		);
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
			<div class="ekits-product-<?php echo esc_attr( $settings['thumb_style'] ); ?>__wrapper" id="ekits-product-<?php echo esc_attr( $settings['thumb_style'] ); ?>">
				<ul class="<?php echo esc_attr( $settings['thumb_style'] ); ?>">
					<?php
					if ( $post_thumbnail_id ) {
						$html = $this->ekits_get_gallery_image_html( $post_thumbnail_id, true );
					} else {
						$html  = '<li class="woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						$html .= '</li>';
					}

					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

					$this->ekits_get_thumbnail_image( $product );
					?>
				</ul>
			</div>
			<?php
			if ( $settings['thumb_style'] == 'slides' ) {
				$data_slider = 'data-direction = "' . $settings['slides_options'] . '"';
				if ( $settings['slides_options'] == 'horizontal' ) {
					$data_slider .= ' data-marginitem="' . $settings['thumbnail_spacing']['size'] . '"';
					if ( isset( $settings['columns_options'] ) ) {
						$data_slider .= ' data-itemshow="' . $settings['columns_options'] . '"';
					}

					if ( isset( $settings['columns_options_tablet'] ) && $settings['columns_options_tablet'] ) {
						$data_slider .= ' data-itemshowtablet="' . $settings['columns_options_tablet'] . '"';
					}
					if ( isset( $settings['columns_options_mobile'] ) && $settings['columns_options_mobile'] ) {
						$data_slider .= ' data-itemshowmobile="' . $settings['columns_options_mobile'] . '"';
					}
				}
				echo '<div class="ekits-product-thumbnails__wrapper"' . $data_slider . '></div>';
			}
			?>
		</div>
		<?php
		if ( $settings['thumb_style'] == 'slides' ) {
			// js for flexslider
			wp_enqueue_script( 'flexslider' );
			$this->ekits_js_slider();
		}
	}

	function ekits_get_thumbnail_image( $product ) {
		$settings       = $this->get_settings_for_display();
		$attachment_ids = $product->get_gallery_image_ids();
		if ( $attachment_ids && $product->get_image_id() ) {
			$gMoreImages = array();
			if ( has_post_thumbnail() ) {
				$gMoreImages[] = array(
					'src'        => wp_get_attachment_url( get_post_thumbnail_id() ),
					'responsive' => wp_get_attachment_url( get_post_thumbnail_id() ),
					'thumb'      => wp_get_attachment_url( get_post_thumbnail_id() ),
					'subHtml'    => '',
				);
			}
			foreach ( $attachment_ids as $key => $attachment_id ) {
				if ( $settings['thumb_style'] == 'columns' && isset( $settings['lightbox'] ) && $settings['lightbox'] == 'yes' ) {
					if ( $key < $settings['number_to_show'] ) {
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $this->ekits_get_gallery_image_html( $attachment_id ), $attachment_id );
					}
					$gMoreImages[] = array(
						'src'        => wp_get_attachment_url( $attachment_id ),
						'responsive' => wp_get_attachment_url( $attachment_id ),
						'thumb'      => wp_get_attachment_url( $attachment_id ),
						'subHtml'    => '',
					);
				} else {
					echo apply_filters(
						'woocommerce_single_product_image_thumbnail_html',
						$this->ekits_get_gallery_image_html( $attachment_id ),
						$attachment_id
					);
				}
			}
			if ( $settings['thumb_style'] == 'columns' && isset( $settings['lightbox'] ) && $settings['lightbox'] == 'yes' ) {
				?>
				<div class="more-photos-button dynamic-gal" data-dynamicPath='<?php echo json_encode( $gMoreImages ); ?>'>
					<?php \Elementor\Icons_Manager::render_icon( $settings['bt_show_icon'], array( 'aria-hidden' => 'true' ) ); ?>
					<?php esc_html_e( $settings['bt_show_title'], 'travel-booking' ); ?>
				</div>
				<?php
			}
		}
	}
}
