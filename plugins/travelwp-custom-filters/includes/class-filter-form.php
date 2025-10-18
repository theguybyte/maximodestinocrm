<?php

class TravelWP_Filter_Form {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('travelwp_filter_form', [$this, 'render_form']);
    }
    
    /**
     * Enqueue Select2 and custom scripts
     */
    public function enqueue_assets() {
        // Material Icons
        wp_enqueue_style(
            'material-icons',
            'https://fonts.googleapis.com/icon?family=Material+Icons',
            [],
            null
        );
        
        // Select2 CSS
        wp_enqueue_style(
            'select2',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            [],
            '4.1.0'
        );
        
        // Custom CSS
        wp_enqueue_style(
            'travelwp-filter-form',
            TRAVELWP_FILTERS_URL . 'assets/css/filter-form.css',
            ['select2', 'material-icons'],
            TRAVELWP_FILTERS_VERSION
        );
        
        // Lottie Player JS
        wp_enqueue_script(
            'lottie-player',
            'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js',
            [],
            '5.12.2',
            true
        );
        
        // Select2 JS
        wp_enqueue_script(
            'select2',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ['jquery'],
            '4.1.0',
            true
        );
        
        // Custom JS
        wp_enqueue_script(
            'travelwp-filter-form',
            TRAVELWP_FILTERS_URL . 'assets/js/filter-form.js',
            ['jquery', 'select2', 'lottie-player'],
            TRAVELWP_FILTERS_VERSION,
            true
        );
        
        // Pass data to JavaScript
        wp_localize_script('travelwp-filter-form', 'travelwpFilterData', [
            'locations' => $this->get_all_locations(),
            'transportations' => $this->get_all_transportations(),
            'strings' => [
                'selectDestination' => __('Selecciona un destino...', 'travelwp-filters'),
                'selectTransportation' => __('Selecciona transporte...', 'travelwp-filters'),
                'anyDestination' => __('Cualquier destino', 'travelwp-filters'),
                'anyTransportation' => __('Cualquier transporte', 'travelwp-filters'),
            ]
        ]);
    }
    
    /**
     * Get all locations organized by country
     */
    private function get_all_locations() {
        $terms = get_terms([
            'taxonomy' => 'location',
            'hide_empty' => true,
        ]);
        
        $countries = [];
        $cities = [];
        
        foreach ($terms as $term) {
            if ($term->parent == 0) {
                $countries[$term->term_id] = $term;
            } else {
                if (!isset($cities[$term->parent])) {
                    $cities[$term->parent] = [];
                }
                $cities[$term->parent][] = $term;
            }
        }
        
        // Sort countries alphabetically
        usort($countries, function($a, $b) {
            return strcmp($a->name, $b->name);
        });
        
        $results = [];
        
        foreach ($countries as $country) {
            $results[] = [
                'id' => $country->slug,
                'text' => $country->name,
            ];
            
            if (isset($cities[$country->term_id])) {
                usort($cities[$country->term_id], function($a, $b) {
                    return strcmp($a->name, $b->name);
                });
                
                foreach ($cities[$country->term_id] as $city) {
                    $results[] = [
                        'id' => $city->slug,
                        'text' => '  — ' . $city->name,
                    ];
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Get all transportation types
     */
    private function get_all_transportations() {
        $terms = get_terms([
            'taxonomy' => 'transportation',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);
        
        $results = [];
        
        foreach ($terms as $term) {
            $results[] = [
                'id' => $term->slug,
                'text' => $term->name,
            ];
        }
        
        return $results;
    }
    
    /**
     * Render filter form shortcode
     */
    public function render_form($atts) {
        ob_start();
        ?>
        <div class="travelwp-filter-form">
            <form id="travelwp-search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="hidden" name="toursearch_custom" value="1">
                
                <div class="filter-row">
                    <div class="filter-col">
                        <label for="location-select">
                            <span class="material-icons">place</span>
                            <?php _e('Destino', 'travelwp-filters'); ?>
                        </label>
                        <select id="location-select" name="location" class="travelwp-select2">
                            <!-- Options populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="filter-col">
                        <label for="transportation-select">
                            <span class="material-icons">directions_bus</span>
                            <?php _e('Transporte', 'travelwp-filters'); ?>
                        </label>
                        <select id="transportation-select" name="transportation" class="travelwp-select2">
                            <!-- Options populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="filter-col filter-btn-col">
                        <button type="submit" class="filter-search-btn">
                            <span class="material-icons">search</span>
                            <?php _e('Buscar', 'travelwp-filters'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * AJAX: Get locations with tours
     */
    public function ajax_get_locations() {
        $search = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
        
        // Get all location terms that have products
        $terms = get_terms([
            'taxonomy' => 'location',
            'hide_empty' => true,
            'search' => $search,
        ]);
        
        // Organize by country
        $countries = [];
        $cities = [];
        
        foreach ($terms as $term) {
            if ($term->parent == 0) {
                // It's a country (parent)
                $countries[$term->term_id] = $term;
            } else {
                // It's a city (child)
                if (!isset($cities[$term->parent])) {
                    $cities[$term->parent] = [];
                }
                $cities[$term->parent][] = $term;
            }
        }
        
        // Sort countries alphabetically
        usort($countries, function($a, $b) {
            return strcmp($a->name, $b->name);
        });
        
        // Build results array with hierarchy
        $results = [];
        
        foreach ($countries as $country) {
            // Add country itself as selectable
            $results[] = [
                'id' => $country->slug,
                'text' => $country->name,
            ];
            
            // Add cities under this country
            if (isset($cities[$country->term_id])) {
                // Sort cities alphabetically
                usort($cities[$country->term_id], function($a, $b) {
                    return strcmp($a->name, $b->name);
                });
                
                foreach ($cities[$country->term_id] as $city) {
                    $results[] = [
                        'id' => $city->slug,
                        'text' => '  — ' . $city->name,
                    ];
                }
            }
        }
        
        wp_send_json(['results' => $results]);
    }
    
    /**
     * AJAX: Get transportation types with tours
     */
    public function ajax_get_transportations() {
        $search = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
        
        $terms = get_terms([
            'taxonomy' => 'transportation',
            'hide_empty' => true,
            'search' => $search,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);
        
        $results = [];
        
        foreach ($terms as $term) {
            $results[] = [
                'id' => $term->slug,
                'text' => $term->name,
            ];
        }
        
        wp_send_json(['results' => $results]);
    }
}