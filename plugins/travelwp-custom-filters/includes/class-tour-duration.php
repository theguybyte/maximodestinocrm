<?php
/**
 * Tour Duration
 * Displays tour duration from ACF fields
 */
class TravelWP_Tour_Duration {
    
    public function __construct() {
        // Register shortcode
        add_shortcode('tour_duration', [$this, 'render_tour_duration']);
        
        // Add inline styles
        add_action('wp_head', [$this, 'add_inline_styles']);
    }
    
    /**
     * Add inline CSS for tour duration
     */
    public function add_inline_styles() {
        ?>
        <style>
        .travelwp-tour-duration {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #333;
            font-size: 14px;
            font-weight: 400;
        }

        .travelwp-tour-duration .material-icons {
            font-size: 20px;
            color: #0066cc;
        }

        .travelwp-tour-duration-text {
            color: #1a1a1a;
        }

        @media (max-width: 768px) {
            .travelwp-tour-duration {
                font-size: 14px;
                padding: 8px 16px;
            }
            
            .travelwp-tour-duration .material-icons {
                font-size: 18px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Render tour duration shortcode
     */
    public function render_tour_duration($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts([
            'product_id' => get_the_ID(), // Default to current post
            'icon' => 'schedule', // Default Material Icon
            'show_icon' => 'yes', // Show or hide icon
        ], $atts);
        
        // Get ACF fields
        $label_fields = get_field('tour_label_fields', $atts['product_id']);
        
        if (!$label_fields) {
            return ''; // Don't show anything if no data
        }
        
        // Get duration text
        $duration = isset($label_fields['duration_tour']) ? $label_fields['duration_tour'] : '';
        
        // Don't show if empty
        if (empty($duration)) {
            return '';
        }
        
        ob_start();
        ?>
        <span class="travelwp-tour-duration">
            <?php if ($atts['show_icon'] === 'yes'): ?>
                <span class="material-icons"><?php echo esc_attr($atts['icon']); ?></span>
            <?php endif; ?>
            <span class="travelwp-tour-duration-text">
                <?php echo esc_html($duration); ?>
            </span>
        </span>
        <?php
        return ob_get_clean();
    }
}