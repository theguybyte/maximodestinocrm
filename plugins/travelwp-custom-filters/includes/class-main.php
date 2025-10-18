<?php
/**
 * Main plugin class - Orchestrator
 */
class TravelWP_Custom_Filters {
    
    private $location_taxonomy;
    private $transportation_taxonomy;
    private $filter_form;
    private $query_filter;
    private $admin;
    private $test;
    private $template_loader; // NEW
    
    public function __construct() {
        $this->init_classes();
        $this->init_hooks();
    }
    
    /**
     * Initialize all classes
     */
    private function init_classes() {
        $this->location_taxonomy = new TravelWP_Location_Taxonomy();
        $this->transportation_taxonomy = new TravelWP_Transportation_Taxonomy();
        $this->admin = new TravelWP_Admin();
        $this->filter_form = new TravelWP_Filter_Form();
        $this->query_filter = new TravelWP_Query_Filter();
        $this->test = new TravelWP_Test();
        $this->template_loader = new TravelWP_Template_Loader(); // NEW
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
    }
    
    /**
     * Load plugin text domain for translations
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'travelwp-filters',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }
}