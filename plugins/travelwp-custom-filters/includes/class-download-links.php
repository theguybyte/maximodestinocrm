<?php
/**
 * Tour Download Links
 * Displays download links for itineraries, rate sheets, and promotional material
 * Uses ACF fields from tour_download_links group
 */
class TravelWP_Download_Links {
    
    public function __construct() {
        // Register shortcode
        add_shortcode('tour_download_links', [$this, 'render_download_links']);
        
        // Add inline styles
        add_action('wp_head', [$this, 'add_inline_styles']);
    }
    
    /**
     * Add inline CSS for download links
     */
    public function add_inline_styles() {
        ?>
        <style>
        .travelwp-download-links {
            background: linear-gradient(135deg, #001d3a 0%, #07437f 100%);
            border-radius: 16px;
            padding: 32px;
            margin: 32px 0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .travelwp-download-links-title {
            font-family: "Urbanist", Sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 24px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .travelwp-download-links-title .material-icons {
            font-size: 32px;
            color: #bc1115;
        }

        .travelwp-download-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .travelwp-download-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 2px solid transparent;
            position: relative;
            height: 96px;
            box-sizing: border-box;
        }

        .travelwp-download-item:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            border-color: #0066cc;
        }

        .travelwp-download-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .travelwp-download-icon .material-icons {
            font-size: 32px;
            color: white;
        }

        .travelwp-download-item:hover .travelwp-download-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Icon colors for different types */
        .travelwp-download-item.itineraries .travelwp-download-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .travelwp-download-item.rates .travelwp-download-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .travelwp-download-item.promo .travelwp-download-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .travelwp-download-content {
            flex: 1;
        }

        .travelwp-download-label {
            font-family: "Urbanist", Sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
            display: block;
        }

        .travelwp-download-description {
            font-family: "Urbanist", Sans-serif;
            font-size: 13px;
            color: #666;
            margin: 0;
        }

        .travelwp-download-arrow {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(0, 102, 204, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .travelwp-download-arrow .material-icons {
            font-size: 20px;
            color: #0066cc;
        }

        .travelwp-download-item:hover .travelwp-download-arrow {
            background: #0066cc;
            transform: translateX(4px);
        }

        .travelwp-download-item:hover .travelwp-download-arrow .material-icons {
            color: white;
        }

        /* Empty state */
        .travelwp-download-empty {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            font-family: "Urbanist", Sans-serif;
            font-size: 15px;
        }

        .travelwp-download-empty .material-icons {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .travelwp-download-links {
                padding: 24px 20px;
            }

            .travelwp-download-links-title {
                font-size: 20px;
            }

            .travelwp-download-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .travelwp-download-item {
                padding: 16px;
            }

            .travelwp-download-icon {
                width: 48px;
                height: 48px;
            }

            .travelwp-download-icon .material-icons {
                font-size: 28px;
            }

            .travelwp-download-label {
                font-size: 15px;
            }

            .travelwp-download-description {
                font-size: 12px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Render download links shortcode
     */
    public function render_download_links($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts([
            'title' => __('Material Descargable', 'travelwp-filters'),
            'product_id' => get_the_ID(), // Default to current post
        ], $atts);
        
        // Get ACF fields
        $download_links = get_field('tour_download_links', $atts['product_id']);
        
        if (!$download_links) {
            return ''; // Don't show anything if no data
        }
        
        // Extract individual links
        $itineraries_link = isset($download_links['download_itineraries_link']) ? $download_links['download_itineraries_link'] : '';
        $rate_sheets_link = isset($download_links['download_rate_sheets']) ? $download_links['download_rate_sheets'] : '';
        $promo_material_link = isset($download_links['download_promotional_material']) ? $download_links['download_promotional_material'] : '';
        
        // Check if at least one link exists
        if (empty($itineraries_link) && empty($rate_sheets_link) && empty($promo_material_link)) {
            return ''; // Don't show if no links
        }
        
        ob_start();
        ?>
        <div class="travelwp-download-links">
            <?php if (!empty($atts['title'])): ?>
                <h3 class="travelwp-download-links-title">
                    <span class="material-icons">download</span>
                    <?php echo esc_html($atts['title']); ?>
                </h3>
            <?php endif; ?>
            
            <div class="travelwp-download-grid">
                <?php if (!empty($itineraries_link)): ?>
                    <a href="<?php echo esc_url($itineraries_link); ?>" 
                       class="travelwp-download-item itineraries" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <div class="travelwp-download-icon">
                            <span class="material-icons">map</span>
                        </div>
                        <div class="travelwp-download-content">
                            <span class="travelwp-download-label">
                                <?php _e('Itinerarios', 'travelwp-filters'); ?>
                            </span>
                            <p class="travelwp-download-description">
                                <?php _e('Descargá los itinerarios completos del tour', 'travelwp-filters'); ?>
                            </p>
                        </div>
                        <div class="travelwp-download-arrow">
                            <span class="material-icons">arrow_forward</span>
                        </div>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($rate_sheets_link)): ?>
                    <a href="<?php echo esc_url($rate_sheets_link); ?>" 
                       class="travelwp-download-item rates" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <div class="travelwp-download-icon">
                            <span class="material-icons">receipt_long</span>
                        </div>
                        <div class="travelwp-download-content">
                            <span class="travelwp-download-label">
                                <?php _e('Tarifarios', 'travelwp-filters'); ?>
                            </span>
                            <p class="travelwp-download-description">
                                <?php _e('Consultá las tarifas actualizadas', 'travelwp-filters'); ?>
                            </p>
                        </div>
                        <div class="travelwp-download-arrow">
                            <span class="material-icons">arrow_forward</span>
                        </div>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($promo_material_link)): ?>
                    <a href="<?php echo esc_url($promo_material_link); ?>" 
                       class="travelwp-download-item promo" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <div class="travelwp-download-icon">
                            <span class="material-icons">campaign</span>
                        </div>
                        <div class="travelwp-download-content">
                            <span class="travelwp-download-label">
                                <?php _e('Material Promocional', 'travelwp-filters'); ?>
                            </span>
                            <p class="travelwp-download-description">
                                <?php _e('Descargá imágenes, flyers y más', 'travelwp-filters'); ?>
                            </p>
                        </div>
                        <div class="travelwp-download-arrow">
                            <span class="material-icons">arrow_forward</span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}