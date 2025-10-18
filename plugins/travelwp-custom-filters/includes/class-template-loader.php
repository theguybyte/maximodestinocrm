<?php
/**
 * Template Loader
 * Loads custom templates from the plugin
 */
class TravelWP_Template_Loader {
    
    public function __construct() {
        // Use priority 999 to ensure we override everything
        // add_filter('template_include', [$this, 'load_custom_template'], 999);
    }
    
    /**
     * Load custom template for our filtered queries
     */
    public function load_custom_template($template) {
        // Only override if this is our custom search
        if (!isset($_GET['toursearch_custom']) || $_GET['toursearch_custom'] != '1') {
            return $template;
        }
        
        // IMPORTANT: Force high priority to override taxonomy templates too
        // Look for our custom template
        $plugin_template = TRAVELWP_FILTERS_PATH . 'templates/archive-tour.php';
        
        if (file_exists($plugin_template)) {
            error_log('TravelWP: Loading custom template from plugin');
            return $plugin_template;
        } else {
            error_log('TravelWP: Custom template not found at: ' . $plugin_template);
        }
        
        return $template;
    }
}