<?php
/**
 * Plugin Name: TravelWP Custom Filters
 * Plugin URI: https://yoursite.com
 * Description: Custom taxonomies and filters for TravelWP tours
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yoursite.com
 * License: GPL2
 * Text Domain: travelwp-filters
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TRAVELWP_FILTERS_VERSION', '1.0.1');
define('TRAVELWP_FILTERS_PATH', plugin_dir_path(__FILE__));
define('TRAVELWP_FILTERS_URL', plugin_dir_url(__FILE__));

// Load classes
require_once TRAVELWP_FILTERS_PATH . 'includes/class-location-taxonomy.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-transportation-taxonomy.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-admin.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-main.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-test.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-filter-form.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-query-filter.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-template-loader.php'; // NEW
require_once TRAVELWP_FILTERS_PATH . 'includes/class-page-title.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-no-results.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-currency-selector.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-filter-widget.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-download-links.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-tour-label.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-tour-duration.php';
require_once TRAVELWP_FILTERS_PATH . 'includes/class-tour-quotation.php';


// Initialize plugin
new TravelWP_Custom_Filters();