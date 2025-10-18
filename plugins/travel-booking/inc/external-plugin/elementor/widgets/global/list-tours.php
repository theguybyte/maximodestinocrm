<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Thim_EL_Kit\GroupControlTrait;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;

class Thim_Ekit_Widget_List_Tours extends Widget_Base {
	use GroupControlTrait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-list-tours';
	}

	public function get_title() {
		return esc_html__( 'List Tours', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	public function get_style_depends(): array {
		return array( 'e-swiper' );
	}
	public function get_script_depends(): array {
		return array( 'swiper' );
	}
	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::TOUR_BOOKING );
	}

	public function get_keywords() {
		return array(
			'thim',
			'tours',
			'list tours',
			'tours',
		);
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->register_tours_setting();
		$this->register_style_layout();
		$this->register_pagination_options();
		$this->_register_settings_slider(
			array(
				'style' => 'slider',
			)
		);
		$this->_register_style_pagination();
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
	}

	protected function register_tours_setting() {
		$this->start_controls_section(
			'general_settings',
			array(
				'label' => esc_html__( 'General Settings', 'travel-booking' ),
			)
		);
		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Layout', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'pain'   => esc_html__( 'Pain', 'travel-booking' ),
					'slider' => esc_html__( 'Slider', 'travel-booking' ),

				),
				'default' => 'pain',
			)
		);
		$this->add_control(
			'template_id',
			array(
				'label'              => esc_html__( 'Template ID', 'travel-booking' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '0',
				'options'            => array( '0' => esc_html__( 'None', 'travel-booking' ) ) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'tours' ),
				'frontend_available' => true,
			)
		);
		$this->add_control(
			'cat_ids',
			array(
				'label'       => esc_html__( 'Select Tour Category', 'travel-booking' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_cats_tour(),
			)
		);
		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order by', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'date'       => esc_html__( 'Date', 'travel-booking' ),
					'price'      => esc_html__( 'Price', 'travel-booking' ),
					'rand'       => esc_html__( 'Random', 'travel-booking' ),
					'sales'      => esc_html__( 'On-sale', 'travel-booking' ),
					'menu_order' => esc_html__( 'Sort custom', 'travel-booking' ),

				),
				'default' => 'date',
			)
		);
		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'desc' => esc_html__( 'DESC', 'travel-booking' ),
					'asc'  => esc_html__( 'ASC', 'travel-booking' ),

				),
				'default' => 'desc',
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
				// 'condition' => array(
				//     'style' => 'pain',
				// ),
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
					'{{WRAPPER}}' => '--thim-tours-columns: {{VALUE}}',
				),
				'condition' => array(
					'style' => 'pain',
				),
			)
		);

		$this->end_controls_section();
	}
	protected function register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label'     => esc_html__( 'Layout', 'travel-booking' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style' => 'pain',
				),
			)
		);
		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-tours-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'travel-booking' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-tours-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	function register_pagination_options() {
		$this->start_controls_section(
			'section_attr_pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'travel-booking' ),
				'condition' => array(
					'style' => 'pain',
				),
			)
		);

		$this->add_control(
			'pagination_type',
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
					'{{WRAPPER}} .wp-pagination-list-tours' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type' => 'numbers',
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
					'pagination_type' => 'numbers',
				),
			)
		);
		$this->add_responsive_control(
			'pagination_padding',
			array(
				'label'      => esc_html__( 'Padding', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pagination_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .wp-pagination-list-tours .page-numbers',
			)
		);
		$this->add_control(
			'onsale_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'travel-booking' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .wp-pagination-list-tours .page-numbers',
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
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(.dots)' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_bg',
			array(
				'label'     => esc_html__( 'Background', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(.dots)' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'pagination_border_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(.dots)' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_hover_bg',
			array(
				'label'     => esc_html__( 'Background', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers:hover' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_border_colorhover',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'pagination_border_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(.dots):hover' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers.current' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_active_bg',
			array(
				'label'     => esc_html__( 'Background', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wp-pagination-list-tours .page-numbers.current' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'pagination_border_colorcurrent',
			array(
				'label'     => esc_html__( 'Border Color', 'travel-booking' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'pagination_border_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .wp-pagination-list-tours  .page-numbers.current' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .wp-pagination-list-tours' => 'margin-top:{{SIZE}}px;',
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
					'body:not(.rtl) {{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(:last-child)'  => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(:first-child)'       => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .wp-pagination-list-tours .page-numbers:not(:last-child)'        => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function get_cats_tour() {
		$args          = array(
			'pad_counts'         => 1,
			'show_counts'        => 1,
			'hierarchical'       => 1,
			'hide_empty'         => 1,
			'show_uncategorized' => 1,
			'orderby'            => 'name',
			'menu_order'         => false,
		);
		$terms         = get_terms( 'tour_phys', $args );
		$tour_cat      = array();
		$tour_cat['0'] = 'Select Tour Category';
		if ( is_wp_error( $terms ) ) {
		} else {
			if ( empty( $terms ) ) {
			} else {
				foreach ( $terms as $term ) {
					$tour_cat[ $term->term_id ] = $term->name;
				}
			}
		}
		return $tour_cat;
	}
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$query_args = array(
			'posts_per_page' => $settings['limit'],
			'post_status'    => 'publish',
			// 'no_found_rows'  => 1,
			'order'          => $settings['order'] == 'asc' ? 'asc' : 'desc',
			'post_type'      => array( 'product' ),
			'wc_query'       => 'tours',
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,

		);
		$query_args['meta_query'] = array();
		if ( ! empty( $settings['cat_ids'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'tour_phys',
					'field'    => 'term_id',
					'terms'    => $settings['cat_ids'],
					'operator' => 'IN',
				),
			);
		} else {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'tour_phys' ),
					'operator' => 'IN',
				),
			);
		}
		switch ( $settings['orderby'] ) {
			case 'price':
				$query_args['meta_key'] = '_price';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand':
				$query_args['orderby'] = 'rand';
				break;
			case 'sales':
				// $product_ids_on_sale    = wc_get_product_ids_on_sale();
				// $product_ids_on_sale[]  = 0;
				// $query_args['post__in'] = $product_ids_on_sale;
				// $query_args['meta_key'] = '_price';
				// $query_args['orderby']  = 'meta_value_num';
				$query_args['meta_query'] = array(
					array(
						'key'     => '_sale_price',
						'value'   => 0,
						'compare' => '>',
						'type'    => 'NUMERIC',
					),
				);
				$query_args['orderby']    = 'meta_value_num';
				break;
			case 'menu_order':
				$query_args['orderby'] = 'menu_order';
				break;
			default:
				$query_args['orderby'] = 'date';
		}
		$the_query   = new \WP_Query( $query_args );
		$class       = 'thim-ekits-tours';
		$class_inner = 'thim-ekits-tours__inner';
		$class_item  = 'thim-ekits-tours__item';
		if ( $the_query->have_posts() ) {
			if ( isset( $settings['style'] ) && $settings['style'] == 'slider' ) {
				$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
				$class       .= ' thim-ekits-sliders ' . $swiper_class;
				$class_inner  = 'swiper-wrapper';
				$class_item  .= ' swiper-slide';

				$this->render_nav_pagination_slider( $settings );
			}
			echo '<div class="row list-tours-slider ' . $class . '">';
			echo '<div class="list_content tours-type-' . $settings['style'] . ' ' . $class_inner . ' ">';
			while ( $the_query->have_posts() ) :
				$the_query->the_post();
				echo '<div class="tour-item ' . $class_item . '">';
				if ( $settings['template_id'] != '0' ) {
					\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
				} else {
					tb_get_file_template( 'content-tour.php', false );
				}
				echo '</div>';
			endwhile;
			echo '</div>';
			echo '</div>';
			if ( isset( $settings['pagination_type'] ) && $settings['pagination_type'] == 'numbers' ) {
				$big = 999999999; // need an unlikely integer
				echo '<div class="wp-pagination-list-tours">';
				echo paginate_links(
					array(
						'base'    => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
						'format'  => '?paged=%#%',
						'current' => max( 1, get_query_var( 'paged' ) ),
						'total'   => $the_query->max_num_pages,
					)
				);
				echo '</div>';
			}
			wp_reset_postdata();

			// $this->render_loop_footer($the_query, $settings);
		} else {
			echo '<div class="message-info">' . __( 'No data were found matching your selection, you need to create Post or select Category of Widget.', 'travel-booking' ) . '</div>';
		}
	}
}
