<?php
/**
 * No Results Handler
 * Displays a beautiful message when no tours are found
 */
class TravelWP_No_Results {
    
    public function __construct() {
        add_action('wp_footer', [$this, 'replace_no_results_message']);
        add_action('wp_head', [$this, 'add_no_results_styles']);
    }
    
    /**
     * Add custom styles for no results message
     */
    public function add_no_results_styles() {
        ?>
        <style>
            .travelwp-no-results {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 60px 20px;
                text-align: center;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                border-radius: 16px;
                margin: 0px 0px 0px 40px;
                min-height: 400px;
            }
            
            .travelwp-no-results-icon {
                position: relative;
                margin-bottom: 30px;
            }
            
            .travelwp-no-results-icon .material-icons {
                font-size: 120px;
                color: #0066cc;
                opacity: 0.2;
                animation: float 3s ease-in-out infinite;
            }
            
            .travelwp-no-results-icon .material-icons.icon-overlay {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 60px;
                opacity: 0.4;
                animation: pulse 2s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-20px);
                }
            }
            
            @keyframes pulse {
                0%, 100% {
                    transform: translate(-50%, -50%) scale(1);
                    opacity: 0.4;
                }
                50% {
                    transform: translate(-50%, -50%) scale(1.1);
                    opacity: 0.6;
                }
            }
            
            .travelwp-no-results-title {
                font-size: 28px;
                font-weight: 700;
                color: #1a1a1a;
                margin-bottom: 12px;
            }
            
            .travelwp-no-results-subtitle {
                font-size: 16px;
                color: #666;
                margin-bottom: 30px;
                max-width: 500px;
                line-height: 1.6;
            }
            
            .travelwp-no-results-suggestions {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 30px;
                text-align: left;
                max-width: 400px;
                width: 100%;
            }
            
            .travelwp-suggestion-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                transition: all 0.3s ease;
            }
            
            .travelwp-suggestion-item:hover {
                transform: translateX(5px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }
            
            .travelwp-suggestion-item .material-icons {
                font-size: 20px;
                color: #0066cc;
            }
            
            .travelwp-suggestion-text {
                font-size: 14px;
                color: #333;
                flex: 1;
            }
            
            .travelwp-no-results-actions {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .travelwp-btn-primary,
            .travelwp-btn-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 24px;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }
            
            .travelwp-btn-primary {
                background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
                color: white;
                box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
            }
            
            .travelwp-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(0, 102, 204, 0.4);
                color: white;
            }
            
            .travelwp-btn-secondary {
                background: white;
                color: #0066cc;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .travelwp-btn-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                color: #0052a3;
            }
            
            .material-icons {
                font-size: 20px;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .travelwp-no-results {
                    padding: 40px 20px;
                    min-height: 350px;
                }
                
                .travelwp-no-results-icon .material-icons {
                    font-size: 80px;
                }
                
                .travelwp-no-results-icon .material-icons.icon-overlay {
                    font-size: 40px;
                }
                
                .travelwp-no-results-title {
                    font-size: 22px;
                }
                
                .travelwp-no-results-subtitle {
                    font-size: 14px;
                }
                
                .travelwp-no-results-actions {
                    flex-direction: column;
                    width: 100%;
                }
                
                .travelwp-btn-primary,
                .travelwp-btn-secondary {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
        <?php
    }
    
    /**
     * Replace the default no results message with JavaScript
     */
    public function replace_no_results_message() {
        // Only run on tour search pages
        if (!isset($_GET['tour_search'])) {
            return;
        }
        
        $location = get_query_var('location');
        $transportation = get_query_var('transportation');
        
        $location_term = null;
        $transportation_term = null;
        
        if ($location && $location != '0') {
            $location_term = get_term_by('slug', $location, 'location');
        }
        
        if ($transportation && $transportation != '0') {
            $transportation_term = get_term_by('slug', $transportation, 'transportation');
        }
        
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Find the ugly message
            var $uglyMessage = $('.thim-ekit-archive-tours p.woocommerce-info');
            
            if ($uglyMessage.length) {
                // Build the beautiful message
                var beautifulMessage = `
                    <div class="travelwp-no-results">
                        <div class="travelwp-no-results-icon">
                            <span class="material-icons">explore</span>
                            <span class="material-icons icon-overlay">search_off</span>
                        </div>
                        
                        <h2 class="travelwp-no-results-title">
                            <?php _e('No encontramos paquetes con estos filtros', 'travelwp-filters'); ?>
                        </h2>
                        
                        <p class="travelwp-no-results-subtitle">
                            <?php 
                            if ($location_term || $transportation_term) {
                                echo __('No hay tours disponibles', 'travelwp-filters');
                                if ($location_term) {
                                    echo ' ' . __('en', 'travelwp-filters') . ' <strong>' . esc_html($location_term->name) . '</strong>';
                                }
                                if ($transportation_term) {
                                    echo ' ' . __('con transporte', 'travelwp-filters') . ' <strong>' . esc_html($transportation_term->name) . '</strong>';
                                }
                                echo '.';
                            } else {
                                _e('Intenta modificar tus criterios de búsqueda para encontrar el tour perfecto.', 'travelwp-filters');
                            }
                            ?>
                        </p>
                        
                        <div class="travelwp-no-results-suggestions">
                            <div class="travelwp-suggestion-item">
                                <span class="material-icons">lightbulb</span>
                                <span class="travelwp-suggestion-text"><?php _e('Prueba seleccionar otro destino cercano', 'travelwp-filters'); ?></span>
                            </div>
                            <div class="travelwp-suggestion-item">
                                <span class="material-icons">tune</span>
                                <span class="travelwp-suggestion-text"><?php _e('Cambia el tipo de transporte', 'travelwp-filters'); ?></span>
                            </div>
                            <div class="travelwp-suggestion-item">
                                <span class="material-icons">calendar_today</span>
                                <span class="travelwp-suggestion-text"><?php _e('Intenta con fechas más flexibles', 'travelwp-filters'); ?></span>
                            </div>
                        </div>
                        
                        <div class="travelwp-no-results-actions">
                            <a href="<?php echo esc_url(add_query_arg('tour_search', '1', home_url('/'))); ?>" class="travelwp-btn-primary">
                                <span class="material-icons">refresh</span>
                                <span><?php _e('Ver todos los tours', 'travelwp-filters'); ?></span>
                            </a>
                            <button onclick="window.history.back();" class="travelwp-btn-secondary">
                                <span class="material-icons">arrow_back</span>
                                <span><?php _e('Volver atrás', 'travelwp-filters'); ?></span>
                            </button>
                        </div>
                    </div>
                `;
                
                // Replace it
                $uglyMessage.replaceWith(beautifulMessage);
            }
        });
        </script>
        <?php
    }
}