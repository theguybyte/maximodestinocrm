<?php

//https://maximodestino.local/?test_filter=1
class TravelWP_Test {
    
    public function __construct() {
        add_action('wp_head', [$this, 'test_filter']);
    }
    
    /**
     * Test filter functionality
     */
    public function test_filter() {
        if (!isset($_GET['test_filter']) || $_GET['test_filter'] != '1') {
            return;
        }
        
        echo '<pre style="background: #f5f5f5; padding: 20px; margin: 20px; border: 1px solid #ddd; font-family: monospace;">';
        echo "<h2>=== TravelWP Filter Test ===</h2>\n\n";
        
        // Test 1: Query by location
        echo "<strong>Test 1: Products with location 'Buenos Aires' (ID: 499)</strong>\n";
        echo "---\n";
        
        $args_location = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => 'location',
                    'field'    => 'term_id',
                    'terms'    => 499,
                ],
            ],
        ];
        
        $query_location = new WP_Query($args_location);
        echo "Found: " . $query_location->found_posts . " products\n";
        
        if ($query_location->have_posts()) {
            while ($query_location->have_posts()) {
                $query_location->the_post();
                echo "- " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
            }
        } else {
            echo "No products found\n";
        }
        
        wp_reset_postdata();
        
        echo "\n";
        
        // Test 2: Query by transportation
        echo "<strong>Test 2: Products with transportation 'Autobús' (ID: 523)</strong>\n";
        echo "---\n";
        
        $args_transport = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => 'transportation',
                    'field'    => 'term_id',
                    'terms'    => 523,
                ],
            ],
        ];
        
        $query_transport = new WP_Query($args_transport);
        echo "Found: " . $query_transport->found_posts . " products\n";
        
        if ($query_transport->have_posts()) {
            while ($query_transport->have_posts()) {
                $query_transport->the_post();
                echo "- " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
            }
        } else {
            echo "No products found\n";
        }
        
        wp_reset_postdata();
        
        echo "\n";
        
        // Test 3: Query by BOTH
        echo "<strong>Test 3: Products with BOTH 'Buenos Aires' AND 'Autobús'</strong>\n";
        echo "---\n";
        
        $args_both = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => 'location',
                    'field'    => 'term_id',
                    'terms'    => 499,
                ],
                [
                    'taxonomy' => 'transportation',
                    'field'    => 'term_id',
                    'terms'    => 523,
                ],
                'relation' => 'AND',
            ],
        ];
        
        $query_both = new WP_Query($args_both);
        echo "Found: " . $query_both->found_posts . " products\n";
        
        if ($query_both->have_posts()) {
            while ($query_both->have_posts()) {
                $query_both->the_post();
                echo "- " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
            }
        } else {
            echo "No products found\n";
        }
        
        wp_reset_postdata();
        
        echo "\n";
        
        // Test 4: Show all products with their assigned terms
        echo "<strong>Test 4: All products with their assigned taxonomies</strong>\n";
        echo "---\n";
        
        $all_products = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => -1,
        ]);
        
        if ($all_products->have_posts()) {
            while ($all_products->have_posts()) {
                $all_products->the_post();
                $locations = wp_get_post_terms(get_the_ID(), 'location', ['fields' => 'names']);
                $transports = wp_get_post_terms(get_the_ID(), 'transportation', ['fields' => 'names']);
                
                echo "- " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
                echo "  Locations: " . (empty($locations) ? 'None' : implode(', ', $locations)) . "\n";
                echo "  Transportation: " . (empty($transports) ? 'None' : implode(', ', $transports)) . "\n";
            }
        }
        
        wp_reset_postdata();
        
        echo "\n<h2>=== End Test ===</h2>\n";
        echo '</pre>';
        
        die();
    }
}