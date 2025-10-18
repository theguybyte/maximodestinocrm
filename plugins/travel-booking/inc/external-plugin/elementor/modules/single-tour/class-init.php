<?php
namespace TravelBooking\Modules\SingleTour;

use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\SingletonTrait;

class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'single-tour';
		$this->tab_name = esc_html__( 'Single Tour', 'travel-booking' );

		parent::__construct();
		add_action( 'elementor/frontend/before_get_builder_content', array( $this, 'before_get_content' ) );
		add_action( 'elementor/frontend/get_builder_content', array( $this, 'after_get_content' ) );
	}

	public function template_include( $template ) {

		if ( ! class_exists( '\WooCommerce' ) ) {
			return $template;
		}
		$this->template_include = is_product();

		return parent::template_include( $template );
	}

	public function get_preview_id() {
		global $post;

		$output = false;

		if ( $post ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post->ID );

			if ( $document ) {
				$preview_id = $document->get_settings( 'thim_ekits_preview_id' );

				$output = ! empty( $preview_id ) ? absint( $preview_id ) : false;
			}
		}

		return $output;
	}

	public function before_preview_query() {
		$preview_id = $this->get_preview_id();

		if ( $preview_id ) {
			$query = array(
				'p'         => absint( $preview_id ),
				'post_type' => 'product',
				'wc_query'  => 'tours',
				'tax_query' => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'tour_phys' ),
						'operator' => 'IN',
					),
				),
			);
		} else {
			$query_vars = array(
				'post_type'      => 'product',
				'posts_per_page' => 1,
				'wc_query'       => 'tours',
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'tour_phys' ),
						'operator' => 'IN',
					),
				),
			);

			$posts = get_posts( $query_vars );

			if ( ! empty( $posts ) ) {
				$query = array(
					'p'         => $posts[0]->ID,
					'post_type' => 'product',
					'wc_query'  => 'tours',
					'tax_query' => array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => array( 'tour_phys' ),
							'operator' => 'IN',
						),
					),
				);
			}
		}

		if ( ! empty( $query ) ) {
			\Elementor\Plugin::instance()->db->switch_to_query( $query, true );
		}
	}

	public function before_get_content() {
		if ( ! class_exists( '\WooCommerce' ) ) {
			return;
		}

		if ( ! get_the_ID() ) {
			return;
		}

		if ( ! is_product() ) {
			return;
		}

		$type = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );

		if ( $type === $this->tab || ! empty( $option ) ) {
			$option = $this->get_layout_id( $this->tab );

			if ( ! empty( $option ) ) {
				global $product;

				if ( ! is_object( $product ) ) {
					$product = wc_get_product( get_the_ID() );
				}

				do_action( 'woocommerce_before_single_product' );
			}
		}
	}

	public function after_get_content() {
		if ( ! class_exists( '\WooCommerce' ) ) {
			return;
		}

		if ( ! get_the_ID() ) {
			return;
		}

		if ( ! is_product() ) {
			return;
		}

		$type = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );

		if ( $type === $this->tab ) {
			$option = $this->get_layout_id( $this->tab );

			if ( ! empty( $option ) ) {
				wp_reset_postdata();

				do_action( 'woocommerce_after_single_product' );
			}
		}
	}

	public function is( $condition ) {
		global $wp_query, $product;
		if ( $wp_query->get_queried_object() ) {
			if ( isset( $wp_query->get_queried_object()->slug ) ) {
				$term_slug = $wp_query->get_queried_object()->slug;
			}
			if ( isset( $wp_query->get_queried_object()->taxonomy ) ) {
				$term_taxonomy = $wp_query->get_queried_object()->taxonomy;
			}
		}
		if ( isset( $wp_query->posts ) && ! empty( $wp_query->posts[0] ) ) {
			$tour_id = $wp_query->posts[0]->ID;
		}
		switch ( $condition['type'] ) {
			case 'all':
				return wc_get_product()->get_type() == 'tour_phys';
			case 'tour_type':
				return in_array( $condition['query'], $this->check_term_is_tour_type( $tour_id ) );
			case 'tour_ids':
				return $tour_id === $condition['query'];
		}

		return false;
	}

	public function priority( $type ) {
		$priority = 100;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'tour_type':
				$priority = 20;
				break;
			case 'tour_ids':
				$priority = 30;
				break;
		}

		return apply_filters( 'thim_ekit/condition/priority', $priority, $type );
	}

	public function get_conditions() {
		return array(
			array(
				'label'    => esc_html__( 'All Tour', 'travel-booking' ),
				'value'    => 'all',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Select Tour Type', 'travel-booking' ),
				'value'    => 'tour_type',
				'is_query' => true,
			),
			array(
				'label'    => esc_html__( 'Select Tour', 'travel-booking' ),
				'value'    => 'tour_ids',
				'is_query' => true,
			),
		);
	}
	function check_term_is_tour_type( $tour_id ) {
		$term_slug = array();
		$terms     = get_the_terms( $tour_id, 'tour_phys' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_slug[] = $term->slug;
			}
		}
		return $term_slug;
	}
}

Init::instance();
