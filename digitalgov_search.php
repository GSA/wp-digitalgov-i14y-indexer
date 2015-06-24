<?php
/**
 * @package DigitalGov_Search
 * @version 0.0.1
 */
/*
Plugin Name: DigitalGov Search
Plugin URI: https://github.com/gsa/wp_digitalgov_search
Description: This plugin allows your agency to add pages from your agency's WordPress website into the DigitalGov Search platform.
Author: GSA
Version: 0.0.1
Author URI: http://www.gsa.gov
*/

define( 'DIGITALGOV_SEARCH__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'DigitalGov_Search', 'plugin_activation' ) );

require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search.php' );
require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-document.php' );

add_action( 'save_post', array( 'DigitalGov_Search', 'update_post' ) );

if ( is_admin() ) {
	require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-admin.php' );
	add_action( 'init', array( 'DigitalGov_Search_Admin', 'init' ) );
}
