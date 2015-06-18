<?php

class USASearch_Admin {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_menu', array( 'USASearch_Admin', 'admin_menu' ) );
	}

	public static function admin_menu() {
		self::load_menu();
	}

	public static function load_menu() {
		add_options_page( __('USASearch', 'usasearch'), __('USASearch', 'usasearch'), 'manage_options', 'usasearch-config', array( 'USASearch_Admin', 'display_page' ) );
	}

	public static function display_page() {
		USASearch::view( 'start' );
	}
}
