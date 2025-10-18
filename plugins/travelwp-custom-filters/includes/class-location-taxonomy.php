<?php

class TravelWP_Location_Taxonomy {
    
    const TAXONOMY_NAME = 'location';
    const POST_TYPE = 'product';
    
    public function __construct() {
        add_action('init', [$this, 'register_taxonomy']);
        add_action('init', [$this, 'insert_default_terms']);
    }
    
    /**
     * Register hierarchical Location taxonomy
     */
    public function register_taxonomy() {
        $labels = [
            'name'              => __('Ubicaciones de Destino', 'travelwp-filters'),
            'singular_name'     => __('Ubicación de Destino', 'travelwp-filters'),
            'search_items'      => __('Buscar Ubicaciones', 'travelwp-filters'),
            'all_items'         => __('Todas las Ubicaciones', 'travelwp-filters'),
            'parent_item'       => __('País', 'travelwp-filters'),
            'parent_item_colon' => __('País:', 'travelwp-filters'),
            'edit_item'         => __('Editar Ubicación', 'travelwp-filters'),
            'update_item'       => __('Actualizar Ubicación', 'travelwp-filters'),
            'add_new_item'      => __('Agregar Nueva Ciudad', 'travelwp-filters'),
            'new_item_name'     => __('Nuevo Nombre de Ciudad', 'travelwp-filters'),
            'menu_name'         => __('Ubicaciones', 'travelwp-filters'),
        ];
        
        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'meta_box_cb'       => 'post_categories_meta_box',
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_in_rest'      => false,
            'rewrite'           => [
                'slug'       => 'location',
                'with_front' => false,
            ],
        ];
        
        register_taxonomy(self::TAXONOMY_NAME, [self::POST_TYPE], $args);
    }
    
    /**
     * Insert default countries and cities
     */
    public function insert_default_terms() {
        if (get_option('travelwp_location_terms_inserted')) {
            return;
        }
        
        $countries = [
            'es' => 'España',
            'fr' => 'Francia',
            'it' => 'Italia',
            'pt' => 'Portugal',
            'mx' => 'México',
            'ar' => 'Argentina',
            'br' => 'Brasil',
            'jp' => 'Japón',
            'au' => 'Australia',
        ];
        
        $cities_map = [
            'es' => ['Barcelona', 'Madrid', 'Sevilla', 'Valencia', 'Bilbao'],
            'fr' => ['París', 'Lyon', 'Marsella', 'Niza', 'Toulouse'],
            'it' => ['Roma', 'Milán', 'Venecia', 'Florencia', 'Nápoles'],
            'pt' => ['Lisboa', 'Oporto', 'Coímbra', 'Fátima', 'Lagos'],
            'mx' => ['Ciudad de México', 'Cancún', 'Playa del Carmen', 'Puerto Vallarta', 'Los Cabos'],
            'ar' => ['Buenos Aires', 'Mendoza', 'Córdoba', 'Salta', 'Bariloche'],
            'br' => ['São Paulo', 'Río de Janeiro', 'Salvador', 'Brasilia', 'Fortaleza'],
            'jp' => ['Tokio', 'Kioto', 'Osaka', 'Hiroshima', 'Yokohama'],
            'au' => ['Sídney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaida'],
        ];
        
        foreach ($countries as $code => $country_name) {
            $country_term = wp_insert_term($country_name, self::TAXONOMY_NAME, [
                'slug' => sanitize_title($country_name . '-' . $code),
            ]);
            
            if (is_wp_error($country_term)) {
                $country_id = get_term_by('name', $country_name, self::TAXONOMY_NAME)->term_id;
            } else {
                $country_id = $country_term['term_id'];
            }
            
            if (isset($cities_map[$code])) {
                foreach ($cities_map[$code] as $city_name) {
                    $city_slug = sanitize_title($city_name . '-' . $country_name);
                    
                    wp_insert_term($city_name, self::TAXONOMY_NAME, [
                        'parent' => $country_id,
                        'slug' => $city_slug,
                    ]);
                }
            }
        }
        
        update_option('travelwp_location_terms_inserted', true);
    }
}