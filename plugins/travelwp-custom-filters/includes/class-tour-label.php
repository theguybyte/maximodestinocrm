<?php
/**
 * Tour Label
 * Displays tour label/badge from ACF fields
 */
class TravelWP_Tour_Label {
    
    public function __construct() {
        // Register shortcode
        add_shortcode('tour_label', [$this, 'render_tour_label']);
        
        // Add inline styles
        add_action('wp_head', [$this, 'add_inline_styles']);
    }
    
    /**
     * Add inline CSS for tour label
     */
    public function add_inline_styles() {
        ?>
        <style>
        .travelwp-tour-label {
            background: #001D3A;
            color: white;
            padding: 8px 20px;
            border-radius: 16px;
            font-family: "Urbanist", Sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px #001d3a8c;
        }

        @media (max-width: 768px) {
            .travelwp-tour-label {
                font-size: 12px;
                padding: 6px 16px;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Render tour label shortcode
     */
    public function render_tour_label($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts([
            'product_id' => get_the_ID(), // Default to current post
        ], $atts);
        
        // Get ACF fields
        $label_fields = get_field('tour_label_fields', $atts['product_id']);
        
        if (!$label_fields) {
            return ''; // Don't show anything if no data
        }
        
        // Check if label should be shown
        $show_label = isset($label_fields['show_tour_label']) ? $label_fields['show_tour_label'] : false;
        $label_text = isset($label_fields['tour_label_text']) ? $label_fields['tour_label_text'] : '';
        
        // Don't show if disabled or empty text
        if (!$show_label || empty($label_text)) {
            return '';
        }
        
        ob_start();
        ?>
        <span class="travelwp-tour-label">
            <?php echo esc_html($label_text); ?>
        </span>
        <?php
        return ob_get_clean();
    }
}