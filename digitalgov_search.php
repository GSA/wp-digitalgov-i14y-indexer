<?php
/**
 * @package DigitalGov_Search
 * @version 0.0.2
 */
/*
Plugin Name: DigitalGov Search i14y Content Indexer
Plugin URI: https://github.com/GSA/wp-digitalgov-i14y-indexer
Description: This plugin allows your agency to add pages from your agency's WordPress website into the DigitalGov Search platform.
Author: GSA
Version: 0.0.1
Author URI: http://www.gsa.gov
*/

define( 'DIGITALGOV_SEARCH__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'DigitalGov_Search', 'plugin_activation' ) );

require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search.php' );
require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-document.php' );
require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-api.php' );

add_action( 'save_post', array( 'DigitalGov_Search', 'update_post' ) );
add_action( 'before_delete_post', array( 'DigitalGov_Search', 'delete_post' ) );
add_action( 'admin_head-post.php', array( 'DigitalGov_Search_Admin', 'add_indexer_notice' ) );
add_action( 'transition_post_status', array( 'DigitalGov_Search', 'unindex_post_when_unpublished' ), 10, 3 );

if ( is_admin() ) {
	require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-admin.php' );
	add_action( 'init', array( 'DigitalGov_Search_Admin', 'init' ) );
}
