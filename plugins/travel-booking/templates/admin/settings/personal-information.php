<?php
/**
 * Settings Personal Information
 *
 * @author  physcode
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$travel_personal_information_structure_fields = apply_filters(
	'travel_personal_information_structure_fields',
	array(
		'label'     => array(
			'label' => __( 'Label', 'travel-booking' ),
			'types' => 'text',
		),
		'enable'    => array(
			'label' => __( 'Enable', 'travel-booking' ),
			'types' => array(
				1 => array( 'label' => __( 'Yes' ) ),
				0 => array( 'label' => __( 'No' ) ),
			),
		),
		'type'      => array(
			'label' => __( 'Type', 'travel-booking' ),
			'types' => array(
				'select'   => array( 'label' => __( 'Select' ) ),
				'text'     => array( 'label' => __( 'Text' ) ),
				'checkbox' => array( 'label' => __( 'Checkbox' ) ),
				'radio'    => array( 'label' => __( 'Radio' ) ),
			),
		),
		'required'  => array(
			'label' => __( 'Required', 'travel-booking' ),
			'types' => array(
				1 => array( 'label' => 'Yes' ),
				0 => array( 'label' => 'No' ),
			),
		),

		/*** For Attribute ***/
		'attribute' => array(),
	)
);

$travel_personal_information_enable     = get_option( Tour_Settings_Tab_Phys::$_personal_information_enable ) == '' ? 0 : get_option( 'travel_personal_information_enable' );
$class_field                            = 'field-personal-information';
$travel_personal_information_option_str = htmlspecialchars( get_option( Tour_Settings_Tab_Phys::$_personal_information ) );
$travel_personal_information_option_obj = $travel_personal_information_option_str == '{}' || $travel_personal_information_option_str == '' ? null : json_decode( get_option( Tour_Settings_Tab_Phys::$_personal_information ) );
?>

<div id="travel-setting-personal-information">
	<p>
		<label for="">Enable</label>
		<select name="<?php echo Tour_Settings_Tab_Phys::$_personal_information_enable; ?>">
			<?php
			if ( $travel_personal_information_enable ) {
				echo '<option value="1" selected>Yes</option>';
				echo '<option value="0">No</option>';
			} else {
				echo '<option value="1">Yes</option>';
				echo '<option value="0" selected>No</option>';
			}
			?>

		</select>
	</p>
	<button class="btn" id="add-travel-personal-info"><?php _e( 'Add item', 'travel-booking' ); ?></button>

	<table id="table-travel-setting-personal-information">
		<thead>
		<?php
		foreach ( $travel_personal_information_structure_fields as $k_field => $field ) {
			if ( $k_field !== 'attribute' ) {
				echo '<th>' . $field['label'] . '</th>';
			} else {
				echo '<th>' . __( 'Attribute', 'travel-booking' ) . '</th>';
			}
		}
		echo '<th>' . __( 'Priority', 'travel-booking' ) . '</th>';
		echo '<th>' . __( 'Remove', 'travel-booking' ) . '</th>';
		?>
		</thead>
		<tbody>
		<?php
		if ( ! is_null( $travel_personal_information_option_obj ) ) {
			foreach ( $travel_personal_information_option_obj as $k => $data ) {
				echo '<tr id="' . $k . '">';
				foreach ( $travel_personal_information_structure_fields as $k_field => $field ) {
					if ( $k_field !== 'attribute' ) {
						$types_field = $field['types'];

						if ( is_array( $types_field ) ) {
							echo '<td><select name="' . $k_field . '" class="' . $class_field . '">';
							foreach ( $types_field as $k_type => $type ) {
								$selected = '';
								if ( $data->{$k_field} == $k_type ) {
									$selected = 'selected';
								}
								echo '<option value="' . $k_type . '" ' . $selected . '>' . $type['label'] . '</option>';
							}
							echo '</select></td>';
						} elseif ( $types_field == 'text' ) {
							echo '<td><input name="' . $k_field . '" class="' . $class_field . '" type="text" value="' . $data->{$k_field} . '"></td>';
						}
					} else {
						echo '<td><input name="' . $k_field . '" class="' . $class_field . '" type="text" placeholder="value 1|value2" value="' . $data->{$k_field} . '" /></td>';
					}
				}
				echo '<td><span class="dashicons dashicons-move"></span></td>';
				echo '<td><span class="dashicons dashicons-no-alt remove-item-personal-information"></span></td>';
				echo '</tr>';
			}
		} else {
			echo '<tr></tr>';
		}
		?>
		</tbody>
	</table>

	<input type="hidden" name="travel_personal_information_structure_fields" value="<?php echo htmlentities( json_encode( $travel_personal_information_structure_fields ) ); ?>" />
	<input type="hidden" name="<?php echo Tour_Settings_Tab_Phys::$_personal_information; ?>" value="<?php echo $travel_personal_information_option_str; ?>" />
	<input type="hidden" name="class-field-personal-information" value="<?php echo $class_field; ?>" />
</div>
