<?php

Redux::set_section(
	$opt_name,
	array(
		'title'  => esc_html__( 'Mobile Menu', 'travelwp' ),
		'id'     => 'menu_mobile',
		'icon'   => 'el el-cogs',
		'fields' => array(
			array(
				'id'      => 'header_mobile_menu',
				'type'    => 'switch',
				'title'   => esc_html__( 'Show', 'travelwp' ),
				'default' => 0,
				'on'      => 'Enable',
				'off'     => 'Disable',
			),
			array(
				'id'       => 'menu_mobile_icon',
				'type'     => 'sortable',
				'title'    => '',
				'mode'     => 'checkbox',
				'required' => array( 'header_mobile_menu', '=', '1' ),
				'options'  => array(
					'home'    => esc_html__( 'Home', 'travelwp' ),
					'tours'   => esc_html__( 'Tours', 'travelwp' ),
					'cart'    => esc_html__( 'Cart', 'travelwp' ),
					'search'  => esc_html__( 'Search', 'travelwp' ),
					'account' => esc_html__( 'Account', 'travelwp' ),
				),
				'default'  => array(
					'home'   => true,
					'tours'  => true,
					'cart'   => true,
					'search' => true,
				),
			),
			array(
				'id'       => 'form_search_mobile_popup',
				'type'     => 'editor',
				'title'    => esc_html__( 'Form search mobile popup', 'travelwp' ),
				'required' => array( 'header_mobile_menu', '=', '1' ),
				'desc'     => esc_html__( 'Enter content search tour mobile.', 'travelwp' ),
			),
		),
	)
);
