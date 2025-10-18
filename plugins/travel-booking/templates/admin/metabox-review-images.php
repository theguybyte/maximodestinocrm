<?php
if ( ! isset( $comment_id ) || ! isset( $image_ids ) ) {
	return;
}

?>
    <div class="tour-review-images">
		<?php
		$max_images    = get_option( 'tour_max_images', 10 );
		$max_file_size = get_option( 'tour_max_file_size', 10000 );

		if ( empty( $image_ids ) ) {
			$image_ids = array();
		} else {
			if ( count( $image_ids ) > $max_images ) {
				$image_ids = array_slice( $image_ids, 0, $max_images );
			}
		}

		$value_data = implode( ',', $image_ids );
		?>
        <div class="tour-image-info"
             data-max-file-size="<?php echo esc_attr( $max_file_size ); ?>">
            <div class="tour-gallery-inner">
                <input type="hidden" name="<?php echo esc_attr( 'tour_review_images' ); ?>"
                       data-number="<?php echo esc_attr( $max_images ); ?>"
                       value="<?php echo esc_attr( $value_data ); ?>" readonly/>
				<?php
				$count = count( $image_ids );
				for ( $i = 0; $i < $count; $i ++ ) {
					$data_id = empty( $image_ids[ $i ] ) ? '' : $image_ids[ $i ];
					$img_src = '';
					if ( ! empty( wp_get_attachment_image_url( $data_id, 'thumbnail' ) ) ) {
						$img_src = wp_get_attachment_image_url( $data_id, 'thumbnail' );
					}
					$alt_text = '#';
					?>
                    <div class="tour-gallery-preview" data-id="<?php echo esc_attr( $data_id ); ?>">
                        <div class="tour-gallery-centered">
                            <img src="<?php echo esc_url_raw( $img_src ); ?>"
                                 alt="<?php echo esc_attr( $alt_text ); ?>">
                        </div>
                        <span class="tour-gallery-remove dashicons dashicons dashicons-no-alt"></span>
                    </div>
					<?php
				}
				?>
                <button type="button"
                        class="button tour-gallery-add"><?php esc_html_e( 'Add Images' ); ?></button>
            </div>
        </div>
    </div>

<?php
if ( ! did_action( 'wp_enqueue_media' ) ) {
	wp_enqueue_media();
}

