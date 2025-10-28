<?php

class TravelWP_Location_Taxonomy {
    
    const TAXONOMY_NAME = 'location';
    const POST_TYPE = 'product';
    
    public function __construct() {
        add_action('init', [$this, 'register_taxonomy']);
        add_action('init', [$this, 'insert_default_terms']);
        
        // Remove WooCommerce's default taxonomy meta box
        add_action('add_meta_boxes', [$this, 'remove_woocommerce_meta_box'], 99);
        
        // Add our custom meta box
        add_action('add_meta_boxes', [$this, 'add_custom_meta_box'], 100);
        
        // Enqueue scripts for auto-select parent/child
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
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
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'public'            => true,
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
     * Remove WooCommerce's automatic meta box
     */
    public function remove_woocommerce_meta_box() {
        global $wp_meta_boxes;
        
        // Remove all possible variations
        remove_meta_box('location_div', self::POST_TYPE, 'side');
        remove_meta_box('locationdiv', self::POST_TYPE, 'side');
        remove_meta_box('tagsdiv-location', self::POST_TYPE, 'side');
        
        // Also check in normal position
        remove_meta_box('location_div', self::POST_TYPE, 'normal');
        remove_meta_box('locationdiv', self::POST_TYPE, 'normal');
        remove_meta_box('tagsdiv-location', self::POST_TYPE, 'normal');
        
        // Debug: uncomment to see what meta boxes exist
        // error_log('Meta boxes: ' . print_r($wp_meta_boxes[self::POST_TYPE], true));
    }
    
    /**
     * Add custom meta box
     */
    public function add_custom_meta_box() {
        add_meta_box(
            'travelwp_location_meta_box',
            __('Ubicaciones de Destino', 'travelwp-filters'),
            [$this, 'location_meta_box'],
            self::POST_TYPE,
            'side',
            'default'
        );
    }
    
    /**
     * Custom meta box with proper hierarchical ordering
     */
    public function location_meta_box($post, $box) {
        $defaults = ['taxonomy' => self::TAXONOMY_NAME];
        
        if (!isset($box['args']) || !is_array($box['args'])) {
            $args = [];
        } else {
            $args = $box['args'];
        }
        
        $r = wp_parse_args($args, $defaults);
        $tax_name = esc_attr($r['taxonomy']);
        $taxonomy = get_taxonomy($r['taxonomy']);
        ?>
        <div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
            <ul id="<?php echo $tax_name; ?>-tabs" class="category-tabs">
                <li class="tabs"><a href="#<?php echo $tax_name; ?>-all"><?php echo $taxonomy->labels->all_items; ?></a></li>
            </ul>

            <div id="<?php echo $tax_name; ?>-all" class="tabs-panel">
                <ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
                    <?php
                    wp_terms_checklist($post->ID, [
                        'taxonomy'      => $tax_name,
                        'checked_ontop' => false,
                    ]);
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }
    


    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        global $post;
        
        // Only load on product edit screen
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }
        
        if (!$post || $post->post_type !== self::POST_TYPE) {
            return;
        }
        
        // Add inline script
        add_action('admin_footer', [$this, 'add_inline_script']);
    }
    
    /**
     * Add inline jQuery script for auto-select parent/child
     */
    public function add_inline_script() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var taxonomyName = '<?php echo self::TAXONOMY_NAME; ?>';
            var checklistSelector = '#' + taxonomyName + 'checklist';
            
            // Function to check/uncheck parent when child changes
            function handleChildChange(checkbox) {
                var $checkbox = $(checkbox);
                var $li = $checkbox.closest('li');
                var $parentLi = $li.parent().closest('li');
                
                if ($parentLi.length > 0) {
                    var $parentCheckbox = $parentLi.children('label').find('input[type="checkbox"]');
                    
                    if ($checkbox.is(':checked')) {
                        // When child is checked, check parent
                        $parentCheckbox.prop('checked', true);
                        // Recursively check grandparents
                        handleChildChange($parentCheckbox);
                    } else {
                        // When child is unchecked, check if any sibling is still checked
                        var $siblings = $li.siblings('li');
                        var anySiblingChecked = false;
                        
                        $siblings.each(function() {
                            var $siblingCheckbox = $(this).children('label').find('input[type="checkbox"]');
                            if ($siblingCheckbox.is(':checked')) {
                                anySiblingChecked = true;
                                return false; // break loop
                            }
                        });
                        
                        // If no siblings are checked, uncheck parent
                        if (!anySiblingChecked) {
                            $parentCheckbox.prop('checked', false);
                            // Recursively uncheck grandparents
                            handleChildChange($parentCheckbox);
                        }
                    }
                }
            }
            
            // Function to check/uncheck all children when parent changes
            function handleParentChange(checkbox) {
                var $checkbox = $(checkbox);
                var $li = $checkbox.closest('li');
                var $childrenUl = $li.children('ul.children');
                
                if ($childrenUl.length > 0) {
                    var isChecked = $checkbox.is(':checked');
                    
                    // Check/uncheck all direct children
                    $childrenUl.find('> li > label > input[type="checkbox"]').each(function() {
                        $(this).prop('checked', isChecked);
                        // Recursively handle grandchildren
                        handleParentChange(this);
                    });
                }
            }
            
            // Attach event listener to all checkboxes
            $(document).on('change', checklistSelector + ' input[type="checkbox"]', function() {
                // Handle parent selection
                handleChildChange(this);
                // Handle children selection
                handleParentChange(this);
            });
        });
        </script>
        <?php
    }



    /**
     * Insert default countries and cities
     */
    public function insert_default_terms() {

        //     if (get_option('travelwp_location_terms_inserted')) {
        //         return;
        //     }
            
        //     $countries = [
        //         'es' => 'España',
        //         'fr' => 'Francia',
        //         'it' => 'Italia',
        //         'pt' => 'Portugal',
        //         'mx' => 'México',
        //         'ar' => 'Argentina',
        //         'br' => 'Brasil',
        //         'jp' => 'Japón',
        //         'au' => 'Australia',
        //     ];
            
        //     $cities_map = [
        //         'es' => ['Barcelona', 'Madrid', 'Sevilla', 'Valencia', 'Bilbao'],
        //         'fr' => ['París', 'Lyon', 'Marsella', 'Niza', 'Toulouse'],
        //         'it' => ['Roma', 'Milán', 'Venecia', 'Florencia', 'Nápoles'],
        //         'pt' => ['Lisboa', 'Oporto', 'Coímbra', 'Fátima', 'Lagos'],
        //         'mx' => ['Ciudad de México', 'Cancún', 'Playa del Carmen', 'Puerto Vallarta', 'Los Cabos'],
        //         'ar' => ['Buenos Aires', 'Mendoza', 'Córdoba', 'Salta', 'Bariloche'],
        //         'br' => ['São Paulo', 'Río de Janeiro', 'Salvador', 'Brasilia', 'Fortaleza'],
        //         'jp' => ['Tokio', 'Kioto', 'Osaka', 'Hiroshima', 'Yokohama'],
        //         'au' => ['Sídney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaida'],
        //     ];
            
        //     foreach ($countries as $code => $country_name) {
        //         $country_term = wp_insert_term($country_name, self::TAXONOMY_NAME, [
        //             'slug' => sanitize_title($country_name . '-' . $code),
        //         ]);
                
        //         if (is_wp_error($country_term)) {
        //             $country_id = get_term_by('name', $country_name, self::TAXONOMY_NAME)->term_id;
        //         } else {
        //             $country_id = $country_term['term_id'];
        //         }
                
        //         if (isset($cities_map[$code])) {
        //             foreach ($cities_map[$code] as $city_name) {
        //                 $city_slug = sanitize_title($city_name . '-' . $country_name);
                        
        //                 wp_insert_term($city_name, self::TAXONOMY_NAME, [
        //                     'parent' => $country_id,
        //                     'slug' => $city_slug,
        //                 ]);
        //             }
        //         }
        //     }
            
        //     update_option('travelwp_location_terms_inserted', true);
        // }

    }

}