<?php
if ( ! isset( $data ) ) {
	return;
}
$option_attribute_to_search = get_option( 'tour_search_by_attributes' );
if ( empty( $option_attribute_to_search ) ) {
	return;
}
$item_label        = ! empty( $data['label'] ) ? $data['label'] : 'Where & When';
$destination_value = $data['destination'] ?? '';
$icon_date         = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M19.5 3.75H4.5C4.08579 3.75 3.75 4.08579 3.75 4.5V19.5C3.75 19.9142 4.08579 20.25 4.5 20.25H19.5C19.9142 20.25 20.25 19.9142 20.25 19.5V4.5C20.25 4.08579 19.9142 3.75 19.5 3.75Z" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M16.5 2.25V5.25" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M7.5 2.25V5.25" stroke="#AAAFB6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M3.75 8.25H20.25" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>';
$icon_where        = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
	<path d="M12 12.75C13.6569 12.75 15 11.4069 15 9.75C15 8.09315 13.6569 6.75 12 6.75C10.3431 6.75 9 8.09315 9 9.75C9 11.4069 10.3431 12.75 12 12.75Z" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
	<path d="M19.5 9.75C19.5 16.5 12 21.75 12 21.75C12 21.75 4.5 16.5 4.5 9.75C4.5 7.76088 5.29018 5.85322 6.6967 4.4467C8.10322 3.04018 10.0109 2.25 12 2.25C13.9891 2.25 15.8968 3.04018 17.3033 4.4467C18.7098 5.85322 19.5 7.76088 19.5 9.75V9.75Z" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
</svg>';
$icon_remove       = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M12.5 3.5L3.5 12.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M12.5 12.5L3.5 3.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>';
?>
<div class="tour-search-field destination 
<?php
if ( ! empty( $data['extra_class'] ) ) {
	echo $data['extra_class'];
}
?>
">
	<div class="item-filter-heading"><?php esc_html_e( $item_label, 'travel-booking' ); ?></div>
		<?php
		foreach ( $option_attribute_to_search as $key => $attribute_to_search ) {
			if ( isset( $data['show_attr'] ) && ( $data['show_attr'] == 'all' || $data['show_attr'] == $attribute_to_search ) ) {
				$tax_attribute      = get_taxonomy( $attribute_to_search );
				$terms_of_attribute = get_terms( $attribute_to_search );
				$class_inner        = $key == 0 ? ' frist ' : '';
				echo '<div class="tour-search-field-inner wrapper-content ' . $class_inner . $attribute_to_search . '">';
				if ( empty( $data['icon_attr']['value'] ) ) {
					if ( $attribute_to_search == 'pa_destination' ) {
						echo $icon_where;
					}
					if ( $attribute_to_search == 'pa_month' ) {
						echo $icon_date;
					}
				} else {
					if ( $data['icon_attr']['library'] == 'svg' ) {
						echo wp_get_attachment_image( $data['icon_attr']['value']['id'], 'full' );
					} else {
						echo '<i class="' . $data['icon_attr']['value'] . '"></i>';
					}
				}


				$tour_tax_param = $data['tour_tax_param'] ?? '';
				$placeholder    = sprintf(
					'%s %s',
					! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'All', 'travel-booking' ),
					$tax_attribute ? $tax_attribute->labels->singular_name : ''
				);
				echo '<div class="remove-attr">' . $icon_remove . '</div>';
				echo '<select class="' . $attribute_to_search . '" name="tourtax[' . $attribute_to_search . ']">';
				echo '<option value="0">' . esc_html__( $placeholder, 'travel-booking' ) . '</option>';
				if ( ( ! empty( $terms_of_attribute ) && ! is_wp_error( $terms_of_attribute ) ) && count( $terms_of_attribute ) > 0 ) {
					foreach ( $terms_of_attribute as $term ) {
						if ( is_array( $tour_tax_param ) && array_key_exists( $attribute_to_search, $tour_tax_param ) && $term->slug == $tour_tax_param[ $attribute_to_search ] ) {
							echo '<option value="' . $term->slug . '" selected="selected">' . $term->name . '</option>';
						} else {
							echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
						}
					}
				}
				echo '</select>';

				echo '</div>';


			}
		}
		?>

</div>
