<?php

/**
 * Admin functionality
 */
class TravelWP_Admin {
    
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_taxonomy_metaboxes']);
    }
    
    /**
     * Add taxonomy metaboxes to product editor
     */
    public function add_taxonomy_metaboxes() {
        add_meta_box(
            'location_div',
            __('Ubicaciones de Destino', 'travelwp-filters'),
            'post_categories_meta_box',
            'product',
            'normal',
            'default',
            ['taxonomy' => 'location']
        );
        
        add_meta_box(
            'transportation_div',
            __('Transporte', 'travelwp-filters'),
            'post_categories_meta_box',
            'product',
            'normal',
            'default',
            ['taxonomy' => 'transportation']
        );
    }
}
