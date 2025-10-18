<?php

namespace Elementor;

class Physc_Destination_Content_Element extends Widget_Base {
    public function get_name() {
        return 'destination-content';
    }

    public function get_title() {
        return esc_html__('Attribute Destination Content', 'travelwp');
    }

    public function get_icon() {
        return 'el-travelwp eicon-post-content';
    }

    public function get_categories() {
	    return ['thim_ekit_archive_tours'];
     }
    public function render() {
         if(is_product_taxonomy()){
            $term = get_queried_object();
            $pattern         = '/^pa_/i';
            $check_attribute = preg_match($pattern, $term->taxonomy);
            if ($check_attribute) {
                $term_object = get_queried_object();
                if(!empty($term_object->term_id)){
                    $data_content   = get_tax_meta($term_object->term_id, 'phys_destination_content', true) ? get_tax_meta($term_object->term_id, 'phys_destination_content', true) : '';
                    if(!empty($data_content)){
                        $cleanedData = str_replace('\\', '', $data_content);
                        echo do_shortcode($cleanedData);
                    }
                }
            }
        }
    }
}
