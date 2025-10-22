<?php
/**
 * Filter Widget/Shortcode
 * Compact version of the filter form for sidebars and small spaces
 * All styles and scripts are self-contained in this class
 */
class TravelWP_Filter_Widget {
    
    public function __construct() {
        // Register the widget shortcode
        add_shortcode('travelwp_filter_widget', [$this, 'render_widget_form']);
        
        // Add inline styles and scripts
        add_action('wp_head', [$this, 'add_inline_styles']);
        add_action('wp_footer', [$this, 'add_inline_scripts']);
    }
    
    /**
     * Add inline CSS for widget
     */
    public function add_inline_styles() {
        ?>
        <style>
        /* Widget Container */
        .travelwp-filter-widget {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .travelwp-filter-widget .widget-title {
            font-family: "Urbanist", Sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 20px 0;
            padding-bottom: 12px;
            border-bottom: 2px solid #0066cc;
        }

        .travelwp-widget-search-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Widget Field Styling - All stacked vertically */
        .widget-filter-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .widget-filter-field label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #333;
            font-family: "Urbanist", Sans-serif;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
        }

        .widget-filter-field label .material-icons {
            font-size: 18px;
            color: #0066cc;
        }

        /* Widget Select2 Styling */
        .travelwp-widget-select2 {
            width: 100%;
        }

        .widget-filter-field .select2-container {
            width: 100% !important;
        }

        .widget-filter-field .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #f9f9f9;
            padding: 0 12px;
            transition: all 0.3s ease;
        }

        .widget-filter-field .select2-container--default .select2-selection--single:hover {
            border-color: #0066cc;
            background: #fff;
        }

        .widget-filter-field .select2-container--default.select2-container--focus .select2-selection--single,
        .widget-filter-field .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #0066cc;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            color: #333;
            font-size: 14px;
            padding: 0;
            font-family: "Urbanist", Sans-serif;
        }

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #999;
            font-family: "Urbanist", Sans-serif;
            font-size: 14px;
        }

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
            height: 100% !important;
            right: 12px !important;
            top: 0 !important;
        }

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #999 transparent transparent transparent;
            border-width: 6px 5px 0 5px;
            margin-top: -3px;
        }

        /* Clear button styling for widget */
        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__clear {
            cursor: pointer;
            float: right;
            font-weight: 400;
            height: 24px;
            width: 24px;
            margin-right: 8px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.08);
            border-radius: 50%;
            color: #666;
            font-size: 18px;
            line-height: 1;
            transition: all 0.2s ease;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }        

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            transform: translateY(-50%) scale(1.1);
        }

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__clear:active {
            transform: translateY(-50%) scale(0.95);
        }

        .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__clear span {
            display: block;
            line-height: 1;
        }        

        /* Fix Select2 dropdown width for widget */
        .select2-container--default.travelwp-widget-dropdown .select2-dropdown {
            max-width: 100%;
            width: auto !important;
        }

        .select2-container--default.travelwp-widget-dropdown .select2-results {
            max-width: 100%;
        }

        .select2-container--default.travelwp-widget-dropdown .select2-results__option {
            word-wrap: break-word;
            white-space: normal;
        }

        /* Widget Search Button */
        .widget-search-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            height: 48px;
            padding: 0 20px;
            background: linear-gradient(135deg, #00bfa5 0%, #00a08a 100%);
            color: white;
            border: none;
            border-radius: 200px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 191, 165, 0.3);
            font-family: "Urbanist", Sans-serif;
            font-size: 15px;
            font-weight: 600;
        }

        .widget-search-btn:hover {
            background: linear-gradient(135deg, #00a08a 0%, #008f7a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 191, 165, 0.4);
        }

        .widget-search-btn:active {
            transform: translateY(0);
        }

        .widget-search-btn .material-icons {
            font-size: 20px;
        }

        /* Responsive adjustments for very small screens */
        @media (max-width: 480px) {
            .travelwp-filter-widget {
                padding: 16px;
                border-radius: 10px;
            }
            
            .travelwp-filter-widget .widget-title {
                font-size: 16px;
                margin-bottom: 16px;
            }
            
            .travelwp-widget-search-form {
                gap: 14px;
            }
            
            .widget-filter-field label {
                font-size: 12px;
            }
            
            .widget-filter-field .select2-container--default .select2-selection--single {
                height: 40px;
            }
            
            .widget-filter-field .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 38px;
                font-size: 13px;
            }
            
            .widget-search-btn {
                height: 44px;
                font-size: 14px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Add inline JavaScript for widget
     */
    public function add_inline_scripts() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Only initialize if widget exists on page
            if ($('#widget-location-select').length === 0) {
                return;
            }
            
            // Initialize widget Select2 for locations
            $('#widget-location-select').select2({
                placeholder: travelwpFilterData.strings.selectDestination,
                allowClear: true,
                data: travelwpFilterData.locations,
                dropdownAutoWidth: false,
                width: '100%',
                dropdownCssClass: 'travelwp-widget-dropdown',
                dropdownParent: $('.travelwp-filter-widget'),
                language: {
                    noResults: function () {
                        return 'No encontramos ese destino';
                    }
                }                
            });
            
            // Initialize widget Select2 for transportation
            $('#widget-transportation-select').select2({
                placeholder: travelwpFilterData.strings.selectTransportation,
                allowClear: true,
                data: travelwpFilterData.transportations,
                dropdownAutoWidth: false,
                width: '100%',
                dropdownCssClass: 'travelwp-widget-dropdown',
                dropdownParent: $('.travelwp-filter-widget'),
                language: {
                    noResults: function () {
                        return 'No encontramos ese transporte';
                    }
                }                   
            });

            $('#widget-location-select').val(null).trigger('change');
            $('#widget-transportation-select').val(null).trigger('change');
            
            // Show loading animation on widget form submit
            $('.travelwp-widget-search-form').on('submit', function(e) {
                showLoadingOverlay();
            });
            
            // Shared loading overlay function
            function showLoadingOverlay() {

                if ($('#travelwp-loading-overlay').length > 0) {
                    return;
                }                

                // Prevent body scroll BEFORE adding overlay
                $('html, body').css({
                    'overflow': 'hidden',
                    'height': '100%'
                });

                // Create overlay HTML
                const overlayHTML = `
                    <div id="travelwp-loading-overlay" style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(255, 255, 255, 0.95);
                        z-index: 99999;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        overflow: hidden;
                        margin: 0;
                        padding: 0;
                    ">
                        <lottie-player
                            src="https://lottie.host/231d8a57-c861-4da5-a41f-7197ea89fa12/u882jc7lt1.json"
                            background="transparent"
                            speed="1"
                            style="width: 500px; height: 500px;"
                            loop
                            autoplay>
                        </lottie-player>
                        <p style="
                            margin-top: 20px;
                            font-size: 35px;
                            color: #333;
                            font-weight: 600;
                            margin-bottom: 0;
                            position: absolute;
                            bottom: 30%;
                            font-weight: bold;
                        ">Buscando tours...</p>
                    </div>
                `;

                $('body').append(overlayHTML);

            }


        });
        </script>
        <?php
    }
    
    /**
     * Render the widget form (compact stacked version)
     */
    public function render_widget_form($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts([
            'title' => '',
        ], $atts);
        
        ob_start();
        ?>
        <div class="travelwp-filter-widget">
            <?php if (!empty($atts['title'])): ?>
                <h3 class="widget-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>
            
            <form class="travelwp-widget-search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="hidden" name="tour_search" value="1">
                
                <div class="widget-filter-field">
                    <label for="widget-location-select">
                        <span class="material-icons">place</span>
                        <?php _e('Destino', 'travelwp-filters'); ?>
                    </label>
                    <select id="widget-location-select" name="location" class="travelwp-widget-select2">
                        <!-- Options populated by JavaScript -->
                    </select>
                </div>
                
                <div class="widget-filter-field">
                    <label for="widget-transportation-select">
                        <span class="material-icons">directions_bus</span>
                        <?php _e('Transporte', 'travelwp-filters'); ?>
                    </label>
                    <select id="widget-transportation-select" name="transportation" class="travelwp-widget-select2">
                        <!-- Options populated by JavaScript -->
                    </select>
                </div>
                
                <div class="widget-filter-field widget-filter-button">
                    <button type="submit" class="widget-search-btn">
                        <span class="material-icons">search</span>
                        <span class="btn-text"><?php _e('Buscar Tours', 'travelwp-filters'); ?></span>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}