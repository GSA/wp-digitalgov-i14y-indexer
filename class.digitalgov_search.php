<?php

class DigitalGov_Search {
	private static $INDEXABLE = 'digitalgov_search_indexable';

	/**
         * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
         * @static
         */
	public static function plugin_activation() {
		if (! self::requirements_met() ) {
			$message = "DigitalGov Search i14y Indexer plugin failed to activate!";
			self::failure_to_activate( $message );
		}
	}

	private static function requirements_met() {
		return true;
	}

	private static function failure_to_activate( $message ) {
		echo $message;
		exit;
	}

	public static function update_post( $post_id ) {
		$indexable = $_REQUEST['index'];
		update_post_meta( $post_id, self::$INDEXABLE, $indexable );

		$post = get_post( $post_id );
		$document = DigitalGov_Search_Document::create_from_post( $post );
		if ( $post->post_status == 'publish' && $indexable === 'yes') {
			try {
				$document->index( $post_id );
			} catch (APICouldNotIndexDocumentException $e) {
				update_option('digitalgov_search_admin_message', $e->getMessage());
				update_option('digitalgov_search_display_post_error_message', 1);
			}
		} else {
			$document->unindex( $post->ID );
		}
	}

	public static function unindex_post_when_unpublished($new_status, $old_status, $post) {
		if ( $old_status == 'publish' && $new_status != 'publish' ) {
			$document = DigitalGov_Search_Document::create_from_post( $post );
			try {
				$document->unindex( $post->ID );
			} catch (Exception $e) {
			}
		}
	}

	public static function delete_post( $post_id ) {
		$post = get_post( $post_id );
		$document = DigitalGov_Search_Document::create_from_post( $post );
		$document->unindex( $post_id );
	}

	public static function view( $name ) {
		$file = DIGITALGOV_SEARCH__PLUGIN_DIR . 'views/'. $name . '.php';
		include( $file );
	}

	public static function get_handle() {
		return get_option( 'digitalgov_search_handle' );
	}

	public static function get_token() {
		return get_option( 'digitalgov_search_token' );
	}

	public static function credentials_set() {
		return self::get_handle() && self::get_token();
	}

	public static function is_indexable( $post ) {
		$indexable = get_post_meta( $post->ID, self::$INDEXABLE, true);
		if ($indexable == 'no') return false;
		else return true;
	}
}
