<?php

class USASearch_Admin {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-key' ) {
			self::enter_options();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_menu', array( 'USASearch_Admin', 'admin_menu' ) );
	}

	public static function enter_options() {
		update_option( 'usasearch_handle', $_POST['handle'] );
		update_option( 'usasearch_token', $_POST['token'] );
		USASearch::view( 'post' );
		exit;
	}

	public static function admin_menu() {
		self::load_menu();
	}

	public static function load_menu() {
		add_options_page( __('USASearch', 'usasearch'), __('USASearch', 'usasearch'), 'manage_options', 'usasearch-config', array( 'USASearch_Admin', 'display_page' ) );
	}

	public static function display_page() {
		if (false) {
			self::display_configuration_page();
			return;
		}

		USASearch::view( 'start' );
	}

	public static function display_configuration_page() {
		$handle = USASearch::get_handle();
		$token  = USASearch::get_token();
	}

	public static function get_server_connectivity() {
		$response = wp_remote_get( 'https://i14y.usa.gov/api/v1/status' );
	}

	public static function get_page_url( $page = 'config' ) {
		$args = array( 'page' => 'usasearch-config' );

		$url = add_query_arg( $args , admin_url('options-general.php') );

		return $url;
	}
}
