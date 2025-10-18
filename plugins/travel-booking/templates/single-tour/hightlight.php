 <?php
    if (!defined('ABSPATH')) {
        exit;
    }
    $tour_id = get_the_ID();
    $fields  = get_post_meta($tour_id, 'phys_tour_hightlight_options', true);
    $fields  = json_decode($fields, true);
    $class_wrapper = '';
    $class_section = '';
    if (isset($fields) && is_array($fields)) :
    ?>
     <div class="single-tour-hightlight <?php echo $class_section; ?>">
         <?php
            foreach ($fields as $field) {
            ?>
             <div class="single-tour-hightlight-item">
                <?php 
                if(!empty($field["image_hightlight"])){
                     echo wp_get_attachment_image($field["image_hightlight"],'full');
                }
                if (!empty($field["label_hightlight"])) {
                    echo sprintf('<h6>%s</h6>',esc_html__($field["label_hightlight"],'travel-boking')); 
                }
                ?>
             </div>
            <?php
            }
            ?>
     </div>
 <?php endif; ?>