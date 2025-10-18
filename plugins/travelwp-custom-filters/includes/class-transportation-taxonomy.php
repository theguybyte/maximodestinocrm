<?php

class TravelWP_Transportation_Taxonomy {
    
    const TAXONOMY_NAME = 'transportation';
    const POST_TYPE = 'product';
    
    public function __construct() {
        add_action('init', [$this, 'register_taxonomy']);
        add_action('init', [$this, 'insert_default_terms']);
    }
    
    /**
     * Register Transportation taxonomy
     */
    public function register_taxonomy() {
        $labels = [
            'name'              => __('Transporte', 'travelwp-filters'),
            'singular_name'     => __('Transporte', 'travelwp-filters'),
            'search_items'      => __('Buscar Transporte', 'travelwp-filters'),
            'all_items'         => __('Todos los Transportes', 'travelwp-filters'),
            'edit_item'         => __('Editar Transporte', 'travelwp-filters'),
            'update_item'       => __('Actualizar Transporte', 'travelwp-filters'),
            'add_new_item'      => __('Agregar Nuevo Transporte', 'travelwp-filters'),
            'new_item_name'     => __('Nuevo Nombre de Transporte', 'travelwp-filters'),
            'menu_name'         => __('Transporte', 'travelwp-filters'),
        ];
        
        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_in_rest'      => true,
            'rewrite'           => [
                'slug'       => 'transportation',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy(self::TAXONOMY_NAME, [self::POST_TYPE], $args);
    }
    
    /**
     * Insert default transportation types
     */
    public function insert_default_terms() {
        if (get_option('travelwp_transportation_terms_inserted')) {
            return;
        }
        
        $transportations = [
            'avion' => 'Avión',
            'autobus' => 'Autobús',
            'tren' => 'Tren',
            'crucero' => 'Crucero',
            'auto' => 'Auto Alquilado',
            'helicoptero' => 'Helicóptero',
        ];
        
        foreach ($transportations as $slug => $name) {
            wp_insert_term($name, self::TAXONOMY_NAME, [
                'slug' => $slug,
            ]);
        }
        
        update_option('travelwp_transportation_terms_inserted', true);
    }
}