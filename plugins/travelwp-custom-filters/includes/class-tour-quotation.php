<?php
/**
 * Tour Quotation
 * Displays currency quotation from /cotizacion/ page via AJAX
 */
class TravelWP_Tour_Quotation {
    
    public function __construct() {
        // Register quotation shortcode
        add_shortcode('tour_quotation', [$this, 'render_quotation']);
        
        // Add inline styles
        add_action('wp_head', [$this, 'add_inline_styles']);
        
        // Add inline scripts
        add_action('wp_footer', [$this, 'add_inline_scripts']);
        
        // Register AJAX actions for quotation
        add_action('wp_ajax_get_quotation_data', [$this, 'ajax_get_quotation_data']);
        add_action('wp_ajax_nopriv_get_quotation_data', [$this, 'ajax_get_quotation_data']);
    }
    
    /**
     * AJAX handler to get quotation data
     */
    public function ajax_get_quotation_data() {
        // Verify nonce
        check_ajax_referer('travelwp_quotation_nonce', 'nonce');
        
        // Get the /cotizacion/ page
        $page = get_page_by_path('cotizacion');
        
        if (!$page) {
            wp_send_json_error([
                'message' => 'Página de cotización no encontrada'
            ]);
        }
        
        // Get ACF fields from the page
        $date_quotation = get_field('date_quotation', $page->ID);
        $value_quotation = get_field('value_quotation', $page->ID);
        
        // Validate data
        if (empty($date_quotation)) {
            wp_send_json_error([
                'message' => 'Datos de cotización no disponibles'
            ]);
        }
        
        // Format the date (assuming it comes as d/m/Y or Y-m-d)
        $formatted_date = $this->format_quotation_date($date_quotation);
        
        // Format the value with proper number formatting
        // $formatted_value = $this->format_quotation_value($value_quotation);
        
        // Build the quotation text
        $quotation_text = sprintf(
            'Cotización al día %s - 1U$D = $%s',
            $formatted_date,
            $value_quotation
        );
        
        // Return success response
        wp_send_json_success([
            'text' => $quotation_text,
            'date' => $formatted_date,
            'value' => $formatted_value,
            'raw_date' => $date_quotation,
            'raw_value' => $value_quotation
        ]);
    }
    
    /**
     * Format quotation date
     */
    private function format_quotation_date($date) {
        // If it's already in the correct format, return it
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            return $date;
        }
        
        // Try to parse different date formats
        $timestamp = strtotime($date);
        
        if ($timestamp) {
            return date('d/m/Y', $timestamp);
        }
        
        return $date;
    }
    
    /**
     * Format quotation value with thousands separator
     */
    private function format_quotation_value($value) {
        // Remove any existing formatting
        $clean_value = preg_replace('/[^0-9.,]/', '', $value);
        
        // Convert to float
        $float_value = floatval(str_replace(',', '.', $clean_value));
        
        // Format with comma as decimal separator and dot as thousands separator
        return number_format($float_value, 2, ',', '.');
    }
    
    /**
     * Add inline CSS for quotation
     */
    public function add_inline_styles() {
        ?>
        <style>
        /* Quotation text styling */
        .travelwp-quotation-text {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #001d3a 0%, #07437f 100%);
            color: white;
            padding: 7px 18px;
            border-radius: 24px;
            font-family: "Urbanist", Sans-serif;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .travelwp-quotation-text:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .travelwp-quotation-text .material-icons {
            font-size: 22px;
            color: white;
        }
        
        .travelwp-quotation-loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            font-family: "Urbanist", Sans-serif;
            font-size: 17px;
            padding: 12px 20px;
        }
        
        .travelwp-quotation-loading .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #0066cc;
            border-radius: 50%;
            animation: travelwp-quotation-spin 1s linear infinite;
            display: block;
            flex-shrink: 0;
        }
        
        @keyframes travelwp-quotation-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .travelwp-quotation-error {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #dc3545;
            font-family: "Urbanist", Sans-serif;
            font-size: 14px;
            padding: 12px 20px;
        }
        
        .travelwp-quotation-error .material-icons {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .travelwp-quotation-text {
                font-size: 13px;
                padding: 10px 16px;
            }
            
            .travelwp-quotation-text .material-icons {
                font-size: 20px;
            }
            
            .travelwp-quotation-loading {
                font-size: 13px;
                padding: 10px 16px;
            }
            
            .travelwp-quotation-loading .spinner {
                width: 16px;
                height: 16px;
                border-width: 2px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Add inline JavaScript for quotation AJAX
     */
    public function add_inline_scripts() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            
            // Function to fetch and display quotation
            function loadQuotation() {
                const $container = $('#travelwp-quotation-container');
                
                if ($container.length === 0) {
                    return; // Container doesn't exist on this page
                }
                
                // Show loading state
                $container.html(`
                    <span class="travelwp-quotation-loading">
                        <span class="spinner"></span>
                        <span>Cargando cotización...</span>
                    </span>
                `);
                
                // Make AJAX request
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'get_quotation_data',
                        nonce: '<?php echo wp_create_nonce('travelwp_quotation_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Display the quotation text
                            $container.html(`
                                <span class="travelwp-quotation-text">
                                    <span>${response.data.text}</span>
                                </span>
                            `);
                        } else {
                            // Show error message
                            $container.html(`
                                <span class="travelwp-quotation-error">
                                    <span class="material-icons">error_outline</span>
                                    <span>${response.data.message || 'Error al cargar la cotización'}</span>
                                </span>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        $container.html(`
                            <span class="travelwp-quotation-error">
                                <span class="material-icons">error_outline</span>
                                <span>Error de conexión</span>
                            </span>
                        `);
                    }
                });
            }
            
            // Load quotation on page load
            loadQuotation();
            
            // Optional: Refresh quotation every 5 minutes (300000ms)
            // setInterval(loadQuotation, 300000);
        });
        </script>
        <?php
    }
    
    /**
     * Render quotation shortcode
     */
    public function render_quotation($atts) {
        ob_start();
        ?>
        <div id="travelwp-quotation-container">
            <span class="travelwp-quotation-loading">
                <span class="spinner"></span>
                <span>Cargando cotización...</span>
            </span>
        </div>
        <?php
        return ob_get_clean();
    }
}