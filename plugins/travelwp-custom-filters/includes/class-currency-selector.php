<?php
/**
 * Currency Selector for Tour Products
 * Integrates with TravelWP's tour booking system
 */
class TravelWP_Currency_Selector {
    
    const META_KEY = '_product_currency';
    const DEFAULT_CURRENCY = 'ARS';
    
    private $currencies = [
        'ARS' => [
            'name' => 'Peso Argentino',
            'symbol' => '$',
            'format' => '%s %s', // $ 100
        ],
        'USD' => [
            'name' => 'Dólar Estadounidense',
            'symbol' => '$',
            'format' => '%s %s', // USD 100
        ],
    ];
    
    public function __construct() {
        // Add currency field to tour booking tab using TravelWP's filter system
        add_filter('fields_tab_tour_booking', [$this, 'add_currency_to_tour_fields'], 5);
        
        // Save currency selection (TravelWP saves all fields automatically, but we add extra handling)
        add_action('save_post_product', [$this, 'save_currency_field'], 10, 2);
        
        // Modify price display on frontend for TOUR products
        add_filter('woocommerce_get_price_html', [$this, 'modify_price_display'], 99, 2);

        // Override WooCommerce price getters for tour products
        // add_filter('woocommerce_product_get_regular_price', [$this, 'override_regular_price'], 10, 2);
        // add_filter('woocommerce_product_get_sale_price', [$this, 'override_sale_price'], 10, 2);
        // add_filter('woocommerce_product_get_price', [$this, 'override_regular_price'], 10, 2);
        
        // Filter wc_price() output for tour products
        add_filter('wc_price', [$this, 'filter_wc_price'], 10, 3);        
        
        // Add custom CSS for admin
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);


        // add_filter('wc_price', [$this, 'filter_wc_price'], 10, 3);

    }
    
    /**
     * Add currency field to TravelWP's tour booking fields array
     */
    public function add_currency_to_tour_fields($fields) {
        // Get current currency (for editing existing products)
        global $post;
        $selected_currency = '';
        if ($post && $post->ID) {
            $selected_currency = get_post_meta($post->ID, self::META_KEY, true);
            if (empty($selected_currency)) {
                $selected_currency = self::DEFAULT_CURRENCY;
            }
        }
        
        // Build currency options array in TravelWP format
        $currency_options = [];
        foreach ($this->currencies as $code => $data) {
            $currency_options[$code] = $data['name'] . ' (' . $code . ')';
        }
        
        // Create new fields array with currency at the top
        $new_fields = [
            self::META_KEY => [
                'label'   => __('Moneda del Tour', 'travelwp-filters'),
                'type'    => 'select_array_key_val',
                'default' => $selected_currency,
                'options' => $currency_options,
                'description' => __('Selecciona la moneda en la que se mostrará el precio de este tour', 'travelwp-filters'),
            ]
        ];
        
        // Merge with existing fields (currency appears first)
        return array_merge($new_fields, $fields);
    }
    
    /**
     * Save currency field
     */
    public function save_currency_field($post_id, $post) {
        // Check if it's an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check if it's a product
        if ($post->post_type !== 'product') {
            return;
        }
        
        // Save currency if present
        if (isset($_POST[self::META_KEY])) {
            $currency = sanitize_text_field($_POST[self::META_KEY]);
            
            // Validate currency
            if (array_key_exists($currency, $this->currencies)) {
                update_post_meta($post_id, self::META_KEY, $currency);
            }
        }
    }
    
    /**
     * Modify price display on frontend for TOUR products
     */
    public function modify_price_display($price_html, $product) {
        if (empty($price_html)) {
            return $price_html;
        }
        
        // Check if this is a tour product - USE WOOCOMMERCE METHOD
        if ($product->get_type() !== 'tour_phys') {
            return $price_html; // Not a tour, return original
        }
        
        // Get product currency
        $currency_code = get_post_meta($product->get_id(), self::META_KEY, true);
        if (empty($currency_code)) {
            $currency_code = self::DEFAULT_CURRENCY;
        }
        
        // Get currency data
        if (!isset($this->currencies[$currency_code])) {
            return $price_html;
        }
        
        $currency = $this->currencies[$currency_code];
        
        // Get tour prices - ADD UNDERSCORE PREFIX
        $regular_price = get_post_meta($product->get_id(), '_phys_tour_regular_price', true);
        $sale_price = get_post_meta($product->get_id(), '_phys_tour_sale_price', true);
        
        // If no tour prices, return original
        if (empty($regular_price)) {
            return $price_html;
        }
        
        // Format based on currency
        if ($currency_code === 'USD') {
            // USD 100 format
            $price_html = $this->format_usd_price($regular_price, $sale_price);
        } else {
            // $ 100 format (ARS and others)
            $price_html = $this->format_ars_price($regular_price, $sale_price, $currency['symbol']);
        }
        
        return $price_html;
    }
    
    /**
     * Format USD prices (USD 1,000,000.00)
     */
    private function format_usd_price($regular_price, $sale_price) {
        // USD uses comma for thousands, dot for decimals
        $formatted_regular = 'USD ' . number_format((float)$regular_price, 2, '.', ',');
        
        if (!empty($sale_price) && $sale_price < $regular_price) {
            // Product on sale
            $formatted_sale = 'USD ' . number_format((float)$sale_price, 2, '.', ',');
            
            return '<del><span class="woocommerce-Price-amount amount">' . $formatted_regular . '</span></del> ' .
                '<ins><span class="woocommerce-Price-amount amount">' . $formatted_sale . '</span></ins>';
        } else {
            // Regular price only
            return '<span class="woocommerce-Price-amount amount">' . $formatted_regular . '</span>';
        }
    }

    /**
     * Format ARS prices ($ 1.000.000,00)
     */
    private function format_ars_price($regular_price, $sale_price, $symbol) {
        // ARS uses dot for thousands, comma for decimals
        $formatted_regular = $symbol . ' ' . number_format((float)$regular_price, 2, ',', '.');
        
        if (!empty($sale_price) && $sale_price < $regular_price) {
            // Product on sale
            $formatted_sale = $symbol . ' ' . number_format((float)$sale_price, 2, ',', '.');
            
            return '<del><span class="woocommerce-Price-amount amount">' . $formatted_regular . '</span></del> ' .
                '<ins><span class="woocommerce-Price-amount amount">' . $formatted_sale . '</span></ins>';
        } else {
            // Regular price only
            return '<span class="woocommerce-Price-amount amount">' . $formatted_regular . '</span>';
        }
    }
    
    /**
     * Add custom admin styles
     */
    public function enqueue_admin_assets($hook) {
        wp_enqueue_style(
            'travelwp-currency-admin',
            TRAVELWP_FILTERS_URL . 'assets/css/admin-style.css',
            array(),
            TRAVELWP_FILTERS_VERSION
        );
    }
    
    /**
     * Get product currency code
     * Useful helper function for other parts of the plugin
     */
    public static function get_product_currency($product_id) {
        $currency = get_post_meta($product_id, self::META_KEY, true);
        return !empty($currency) ? $currency : self::DEFAULT_CURRENCY;
    }
    
    /**
     * Get currency symbol for a product
     */
    public static function get_product_currency_symbol($product_id) {
        $instance = new self();
        $currency_code = self::get_product_currency($product_id);
        
        if (isset($instance->currencies[$currency_code])) {
            return $instance->currencies[$currency_code]['symbol'];
        }
        
        return '$';
    }
    
    /**
     * Get currency name for a product
     */
    public static function get_product_currency_name($product_id) {
        $instance = new self();
        $currency_code = self::get_product_currency($product_id);
        
        if (isset($instance->currencies[$currency_code])) {
            return $instance->currencies[$currency_code]['name'];
        }
        
        return 'Peso Argentino';
    }
    
    /**
     * Get formatted currency prefix (for display)
     * Returns "USD" for USD, "$" for ARS
     */
    public static function get_currency_prefix($product_id) {
        $currency_code = self::get_product_currency($product_id);
        
        if ($currency_code === 'USD') {
            return 'USD';
        }
        
        return '$';
    }


    /**
     * Filter wc_price output for tour products
     */
    public function filter_wc_price($return, $price, $args) {
        // Only filter if we're in a tour product context
        global $product;
        
        if (!$product || $product->get_type() !== 'tour_phys') {
            return $return;
        }
        
        // Get product currency
        $currency_code = get_post_meta($product->get_id(), self::META_KEY, true);
        if (empty($currency_code)) {
            $currency_code = self::DEFAULT_CURRENCY;
        }
        
        // Get currency data
        if (!isset($this->currencies[$currency_code])) {
            return $return;
        }
        
        $currency = $this->currencies[$currency_code];

        error_log("PRICE DEBUG: {$price} | CODE: {$currency_code}");
        
        // Format based on currency
        // if ($currency_code === 'USD') {
        //     $formatted_price = 'USD ' . number_format((float)$price, 0, '', '.');
        // } else {
        //     $formatted_price = $currency['symbol'] . ' ' . number_format((float)$price, 0, '', '.');
        // }
        if ($currency_code === 'USD') {
            $formatted_price = 'USD ' . str_replace(',', '.', $price);
        } else {
            $formatted_price = $currency['symbol'] . ' ' . str_replace(',', '.', $price);
        } 
        
        // Return formatted price wrapped in WooCommerce price span
        return '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';
    }


    /**
     * Override regular price for tour products
     */
    public function override_regular_price($price, $product) {
        if ($product->get_type() !== 'tour_phys') {
            return $price;
        }
        
        $tour_price = get_post_meta($product->get_id(), '_phys_tour_regular_price', true);
        return !empty($tour_price) ? $tour_price : $price;
    }

    /**
     * Override sale price for tour products
     */
    public function override_sale_price($price, $product) {
        if ($product->get_type() !== 'tour_phys') {
            return $price;
        }
        
        $tour_sale_price = get_post_meta($product->get_id(), '_phys_tour_sale_price', true);
        return !empty($tour_sale_price) ? $tour_sale_price : $price;
    }



}