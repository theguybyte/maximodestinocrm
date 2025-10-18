<?php

/**
 * TravelPhysCart
 *
 * @author  Physcode
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TravelTourTypeMeta {
	private static $instance;
	private static $term_meta_key = 'tour_phys_meta_key';

	protected function __construct() {
		$this->config();
		add_action( 'tour_phys_add_form_fields', array( $this, 'add_term_metabox' ), 10, 1 );
		add_action( 'tour_phys_edit_form', array( $this, 'edit_term_metabox' ), 10, 2 );
		add_action( 'saved_tour_phys', array( $this, 'save_term_metabox' ), 10, 1 );
	}

	public static function config() {
		return apply_filters(
			'travel/config/tour_phys/term-metabox',
			array(
				'tour_phys_text_color'       => array(
					'name'        => 'tour_phys_text_color',
					'type'        => 'color',
					'id'          => 'tour_phys_text_color',
					'title'       => esc_html__( 'Text Color', 'travel-booking' ),
					'description' => '',
					'default'     => '#000000',
				),
				'tour_phys_background_color' => array(
					'name'        => 'tour_phys_background_color',
					'type'        => 'color',
					'id'          => 'tour_phys_background_color',
					'title'       => esc_html__( 'Background Color', 'travel-booking' ),
					'description' => '',
					'default'     => '#4BA7FC',
				)
			)
		);
	}

	public function add_term_metabox( $taxonomy ) {
		$fields = self::config();

		foreach ( $fields as $field ) {
			$field['value'] = $field['default'];
			$field['name']  = self::$term_meta_key . '[' . $field['name'] . ']';

			$this->render_field( $field );
		}
	}

	public function edit_term_metabox( $tag, $taxonomy ) {
		$term_id   = $_GET['tag_ID'] ?? '';
		$term_meta = get_term_meta( $term_id, self::$term_meta_key, true );
		$fields    = self::config();

		foreach ( $fields as $field ) {
			$field['value'] = $term_meta[ $field['name'] ] ?? $field['default'];
			$field['name']  = self::$term_meta_key . '[' . $field['name'] . ']';
			$this->render_field( $field );
		}
	}

	public function save_term_metabox( $term_id ) {
		$fields = self::config();
		$data   = array();
		foreach ( $fields as $field ) {
			$key = isset( $_POST[ self::$term_meta_key ][ $field['name'] ] );
			if ( $key ) {
				$data[ $field['name'] ] = $_POST[ self::$term_meta_key ][ $field['name'] ];
			}
		}

		update_term_meta( $term_id, self::$term_meta_key, $data );
	}

	/**
	 * @param $field
	 *
	 * @return void
	 */
	public function render_field( $field ) {
		wp_enqueue_script( 'tour-booking-color-js' );
		?>
        <div class="tour-phys-field">
            <div class="tour-title-wrapper">
                <label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
            </div>
            <div class="tour-color-picker">
                <input type="text" data-jscolor="" id="<?php echo esc_attr( $field['id'] ); ?>"
                       name="<?php echo esc_attr( $field['name'] ); ?>"
                       value="<?php echo esc_attr( $field['value'] ?? '' ); ?>"
					<?php echo empty( $field['pattern'] ) ? '' : 'pattern = "' . esc_attr( $field['pattern'] ) . '"'; ?>
                />
				<?php
				if ( ! empty( $field->description ) ) {
					?>
                    <p class="tour-description"><?php echo esc_html( $field->description ); ?></p>
					<?php
				}
				?>
            </div>
        </div>
		<?php

	}

	/**
	 * @param $term_id
	 *
	 * @return void
	 */
	public static function get_term_meta_data( $term_id ) {
		return get_term_meta( $term_id, self::$term_meta_key, true );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

TravelTourTypeMeta::getInstance();
