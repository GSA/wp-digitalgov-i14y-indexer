<?php
/**
 * @package DigitalGov_Search
 * @version 0.1
 */
/*
Plugin Name: GSA OCSIT Hosted Search for Wordpress
Plugin URI: https://github.com/sent1nel/search
Description: This is not just a plugin, it is a way of life.
Author: Joshua K. Farrar
Version: 0.1
Author URI: http://sent1nel.me/
*/

define( 'DIGITALGOV_SEARCH__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'DigitalGov_Search', 'plugin_activation' ) );

require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search.php' );
require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-document.php' );

if ( is_admin() ) {
	require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-admin.php' );
	add_action( 'init', array( 'DigitalGov_Search_Admin', 'init' ) );
}
