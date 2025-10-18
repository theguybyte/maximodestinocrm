<?php

class TravelRegisterWidget {
	public static function init() {
		add_action( 'widgets_init', array( __CLASS__, 'tb_load_widget_phys' ) );
	}

	public static function tb_load_widget_phys() {
		require_once "class-travel-widgets.php";

		register_widget( 'tb_search_widget_phys' );
	}
}

TravelRegisterWidget::init();
