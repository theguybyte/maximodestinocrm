<?php
// -> START Typography
Redux::set_section(
	$opt_name,
	array(
		'title'  => esc_html__( 'Typography Mobile', 'travelwp' ),
		'id'     => 'typography_mobile',
		'icon'   => 'el el-fontsize',
		'fields' => array(
			array(
				'id'      => 'font_size_h1_mobile',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size H1 (px)', 'travelwp' ),
				'default' => '32',
				'min'     => '1',
				'step'    => '1',
				'max'     => '90',
			),
			array(
				'id'      => 'font_size_h2_mobile',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size H2 (px)', 'travelwp' ),
				'default' => '26',
				'min'     => '1',
				'step'    => '1',
				'max'     => '80',
			),
			array(
				'id'      => 'font_size_h3_mobile',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size H3 (px)', 'travelwp' ),
				'default' => '20',
				'min'     => '1',
				'step'    => '1',
				'max'     => '50',
			),
			array(
				'id'      => 'font_size_h4_mobile',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size H4 (px)', 'travelwp' ),
				'default' => '18',
				'min'     => '1',
				'step'    => '1',
				'max'     => '50',
			),
			array(
				'id'      => 'font_size_h5_mobile',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size H5 (px)', 'travelwp' ),
				'default' => '16',
				'min'     => '1',
				'step'    => '1',
				'max'     => '50',
			),
			array(
				'id'      => 'font_size_h6_mobile',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Font Size H6 (px)', 'travelwp' ),
				'default' => '16',
				'min'     => '1',
				'step'    => '1',
				'max'     => '50',
			),
		),
	)
);
