<?php
/**
 * @package USASearch
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

define( 'USASEARCH__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'USASearch', 'plugin_activation' ) );

require_once( USASEARCH__PLUGIN_DIR . 'class.usasearch.php' );
require_once( USASEARCH__PLUGIN_DIR . 'class.usasearch-document.php' );

if ( is_admin() ) {
	require_once( USASEARCH__PLUGIN_DIR . 'class.usasearch-admin.php' );
	add_action( 'init', array( 'USASearch_Admin', 'init' ) );
}
