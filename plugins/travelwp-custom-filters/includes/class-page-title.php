<?php
/**
 * Page Title Handler
 * Customizes page titles for tour search results with enhanced UX
 */
class TravelWP_Page_Title {
    
    public function __construct() {
        add_action('init', [$this, 'override_theme_title_filter'], 20);
        add_action('wp_head', [$this, 'add_custom_title_styles']);
    }
    
    /**
     * Override theme's page title filter
     */
    public function override_theme_title_filter() {
        // Remove original filter if it exists
        remove_filter('thim-ekit/widgets/page-title', 'travelwp_add_page_title_tour');
        
        // Add our custom filter
        add_filter('thim-ekit/widgets/page-title', [$this, 'custom_tour_search_title']);
    }
    
    /**
     * Add custom styles for enhanced title display
     */
    public function add_custom_title_styles() {
        if (!isset($_GET['tour_search']) || !$this->has_custom_filters()) {
            return;
        }
        ?>
        <style>
            .travelwp-search-title-wrapper {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 8px;
            }
            
            .travelwp-search-title-main {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 32px;
                font-weight: 700;
                color: #1a1a1a;
                line-height: 1.2;
            }
            
            .travelwp-search-title-main .material-icons {
                font-size: 36px;
                color: #0066cc;
            }
            
            .travelwp-search-filters {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: center;
            }
            
            .travelwp-filter-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
                color: white;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 102, 204, 0.25);
                transition: all 0.3s ease;
            }
            
            .travelwp-filter-badge:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 102, 204, 0.35);
            }
            
            .travelwp-filter-badge .material-icons {
                font-size: 18px;
            }
            
            .travelwp-filter-badge-location {
                background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
                box-shadow: 0 2px 8px rgba(233, 30, 99, 0.25);
            }
            
            .travelwp-filter-badge-location:hover {
                box-shadow: 0 4px 12px rgba(233, 30, 99, 0.35);
            }
            
            .travelwp-filter-badge-transport {
                background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
                box-shadow: 0 2px 8px rgba(0, 188, 212, 0.25);
            }
            
            .travelwp-filter-badge-transport:hover {
                box-shadow: 0 4px 12px rgba(0, 188, 212, 0.35);
            }
            
            .travelwp-results-count {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                background: #f5f5f5;
                color: #666;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 500;
            }
            
            .travelwp-results-count .material-icons {
                font-size: 16px;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .travelwp-search-title-main {
                    font-size: 24px;
                }
                
                .travelwp-search-title-main .material-icons {
                    font-size: 28px;
                }
                
                .travelwp-filter-badge {
                    font-size: 13px;
                    padding: 6px 12px;
                }
            }
        </style>
        <?php
    }
    
    /**
     * Custom title for tour search pages
     */
    public function custom_tour_search_title($heading_title) {
        if (class_exists('TravelBookingPhyscode')) {
            // Handle tour archive pages
            if (is_post_type_archive('product') || is_page(wc_get_page_id('shop'))) {
                if (\TravelPhysUtility::check_is_tour_archive()) {
                    // Check if it's a tour search with our custom filters
                    if (isset($_GET['tour_search']) && $this->has_custom_filters()) {
                        $heading_title = $this->build_enhanced_search_title();
                    } else {
                        $heading_title = esc_html('Tours', 'travelwp');
                    }
                }
            }
            
            // Handle taxonomy pages
            if (is_product_taxonomy() && \TravelPhysUtility::check_is_tour_archive()) {
                $term = get_queried_object();
                $heading_title = $term->name;
            }
        }

        return $heading_title;
    }
    
    /**
     * Check if custom filters are present
     */
    private function has_custom_filters() {
        $location = get_query_var('location');
        $transportation = get_query_var('transportation');
        
        return (!empty($location) && $location != '0') || 
               (!empty($transportation) && $transportation != '0');
    }
    
    /**
     * Build enhanced search title with Material Design elements
     */
    private function build_enhanced_search_title() {
        $location = get_query_var('location');
        $transportation = get_query_var('transportation');
        
        $output = '<div class="travelwp-search-title-wrapper">';
        
        // Main title with icon
        $output .= '<div class="travelwp-search-title-main">';
        $output .= '<span class="material-icons">travel_explore</span>';
        $output .= '<span>' . __('Paquetes encontrados', 'travelwp-filters') . '</span>';
        $output .= '</div>';
        
        // Filter badges
        $output .= '<div class="travelwp-search-filters">';
        
        // Location badge
        if ($location && $location != '0') {
            $term = get_term_by('slug', $location, 'location');
            if ($term && !is_wp_error($term)) {
                $output .= '<span class="travelwp-filter-badge travelwp-filter-badge-location">';
                $output .= '<span class="material-icons">place</span>';
                $output .= '<span>' . esc_html($term->name) . '</span>';
                $output .= '</span>';
            }
        }
        
        // Transportation badge
        if ($transportation && $transportation != '0') {
            $term = get_term_by('slug', $transportation, 'transportation');
            if ($term && !is_wp_error($term)) {
                $output .= '<span class="travelwp-filter-badge travelwp-filter-badge-transport">';
                $output .= '<span class="material-icons">directions_bus</span>';
                $output .= '<span>' . esc_html($term->name) . '</span>';
                $output .= '</span>';
            }
        }
        
        // Results count (if we can get it)
        global $wp_query;
        if ($wp_query && isset($wp_query->found_posts)) {
            $output .= '<span class="travelwp-results-count">';
            $output .= '<span class="material-icons">assignment</span>';
            $output .= '<span>' . sprintf(_n('%s resultado', '%s resultados', $wp_query->found_posts, 'travelwp-filters'), number_format_i18n($wp_query->found_posts)) . '</span>';
            $output .= '</span>';
        }
        
        $output .= '</div>'; // Close filters
        $output .= '</div>'; // Close wrapper
        
        return $output;
    }
}