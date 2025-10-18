<?php
/**
 * Travel Template Loader
 *
 * @author  physocde
 * @version 2.0.0
 */

class TravelPhysTemplateLoader {

	public static function init() {
		remove_filter( 'template_include', array( 'HotelTemplateLoader', 'hotel_template_loader' ), 11 );
		add_filter( 'template_include', array( __CLASS__, 'tb_template_loader_phys' ), 11, 1 );
	}

	public static function tb_template_loader_phys( $template ) {
		$arr_find_match = array(
			'single-tour.php',
			'archive-tour.php',
			'archive-attribute.php',
			'archive-attribute-tour.php',
		);
		$find           = array( 'woocommerce.php' );
		$file           = '';

		if ( is_embed() ) {
			return $template;
		}
		$condition_archive = $condition_single = '';
		if(class_exists('Thim_EL_Kit') && defined( 'ELEMENTOR_VERSION' )){
			$condition_archive = Thim_EL_Kit\Functions::instance()->get_conditions_by_type( 'archive-tour' );
			$condition_single = Thim_EL_Kit\Functions::instance()->get_conditions_by_type( 'single-tour' );
		}
		if ( is_single() && get_post_type() == 'product' ) {
			if ( wc_get_product()->get_type() == 'tour_phys' ) {
				$tour_id = wc_get_product()->get_id();
				$GLOBALS['wp_query']->set( 'is_single_tour', 1 );
				if(!empty($condition_single)){
					if(array_key_exists('all',$condition_single)){
						$file           = '';
					}elseif(array_key_exists('tour_type',$condition_single)){
						$conditions_single_detail = get_post_meta( $condition_single['tour_type'][0], 'thim_ekits_conditions', true );
						if(in_array($conditions_single_detail[0]['query'],self::check_term_is_tour_type($tour_id))){
							$file           = '';
						}else{
							$file = 'single-tour.php';
						}
					}elseif(array_key_exists('tour_ids',$condition_single)){
						$conditions_single_detail = get_post_meta( $condition_single['tour_ids'][0], 'thim_ekits_conditions', true );
						if($conditions_single_detail[0]['query'] == $tour_id){
							$file           = '';
						}else{
							$file = 'single-tour.php';
						}
					}else{
						$file = 'single-tour.php';
					}
				}else{
					$file = 'single-tour.php';
				}
			} else {
				$file = 'single-product.php';
			}

			$find[] = $file;
			$find[] = WC()->template_path() . $file;

		} elseif ( is_product_taxonomy() ) {
			$term = get_queried_object();

			if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
				$file = 'taxonomy-' . $term->taxonomy . '.php';
			} else {
				if ( $term->taxonomy == 'tour_phys' ) {
					$GLOBALS['wp_query']->set( 'is_tour', 1 );
					if(!empty($condition_archive) && array_key_exists('tour_type',$condition_archive)){
						$conditions_archive_detail = get_post_meta( $condition_archive['tour_type'][0], 'thim_ekits_conditions', true );
						if($conditions_archive_detail[0]['query'] == $term->slug){
							$file = '';
						}else{
							$file = 'archive-tour.php';
						}
					}elseif(!empty($condition_archive) && array_key_exists('all',$condition_archive)){
						$file = '';
					}else{
						$file = 'archive-tour.php';
					}
				} else {
					$pattern         = '/^pa_/i';
					$check_attribute = preg_match( $pattern, $term->taxonomy );
					if ( $check_attribute ) {
						if(!empty($condition_archive) && array_key_exists('woo_attributes',$condition_archive) ){
							// if(count($condition_archive['woo_attributes']) >= 2){
								foreach($condition_archive['woo_attributes'] as $key => $value){
									$termpage = get_queried_object();
									if(isset($termpage) && !empty($termpage->taxonomy) && $termpage->taxonomy == $value){
										$conditions_archive_detail = get_post_meta($value, 'thim_ekits_conditions', true);
										if ($conditions_archive_detail[0]['query'] == $term->taxonomy) {
											$file = '';
										} else {
											$file = 'archive-attribute.php';
										}
									}
								}
							// }else{
							// 	$conditions_archive_detail = get_post_meta($condition_archive['woo_attributes'][0], 'thim_ekits_conditions', true);
							// 	if ($conditions_archive_detail[0]['query'] == $term->taxonomy) {
							// 		$file = '';
							// 	} else {
							// 		$file = 'archive-attribute.php';
							// 	}
							// }

						}elseif(!empty($condition_archive) &&  array_key_exists('all',$condition_archive)){
							$file = '';
						}else{
							$file = 'archive-attribute.php';
						}
					}else{
						$file = 'archive-tour.php';
					}
				}
			}

			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = WC()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = WC()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = $file;
			$find[] = WC()->template_path() . $file;

		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			if ( TravelPhysUtility::check_is_tour_archive() ) {
				if(empty($condition_archive)  || !array_key_exists('all',$condition_archive) ){
					$file = 'archive-tour.php';
				}
			}else{
				$file   = 'archive-product.php';
				$find[] = $file;
				$find[] = WC()->template_path() . $file;
			}
		}
		if ( $file ) {
			// $template = locate_template( array_unique( $find ) );
			if ( in_array( $file, $arr_find_match ) ) {
				$template = tb_get_file_template( $file );
			} elseif ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
				$template = WC()->plugin_path() . '/templates/' . $file;
			}
		}

		/*** For permalink structure is Plain ***/
		if ( isset( $_GET['page_id'] ) && $_GET['page_id'] == get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) {
			wp_safe_redirect( home_url( '?is_tour=1' ) );
		}

		//echo 'Travel loader';

		if ( is_plugin_active( 'hotel-booking/hotel-booking-phys.php' ) ) {
			global $wp;
			$current_url          = home_url( add_query_arg( array(), $wp->request ) ) . '/';
			$_page_id_show_hotels = (int) get_option( HotelSetting::$_hotel_show_page_id );
			$link_archive_hotel   = get_page_link( $_page_id_show_hotels );
			$replace              = str_replace( $link_archive_hotel, '', $current_url );

			if ( $replace != $current_url ) {
				$template = HotelTemplateLoader::hotel_template_loader( $template );

				return $template;
			}
		}

		return $template;
	}
	protected static function check_term_is_tour_type($tour_id){
		$term_slug = array();
		$terms = get_the_terms( $tour_id, 'tour_phys' );

        if ( $terms && ! is_wp_error( $terms ) ){
			foreach ( $terms as $term ) {
				$term_slug[] = $term->slug;
            }
		}
		return $term_slug;
	}
}

TravelPhysTemplateLoader::init();
