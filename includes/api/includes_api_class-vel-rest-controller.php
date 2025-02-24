<?php
/**
 * Class VEL_REST_Controller
 *
 * 註冊並處理 API 請求。
 *
 * @package VEL_Enterprise_System
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class VEL_REST_Controller extends WP_REST_Controller {

    public function register_routes() {
        $namespace = VEL_API_NAMESPACE;

        register_rest_route( $namespace, '/predict', array(
            array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( $this, 'handle_predict' ),
                'permission_callback' => array( $this, 'check_api_permissions' ),
            ),
        ));

        register_rest_route( $namespace, '/analyze', array(
            array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( $this, 'handle_analyze' ),
                'permission_callback' => array( $this, 'check_api_permissions' ),
            ),
        ));

        register_rest_route( $namespace, '/notify', array(
            array(
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => array( $this, 'handle_notify' ),
                'permission_callback' => '__return_true',
            ),
        ));
    }

    public function check_api_permissions( $request ) {
        $api_key = $request->get_header( 'X-VEL-API-Key' );
        $signature = $request->get_header( 'X-VEL-Signature' );
        $timestamp = $request->get_header( 'X-VEL-Timestamp' );

        if ( ! VEL_Security::validate_api_key( $api_key ) ) {
            return new WP_Error( 'invalid_api_key', __( 'Invalid API key.', 'vel-enterprise-system' ), array( 'status' => 401 ) );
        }

        if ( ! VEL_Security::verify_signature( $request, $signature ) ) {
            return new WP_Error( 'invalid_signature', __( 'Invalid signature.', 'vel-enterprise-system' ), array( 'status' => 401 ) );
        }

        if ( ! VEL_Security::check_rate_limit( $api_key ) ) {
            return new WP_Error( 'rate_limit_exceeded', __( 'Rate limit exceeded.', 'vel-enterprise-system' ), array( 'status' => 429 ) );
        }

        return true;
    }

    public function handle_predict( $request ) {
        // 處理預測請求
    }

    public function handle_analyze( $request ) {
        // 處理分析請求
    }

    public function handle_notify( $request ) {
        $ip_address = $request->get_param( 'ip_address' );
        VEL_IDS::launch_counterattack( $ip_address );
        return new WP_REST_Response( array( 'status' => 'success' ), 200 );
    }
}