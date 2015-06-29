<?php

class DigitalGov_Search_Admin {
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

		add_action( 'admin_menu', array( 'DigitalGov_Search_Admin', 'admin_menu' ) );
	}

	public static function enter_options() {
		update_option( 'digitalgov_search_handle', $_POST['handle'] );
		update_option( 'digitalgov_search_token', $_POST['token'] );
	}

	public static function admin_menu() {
		self::load_menu();
	}

	public static function load_menu() {
		add_options_page( __('DigitalGov Search i14y Indexer', 'digitalgov_search'), __('DigitalGov Search i14y Indexer', 'digitalgov_search'), 'manage_options', 'digitalgov_search-config', array( 'DigitalGov_Search_Admin', 'display_page' ) );
	}

	public static function display_page() {
		if ( DigitalGov_Search::credentials_set() && $_GET['view'] == 'index') {
			self::display_index_page();
		} else {
			self::display_configuration_page();
		}
	}

	public static function display_configuration_page() {
		DigitalGov_Search::view( 'start' );
	}

	public static function display_index_page() {
		if ( ! DigitalGov_Search::credentials_set() ) {
			self::display_credentials_error();
			exit;
		}
		DigitalGov_Search::view( 'index' );
	}

	public static function display_credentials_error() {
		DigitalGov_Search::view( 'notice' );
	}


	public static function get_page_url( $page = 'config' ) {
		$args = array( 'page' => 'digitalgov_search-config' );

		if ( $page == 'index' ) {
			$args = array( 'page' => 'digitalgov_search-config', 'view' => 'index' );
		}

		$url = add_query_arg( $args , admin_url('options-general.php') );

		return $url;
	}
}
