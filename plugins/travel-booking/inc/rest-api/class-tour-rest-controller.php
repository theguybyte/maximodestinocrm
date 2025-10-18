<?php

class TOUR_REST_Widgets_Controller {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'travel-tour/v1',
			'/widgets/api',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'get_content_widgets' ),
				'permission_callback' => '__return_true',
			),
		);
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return stdClass
	 */
	public function get_content_widgets( WP_REST_Request $request ) {
		global $wp_widget_factory;

		$response = new stdClass();

		try {
			$params    = $request->get_params();
			$widget_id = $params['widget'] ?? false;
			$instance  = $params['instance'] ?? false;

			if ( empty( $widget_id ) || empty( $instance ) ) {
				throw new Exception( 'Error: No params!' );
			}

			$widget_object = $wp_widget_factory->get_widget_object( $widget_id );

			if ( ! method_exists( $widget_object, 'tour_rest_api_content' ) ) {
				throw new Exception( 'Error: No method tour_rest_api_content!' );
			}

			$instance = json_decode( $instance, true );

			unset( $params['instance'] );
			unset( $params['hash'] );

			$data = $widget_object->tour_rest_api_content( $instance, $params );

			if ( is_wp_error( $data ) ) {
				throw new Exception( $data->get_error_message() );
			}

			$response->status = 'success';
			$response->data   = $data;
		} catch ( Throwable $th ) {
			$response->status  = 'error';
			$response->message = $th->getMessage();
		}

		return $response;
	}
}

new TOUR_REST_Widgets_Controller();
