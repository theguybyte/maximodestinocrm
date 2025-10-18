<?php

if ( ! isset( $data ) ) {
	return;
}
$number_to_show = $data['number_to_show'] ?? 3;
$show_bt = $data['show_more'] ?? 'yes';
$title = $data['title'] ?? 'Gallery';
if( is_product()){
    $product = new \WC_product(get_the_ID()); 
    if( !$product ){
        return;
    }
    $attachment_ids = $product->get_gallery_image_ids();
    if(!empty($attachment_ids)){
        if (has_post_thumbnail()) {
            array_push($attachment_ids,get_post_thumbnail_id());	
        }
        ?>
        <div class="tours-single-gallery-more-wrap tours-single-main-item-nobgr fl-wrap">
            <div class="gallery-items grid-small-pad tours-single-gallery three-columns lightgallery" id="gallery-container">
                <?php
                $gMoreImages = array();  
                $gMoreImage = '';
                $images_to_show = $number_to_show;
                $items_width = 'x2,x1,x1,x1,x1,x1,x1,x1,x1,x1,x1,x1,x1,x1,x1';
                $items_widths = explode(',', $items_width); 
                foreach ($attachment_ids as $key =>  $id) {
                ?>
                    <?php if ($key <= $images_to_show - 1) : ?>
                        <?php
                        $item_cls = 'gallery-item';
                        $image_size = array(416,230);
                        if (isset($items_widths[$key])) {
                            switch ($items_widths[$key]) {
                                case 'x2':
                                    $item_cls .= ' gallery-item-second'; 
                                    $image_size = array(860,475);
                                    break;
                                case 'x3':
                                    $item_cls .= ' gallery-item-three';
                                    break;
                            }
                        }
                        ?>
                        <div class="<?php echo esc_attr($item_cls); ?>">
                            <div class="grid-item-holder">
                                <div class="box-item">
                                    <?php echo wp_get_attachment_image($id, $image_size); ?>
                                </div>
                            </div>
                        </div>
                <?php else :
                        if ($gMoreImage == '') $gMoreImage = wp_get_attachment_image($id, $image_size);
                    endif;
                    $gMoreImages[] = array(
                        'src' => wp_get_attachment_url($id),
                        'responsive' => wp_get_attachment_url($id), 
                        'thumb' => wp_get_attachment_url($id),
                        'subHtml' => '',
                    );
                }
                ?>
                <?php if (!empty($gMoreImages)) : ?>
                    <div class="gallery-item">
                        <div class="grid-item-holder">
                            <div class="box-item">
                                <?php echo $gMoreImage;
                                if($show_bt == 'yes'): 
                                 ?>
                                <div class="more-photos-button dynamic-gal" data-dynamicPath='<?php echo json_encode($gMoreImages); ?>'>
                                    <?php if(isset($data['icon'])){
                                        if(!empty($data['icon']['value'])){
                                            if(!empty($data['icon']['value']['url'])){
                                                echo file_get_contents($data['icon']['value']['url']); 
                                            }else{
                                                echo '<i class="'.$data['icon']['value'].'"></i>';
                                            }
                                        }
                                    }else{
                                        echo ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M19.125 4.125H4.875C4.46079 4.125 4.125 4.46079 4.125 4.875V19.125C4.125 19.5392 4.46079 19.875 4.875 19.875H19.125C19.5392 19.875 19.875 19.5392 19.875 19.125V4.875C19.875 4.46079 19.5392 4.125 19.125 4.125Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12 4.125V19.875" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19.875 12H4.125" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>';
                                    }?>
                                    <?php esc_html_e($title, 'travel-booking'); ?>
                                </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }else{
        if (has_post_thumbnail()) {
            $props            = wc_get_product_attachment_props(get_post_thumbnail_id(), $product);
            $image            = get_the_post_thumbnail(get_the_ID(), apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                'title' => $props['title'],
                'alt'   => $props['alt'],
            ));
            echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="swipebox" title="%s">%s</a>', $props['url'], $props['caption'], $image), get_the_ID());
        }
    } 
}