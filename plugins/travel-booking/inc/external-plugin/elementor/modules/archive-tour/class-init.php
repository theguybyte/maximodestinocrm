<?php
namespace TravelBooking\Modules\ArchiveTour;

use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\SingletonTrait;
class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'archive-tour';
		$this->tab_name = esc_html__( 'Archive Tour', 'travel-booking' );

		parent::__construct();
		add_filter( 'thim_ekit/elementor/archive_tours/query_posts/query_vars', array( $this, 'query_args' ) );
	}
	public function query_args( $query_args ) {
		$id   = get_the_ID();
		$type = get_post_meta( $id, Custom_Post_Type::TYPE, true );
		if ( $id && $type && $type === $this->tab && ( $this->is_editor_preview() || $this->is_modules_view() ) ) {
			$query_args = array(
				'post_type' => array( 'product' ),
				'wc_query'  => 'tours',
			);
		}

		return $query_args;
	}
	public function template_include( $template ) {
		if ( is_product_taxonomy() && ( ! is_tax( 'product_cat' ) && ! is_tax( 'product_tag' ) ) ) {
			$this->template_include = \TravelPhysUtility::check_is_tour_archive();
		}
		if ( ! is_product_taxonomy() && ! is_page( wc_get_page_id( 'shop' ) ) ) {
			if ( \TravelPhysUtility::check_is_tour_archive() ) {
				$this->template_include = \TravelPhysUtility::check_is_tour_archive();
			}
		}
		return parent::template_include( $template );
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
		if ( $condition['type'] === 'all' ) {
			return \TravelPhysUtility::check_is_tour_archive();
		}
		if ( $condition['type'] === 'tour_type' && isset( $term_slug ) ) {
			return $term_slug === $condition['query'];
		}
		if ( $condition['type'] === 'woo_attributes' && isset( $term_taxonomy ) ) {
			return $term_taxonomy === $condition['query'];
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
			case 'woo_attributes':
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
				'label'    => esc_html__( 'Select Attribute', 'travel-booking' ),
				'value'    => 'woo_attributes',
				'is_query' => true,
			),
		);
	}
}

Init::instance();
