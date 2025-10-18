<?php
if (!isset($data)) {
    return;
}
// $date_range_value = $_GET['date_range'] ?? '';
$date_range_value = ! empty( $_GET['date_range'] ) ? wc_clean( $_GET['date_range'] ) : '';
$item_label       = ! empty($data['label']) ? $data['label'] : 'Date';
$item_placeholder = ! empty($data['placeholder']) ? $data['placeholder'] : '';
?>
<div class="tour-search-field date <?php if (!empty($data['extra_class'])) {
                                        echo $data['extra_class'];
                                    }; ?>">
    <div class="item-filter-heading"><?php esc_html_e($item_label, 'travel-booking'); ?></div>
    <div class="tour-search-field-inner wrapper-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M19.5 3.75H4.5C4.08579 3.75 3.75 4.08579 3.75 4.5V19.5C3.75 19.9142 4.08579 20.25 4.5 20.25H19.5C19.9142 20.25 20.25 19.9142 20.25 19.5V4.5C20.25 4.08579 19.9142 3.75 19.5 3.75Z" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M16.5 2.25V5.25" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M7.5 2.25V5.25" stroke="#AAAFB6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M3.75 8.25H20.25" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <input type="text" name="date_range" value="<?php echo esc_attr( $date_range_value ); ?>" placeholder="<?php esc_html_e($item_placeholder, 'travel-booking');?>"/>
        <span id="remove-date-range">
             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M12.5 3.5L3.5 12.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.5 12.5L3.5 3.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        </span>
    </div>
    <!--    <div type="button" class="daterange-btn">01/01/2018 - 01/15/2018</div>-->
</div>
<?php
?>