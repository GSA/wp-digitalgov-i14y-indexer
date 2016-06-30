<?php
/**
 * @package DigitalGov_Search
 * @version 0.0.3
 */
/*
Plugin Name: DigitalGov Search i14y Content Indexer
Plugin URI: https://github.com/GSA/wp-digitalgov-i14y-indexer
Description: Index your federal agency's WordPress website with DigitalGov Search's hosted search platform through the I14y service.
Author: GSA
Version: 0.0.3
Author URI: http://search.digitalgov.gov
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

function wp_enqueue_custom_styles() {
	wp_register_style( 'DigitalGov_Search_Admin',  plugin_dir_url( __FILE__ ) . 'styles/admin.css' );
	wp_enqueue_style( 'DigitalGov_Search_Admin' );
}

add_action( 'admin_enqueue_scripts', 'wp_enqueue_custom_styles');

function wp_register_meta_boxes() {
	add_meta_box( 'dg_meta_box', 'DigitalGov Search', array( 'DigitalGov_Search_Admin', 'display_meta_box' ), null, 'side', 'high');
}

add_action( 'add_meta_boxes', 'wp_register_meta_boxes');

if ( is_admin() ) {
	require_once( DIGITALGOV_SEARCH__PLUGIN_DIR . 'class.digitalgov_search-admin.php' );
	add_action( 'init', array( 'DigitalGov_Search_Admin', 'init' ) );
}
