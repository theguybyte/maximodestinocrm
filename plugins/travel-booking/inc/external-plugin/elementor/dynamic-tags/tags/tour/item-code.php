<?php
namespace Tours\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Code extends Tag_Base {

	public function get_name() {
		return 'tours-item-code';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'travel-tour' );
	}

	public function get_title() {
		return 'Item Code';
	}

	protected function register_controls() {
		$this->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'travel-booking' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Code:',
			)
		);
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		global $product, $post;
		if ( ! $product ) {
			return '';
		}
		$code = get_post_meta( $post->ID, '_tour_code', true );
		if ( $code != '' ) : ?>
				<?php
				if ( ! empty( $settings['label'] ) ) :
					esc_html_e( $settings['label'], 'travel-booking' );
endif;
				?>
				<?php echo '<span>' . $code . '</span>'; ?>
			<?php
		endif;
	}
}
