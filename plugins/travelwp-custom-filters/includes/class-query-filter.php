<?php

/**
 * Query Filter - Handles product filtering by taxonomies
 */
class TravelWP_Query_Filter {
    
    public function __construct() {
        // add_action('init', [$this, 'register_query_vars']);
        // add_action('template_redirect', [$this, 'handle_empty_search'], 1); // NEW - Add this line
        // add_action('pre_get_posts', [$this, 'filter_products_query']);
        // add_filter('query_vars', [$this, 'add_query_vars']);
        // add_action('parse_request', [$this, 'parse_custom_request']);
    }
    
    /**
     * Register our custom query var with WordPress
     */
    public function register_query_vars() {
        add_rewrite_endpoint('toursearch_custom', EP_ROOT);
    }
    
    /**
     * Add custom query vars to WordPress
     */
    public function add_query_vars($vars) {
        $vars[] = 'toursearch_custom';
        $vars[] = 'location';
        $vars[] = 'transportation';
        return $vars;
    }

    public function handle_empty_search() {
        // Check if this is our custom search
        if (!isset($_GET['toursearch_custom']) || $_GET['toursearch_custom'] != '1') {
            return;
        }
        
        // If no location AND no transportation, redirect to theme's tour search
        if (empty($_GET['location']) && empty($_GET['transportation'])) {
            $redirect_url = add_query_arg('tour_search', '1', home_url('/'));
            wp_redirect($redirect_url);
            exit;
        }
    }    
    
    /**
     * Parse custom request to prevent 404
     */
    public function parse_custom_request($wp) {
        if (isset($_GET['toursearch_custom']) && $_GET['toursearch_custom'] == '1') {
            // Tell WordPress this is a valid request
            $wp->query_vars['toursearch_custom'] = 1;
            $wp->query_vars['post_type'] = 'product';
            
            // Prevent 404
            status_header(200);
        }
    }
    
    /**
     * Filter products by location and transportation
     */
    public function filter_products_query($query) {
        // Only run on main query, frontend, and when our custom search is active
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        
        // Check if this is our custom search
        if (!isset($_GET['toursearch_custom']) || $_GET['toursearch_custom'] != '1') {
            return;
        }
        
        // Force it to be a product query
        $query->set('post_type', 'product');
        
        // IMPORTANT: Filter for TOUR products only (not regular WooCommerce products)
        // $meta_query = [
        //     [
        //         'key'     => 'product_type_phys',
        //         'value'   => 'tour_phys',
        //         'compare' => '='
        //     ]
        // ];
        // $query->set('meta_query', $meta_query);
        
        // Build tax_query array
        $tax_query = [];
        
        // Add location filter if selected
        if (!empty($_GET['location'])) {
            $tax_query[] = [
                'taxonomy' => 'location',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['location']),
            ];
        }
        
        // Add transportation filter if selected
        if (!empty($_GET['transportation'])) {
            $tax_query[] = [
                'taxonomy' => 'transportation',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET['transportation']),
            ];
        }
        
        // If both filters are set, use AND relation
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        
        // Apply tax_query if we have any filters
        if (!empty($tax_query)) {
            $query->set('tax_query', $tax_query);
        }
        
        // If no filters are set, show ALL products (tours)
        // This ensures results appear even without filters
        
        // UPDATED: Enable pagination with 12 posts per page
        // Get posts_per_page from theme settings if available
        $posts_per_page = travelwp_get_option('phys_tour_per_page');
        if (empty($posts_per_page)) {
            $posts_per_page = 12; // Default fallback
        }
        
        $query->set('posts_per_page', $posts_per_page);

        // error_log("TAX QUERY " . print_r($tax_query, true));
        
        // Handle pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $query->set('paged', $paged);
    }
}