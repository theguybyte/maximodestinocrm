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
    private $page_title; // Add this property    
    private $no_results; // Add property
    private $currency_selector;
    private $filter_widget; // Add property

    
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
        $this->page_title = new TravelWP_Page_Title(); // Add this line
        $this->no_results = new TravelWP_No_Results(); // Add this
        $this->currency_selector = new TravelWP_Currency_Selector();
        $this->filter_widget = new TravelWP_Filter_Widget();
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