<?php
if ( ! isset( $data ) ) {
	return;
}

if ( ! isset( $data['fields'] ) ) {
	return;
}

$fields = $data['fields'];
if ( is_string( $data['fields'] ) ) {
	$fields = explode( ',', $fields );
}

if ( count( $fields ) === 0 ) {
	return;
}
?>
    <div class="tour-search">
		<?php
		foreach ( $fields as $field ) {
			$file = TB_PHYS_TEMPLATE_PATH_DEFAULT . 'tour-search/fields/' . $field . '.php';
			if ( file_exists( $file ) ) {
				include $file;
			}
		}
		?>
    </div>
<?php



