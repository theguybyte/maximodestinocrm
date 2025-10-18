<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tour Query
 *
 * @author  physcode
 * @version 1.2.7
 */
class TravelPhysQuery {
	public function __construct() {
		if ( ! is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'filter_pre_get_tours' ), 16, 1 );
			add_filter( 'query_vars', array( $this, 'filter_query_vars' ) );
			add_action( 'wp', array( $this, 'remove_tour_query' ) );
		} elseif ( is_admin() && ! wp_doing_ajax() ) {
			add_action( 'pre_get_posts', array( $this, 'query_get_posts_product_admin' ), 16, 1 );
			add_filter( 'views_edit-product', array( $this, 'view_edit_product_tour_phys' ), 11, 1 );
		}
//		new WP_Query();
	}

	/**
	 * @param $q
	 *
	 * @return void
	 */
	public function filter_pre_get_tours( $q ) {
		global $wp_query;
		$taxonomy = '';

		if ( $wp_query ) {
			$q_object = $wp_query->get_queried_object();
			if ( $q_object ) {
				$taxonomy = $q_object->taxonomy ?? '';
			}
		}

		if ( ! $q->is_main_query() ) {
			return;
		}

		if ( $q->get( 'is_hotel' ) ) {
			return;
		}


		if ( $q->get( 'is_tour' ) ) {
			$this->query_for_tour( $q );
			$this->remove_tour_query();

			return;
		} elseif ( $q->get( 'tour_search' ) || is_tax( $taxonomy ) ) {
			error_log("TOUR QUERY");
			$q = $this->query_for_tour( $q );

			if ( $q->get( 'name_tour' ) ) {
				$q->set( 's', $q->get( 'name_tour' ) );
			}

			$tax_query_attribute = array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'tour_phys' ),
					'operator' => 'IN',
				),
			);

			if ( $q->get( 'tourtax' ) ) {
				foreach ( $q->get( 'tourtax' ) as $key => $tax_attribute ) {
					if ( $tax_attribute != '0' ) {
						$q_term                = array(
							'taxonomy' => $key,
							'field'    => 'slug',
							'terms'    => array( $tax_attribute ),
							'operator' => 'IN',
						);
						$tax_query_attribute[] = $q_term;
					}
				}

				$q->set( 'tax_query', $tax_query_attribute );
			}

			//			$args['meta_query'] = $q->get( 'meta_query' );
			$args                           = array();
			$args['meta_query']['relation'] = 'AND';

			if ( $q->get( 'tour_min_price' ) && $q->get( 'tour_max_price' ) ) {
				$tour_price_min = $q->get( 'tour_min_price' );
				$tour_price_max = $q->get( 'tour_max_price' );

				$args['meta_query'][] = array(
					'key'     => '_price',
					'value'   => array( $tour_price_min, $tour_price_max ),
					'compare' => 'BETWEEN',
					'type'    => 'DECIMAL(10,' . wc_get_price_decimals() . ')',
				);
			}

			if ( $q->get( 'tour_code' ) ) {
				$args['meta_query'][] = array(
					'key'     => '_tour_code',
					'value'   => $q->get( 'tour_code' ),
					'compare' => '=',
				);
			}

			if ( $q->get( 'tour_rating' )) {

				if( $q->get( 'tour_rating' ) !== 'all'){
					$args['meta_query'][] = array(
						'relation' => 'AND',
						array(
							'key'     => '_wc_average_rating',
							'value'   => $q->get( 'tour_rating' ),
							'compare' => '>=',
							'type'    => 'DECIMAL(10, 3)',
						)
					);
				}
			}

			//Date
			if ( isset( $_GET['date_range'] ) && ! empty( $_GET['date_range'] ) ) {
				$date_range = wc_clean( urldecode( $_GET['date_range'] ) );
				if ( is_string( $date_range ) ) {
					$date_range = explode( '-', $date_range );
				} else {
					$date_range = $date_range;
				}
				$date_range = array_map(
					function ( $date ) {
						return str_replace( ' ', '', str_replace( '/', '-', $date ) );
					},
					$date_range
				);

				$start = isset( $date_range[0] ) ? explode( '-', $date_range[0] ) : array();
				$end   = isset( $date_range[1] ) ? explode( '-', $date_range[1] ) : array();

				$start_date = strtotime( $date_range[0] );
				$end_date   = strtotime( $date_range[1] );
				$now        = time();

				$phys_starts_after = $end_date - $now;
				// now + Booking starts after day < $end_date
				if ( $phys_starts_after >= 0 ) {
					$args['meta_query'][] = array(
						'relation' => 'OR',
						array(
							'key'     => '_phys_starts_after',
							'value'   => round( $phys_starts_after / ( 60 * 60 * 24 ) ),
							'compare' => '<=',
							'type'    => 'DECIMAL',
						),
						array(
							'key'     => '_phys_starts_after',
							'compare' => 'NOT EXISTS',
						)
					);

					//Max year enable >= $start_date
					$args['meta_query'][] = array(
						'relation' => 'OR',
						array(
							'key'     => '_phys_max_year_enable',
							'value'   => intval( $start[2] ),
							'compare' => '>=',
							'type'    => 'DECIMAL',
						),
						array(
							'key'     => '_phys_max_year_enable',
							'compare' => 'NOT EXISTS',
						)
					);
				} else {
					$args['meta_query'][] = array(
						'key'     => '_phys_starts_after',
						'value'   => - 1,
						'compare' => '=',
						'type'    => 'DECIMAL',
					);
				}

			}

			//Duration
			if ( $q->get( 'duration' ) ) {
				if ( is_string( $q->get( 'duration' ) ) ) {
					$duration = explode( ',', $q->get( 'duration' ) );
				} else {
					$duration = $q->get( 'duration' );
				}

				$duration_args             = [];
				$duration_args['relation'] = 'OR';
				$less_than_1_day           = [ 'less_than_1_day' ];
				if ( count( array_intersect( $less_than_1_day, $duration ) ) ) {
					$duration_args[] = array(
						'key'     => '_phys_tour_duration_number',
						'value'   => 1,
						'type'    => 'DECIMAL',
						'compare' => '<'
					);
				}

				if ( in_array( 'from_1_to_3_days', $duration ) ) {
					$duration_args[] = array(
						'key'     => '_phys_tour_duration_number',
						'value'   => array( 1, 3 ),
						'type'    => 'DECIMAL',
						'compare' => 'BETWEEN'
					);
				}

				if ( in_array( 'greater_than_3_days', $duration ) ) {
					$duration_args[] = array(
						'key'     => '_phys_tour_duration_number',
						'value'   => 3,
						'type'    => 'DECIMAL',
						'compare' => '>'
					);
				}

				$args['meta_query'][] = $duration_args;
			}

			// Catalog visibility
			$args['meta_query'][] = array(
				'relation' => 'OR',
				array(
					'key'     => '_visibility',
					'value'   => 'visible',
					'compare' => '=',
				),
				array(
					'key'     => '_visibility',
					'value'   => 'search',
					'compare' => '=',
				),
				array(
					'key'     => '_visibility',
					'compare' => 'NOT EXISTS',
					'value'   => '',
				),
			);

			//var_dump($args['meta_query']);

			$q->set( 'meta_query', $args['meta_query'] );

		} elseif ( 'product' == $q->get( 'post_type' ) ) {
			$q->set(
				'tax_query',
				array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'simple', 'grouped', 'variable', 'external' ),
						'operator' => 'IN',
					),
				)
			);
		}

		error_log("END TOUR QUERY".print_r($q, true));

		$this->remove_tour_query();
	}

	public function query_get_posts_product_admin( $query ) {
		//Fix override query for only page show list products on Backend
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( $query->get( 'post_type' ) == 'product' && $query->get( 'product_type' ) != 'tour_phys' ) {
			$query->set(
				'tax_query',
				array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'tour_phys' ),
						'operator' => 'NOT IN',
					),
				)
			);
		}
	}

	private function query_for_tour( $q ) {
		$q->set( 'is_tour', 1 );
		$q->set( 'wc_query', 'tours' );
		$q->set( 'post_type', 'product' );
		$q->set( 'page', '' );
		$q->set( 'pagename', '' );
		$q->set(
			'tax_query',
			array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'tour_phys' ),
					'operator' => 'IN',
				),
			)
		);

		$args               = array();
		$args['meta_query'] = $q->get( 'meta_query' );

		if ( ! is_array( $args['meta_query'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'     => '_visibility',
					'value'   => 'visible',
					'compare' => '=',
				),
				array(
					'key'     => '_visibility',
					'value'   => 'catalog',
					'compare' => '=',
				),
				array(
					'key'     => '_visibility',
					'compare' => 'NOT EXISTS',
					'value'   => '',
				),
			);
		} else {
			$args['meta_query'][] = array(
				'relation' => 'OR',
				array(
					'key'     => '_visibility',
					'value'   => 'visible',
					'compare' => '=',
				),
				array(
					'key'     => '_visibility',
					'value'   => 'catalog',
					'compare' => '=',
				),
				array(
					'key'     => '_visibility',
					'compare' => 'NOT EXISTS',
					'value'   => '',
				),
			);
		}
		$q->set( 'posts_per_page', $q->get( 'posts_per_page' ) ? $q->get( 'posts_per_page' ) : apply_filters( 'loop_shop_per_page', get_option( 'posts_per_page' ) ) );

		$q->set( 'meta_query', $args['meta_query'] );

		// Fix conditional Functions
		$q->is_archive           = true;
		$q->is_post_type_archive = true;
		$q->is_singular          = false;
		$q->is_page              = false;

		$this->tour_query_order( $q );

		return $q;
	}

	public function remove_tour_query() {
		remove_action( 'pre_get_posts', array( $this, 'filter_pre_get_tours' ), 15 );
	}

	public function filter_query_vars( $vars ) {
		$vars[] = 'is_tour';
		$vars[] = 'is_single_tour';
		$vars[] = 'tour_search';
		$vars[] = 'tourtax';
		$vars[] = 'name_tour';
		$vars[] = 'tour_code';
		$vars[] = 'tour_rating';
		$vars[] = 'tour_min_price';
		$vars[] = 'tour_max_price';
		$vars[] = 'duration';

		return $vars;
	}

	public function tour_query_order( $q ) {
		// Ordering query vars
		$ordering = $this->get_catalog_ordering_args();
		$q->set( 'orderby', $ordering['orderby'] );
		$q->set( 'order', $ordering['order'] );
		if ( isset( $ordering['meta_key'] ) ) {
			$q->set( 'meta_key', $ordering['meta_key'] );
		}
	}

	public function get_catalog_ordering_args( $orderby = '', $order = '' ) {
		// Get ordering from query string unless defined
		if ( ! $orderby ) {
			$orderby_value = isset( $_GET['orderbyt'] ) ? wc_clean( $_GET['orderbyt'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

			// Get order + orderby args from string
			$orderby_value = explode( '-', $orderby_value );
			$orderby       = esc_attr( $orderby_value[0] );
			$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : $order;
		}
		//$orderby = 'price';
		$orderby = strtolower( $orderby );
		$order   = strtoupper( $order );
		$args    = array();

		// default - menu_order
		$args['orderby']  = 'menu_order title';
		$args['order']    = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
		$args['meta_key'] = '';

		switch ( $orderby ) {
			case 'rand':
				$args['orderby'] = 'rand';
				break;
			case 'date':
				$args['orderby'] = 'date ID';
				$args['order']   = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
				break;
			case 'price':
				$args['orderby']  = 'meta_value_num ID';
				$args['order']    = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
				$args['meta_key'] = '_price';
				break;
			case 'popularity':
				$args['meta_key'] = 'total_sales';

				// Sorting handled later though a hook
				add_filter( 'posts_clauses', array( $this, 'order_by_popularity_post_clauses' ) );
				break;
			case 'rating':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = array(
					'meta_value_num' => 'DESC',
					'ID'             => 'ASC',
				);
				break;
			case 'title':
				$args['orderby'] = 'title';
				$args['order']   = ( 'DESC' === $order ) ? 'DESC' : 'ASC';
				break;
		}

		return apply_filters( 'hotel_get_catalog_ordering_args', $args );
	}

	public function order_by_popularity_post_clauses( $args ) {
		global $wpdb;
		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";

		return $args;
	}

	public function view_edit_product_tour_phys( $view ) {
		global $wpdb;

		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product'
		     && isset( $_GET['product_type'] ) && $_GET['product_type'] == 'tour_phys' ) {

			$queryCountAllToursNotTrash = $wpdb->get_results(
				"SELECT COUNT(DISTINCT ID) as Total FROM $wpdb->posts as post
					LEFT JOIN $wpdb->term_relationships as term_relationships
						ON post.ID = term_relationships.object_id
					LEFT JOIN $wpdb->term_taxonomy as term_taxonomy
						On term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
					LEFT JOIN $wpdb->terms as terms
						On term_taxonomy.term_id = terms.term_id
					WHERE `post_type` = 'product'
					AND `post_status` != 'trash'
					AND terms.name = 'tour_phys'"
			);

			$queryCountAllToursPublish = $wpdb->get_results(
				"SELECT COUNT(DISTINCT ID) as Total FROM $wpdb->posts as post
					LEFT JOIN $wpdb->term_relationships as term_relationships
						ON post.ID = term_relationships.object_id
					LEFT JOIN $wpdb->term_taxonomy as term_taxonomy
						On term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
					LEFT JOIN $wpdb->terms as terms
						On term_taxonomy.term_id = terms.term_id
					WHERE `post_type` = 'product'
					AND `post_status` = 'publish'
					AND terms.name = 'tour_phys'"
			);

			$queryCountAllToursTrash = $wpdb->get_results(
				"SELECT COUNT(DISTINCT ID) as Total FROM $wpdb->posts as post
					LEFT JOIN $wpdb->term_relationships as term_relationships
						ON post.ID = term_relationships.object_id
					LEFT JOIN $wpdb->term_taxonomy as term_taxonomy
						On term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
					LEFT JOIN $wpdb->terms as terms
						On term_taxonomy.term_id = terms.term_id
					WHERE `post_type` = 'product'
					AND `post_status` = 'trash'
					AND terms.name = 'tour_phys'"
			);

			$view['all']     = sprintf( '<a href="edit.php?post_type=product&#038;product_type=tour_phys&#038;all_posts=1">All <span class="count">(%d)</span></a>', $queryCountAllToursNotTrash[0]->Total );
			$view['publish'] = sprintf( '<a href="edit.php?post_status=publish&#038;post_type=product&#038;product_type=tour_phys">Publish <span class="count">(%d)</span></a>', $queryCountAllToursPublish[0]->Total );

			if ( $queryCountAllToursTrash[0]->Total > 0 ) {
				$view['trash'] = sprintf( '<a href="edit.php?post_status=trash&#038;post_type=product&#038;product_type=tour_phys">Trash <span class="count">(%d)</span></a>', $queryCountAllToursTrash[0]->Total );
			} else {
				unset( $view['trash'] );
			}
		} elseif ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product' ) {
			$product_type_woo_default = array(
				'simple',
				'grouped',
				'variable',
				'external',
			);

			$product_type_woo_default_str = "'" . implode( "','", $product_type_woo_default ) . "'";

			$queryCountAllToursNotTrash = $wpdb->get_results(
				"SELECT COUNT(DISTINCT post.ID) as Total FROM $wpdb->posts as post
					LEFT JOIN $wpdb->term_relationships as term_relationships
						ON post.ID = term_relationships.object_id
					LEFT JOIN $wpdb->term_taxonomy as term_taxonomy
						On term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
					LEFT JOIN $wpdb->terms as terms
						On term_taxonomy.term_id = terms.term_id
					WHERE `post_type` = 'product'
					AND `post_status` != 'trash'
					AND terms.name In ($product_type_woo_default_str)"
			);

			$queryCountAllToursPublish = $wpdb->get_results(
				"SELECT COUNT(DISTINCT ID) as Total FROM $wpdb->posts as post
					LEFT JOIN $wpdb->term_relationships as term_relationships
						ON post.ID = term_relationships.object_id
					LEFT JOIN $wpdb->term_taxonomy as term_taxonomy
						On term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
					LEFT JOIN $wpdb->terms as terms
						On term_taxonomy.term_id = terms.term_id
					WHERE `post_type` = 'product'
					AND `post_status` = 'publish'
					AND terms.name In ($product_type_woo_default_str)"
			);

			$queryCountAllToursTrash = $wpdb->get_results(
				"SELECT COUNT(DISTINCT ID) as Total FROM $wpdb->posts as post
					LEFT JOIN $wpdb->term_relationships as term_relationships
						ON post.ID = term_relationships.object_id
					LEFT JOIN $wpdb->term_taxonomy as term_taxonomy
						On term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
					LEFT JOIN $wpdb->terms as terms
						On term_taxonomy.term_id = terms.term_id
					WHERE `post_type` = 'product'
					AND `post_status` = 'trash'
					AND terms.name In ($product_type_woo_default_str)"
			);

			$view['all']     = sprintf( '<a href="edit.php?post_type=product&#038;all_posts=1">All <span class="count">(%d)</span></a>', $queryCountAllToursNotTrash[0]->Total );
			$view['publish'] = sprintf( '<a href="edit.php?post_status=publish&#038;post_type=product">Publish <span class="count">(%d)</span></a>', $queryCountAllToursPublish[0]->Total );

			if ( $queryCountAllToursTrash[0]->Total > 0 ) {
				$view['trash'] = sprintf( '<a href="edit.php?post_status=trash&#038;post_type=product">Trash <span class="count">(%d)</span></a>', $queryCountAllToursTrash[0]->Total );
			} else {
				unset( $view['trash'] );
			}
		}

		return $view;
	}
}

new TravelPhysQuery();
