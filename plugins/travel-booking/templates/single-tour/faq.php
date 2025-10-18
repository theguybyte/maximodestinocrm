<?php
if (!defined('ABSPATH')) {
	exit;
}
$tour_id = get_the_ID();
$title   = get_post_meta($tour_id, 'phys_tour_faq_title', true);
$fields  = get_post_meta($tour_id, 'phys_tour_faq_options', true);
$fields  = json_decode($fields, true);
$class_wrapper = '';
$class_section = '';
if (isset($data) && $data['check_faqs_el']) {
	$class_wrapper = 'accordion-section';
	$class_section = ' thim-accordion-sections';
}
if (isset($fields) && is_array($fields)) :
?>
	<div class="single-tour-faq <?php echo $class_section; ?>">
		<?php if (!empty($title)) : ?>
			<div class="single-tour-faq-title"><?php echo esc_html($title); ?></div>
		<?php endif; ?>
		<ul>
			<?php
			foreach ($fields as $field) {
			?>
				<li class="tour-faq-wrapper <?php echo $class_wrapper; ?>">
					<?php
					if (!empty($field['label_faq'])) {
						$class_icon = '';
						$class_faq_content = "";
						if (empty($data['icon'])) {
							$class_icon = ' faq-icon-active';
						}
						if (class_exists('Thim_EL_Kit') && !empty($data['icon'])) {
							$class_icon .= ' tour-faq-toggle accordion-title';
							$class_faq_content .=  ' accordion-content';
						}
					?>
						<label class="tour-faq-title <?php echo $class_icon; ?>">
							<?php echo esc_html($field['label_faq']); ?>
							<?php
							if (class_exists('Thim_EL_Kit') && (!empty($data['icon']) || !empty($data['icon_active']))) {
							?>
								<span class="accordion-icon">
									<span class="accordion-icon-closed">
										<?php if (isset($data['icon']) && !empty($data['icon']['value'])) {
											echo '<i class="' . $data['icon']['value'] . '"></i>';
										}
										?></span>
									<span class="accordion-icon-opened">
										<?php if (isset($data['icon_active']) && !empty($data['icon_active']['value'])) {
											echo '<i class="' . $data['icon_active']['value'] . '"></i>';
										}
										?></span>
								</span>
							<?php } ?>
						</label>
					<?php
					}
					if (!empty($field['content_faq'])) {
					?>
						<p class="tour-faq-content <?php echo $class_faq_content; ?>"><?php echo esc_html($field['content_faq']); ?></p>
					<?php
					}
					?>
				</li>
			<?php
			}
			?>
		</ul>
	</div>
<?php endif; ?>