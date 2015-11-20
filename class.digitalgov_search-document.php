<?php
class APICouldNotIndexDocumentException extends Exception {};

class DigitalGov_Search_Document {
	public $document_id; // required
	public $title;       // required
	public $path;        // required
	public $created;     // required
	public $content;     // optional
	public $changed;     // optional

	public static $DOCUMENT_CREATED     = 0;
	public static $DOCUMENT_UPDATED     = 1;
	public static $API_ERROR            = 2;
	public static $DOCUMENT_UNINDEXED   = 3;

	private static $ALREADY_INDEXED = 'digitalgov_search_indexed';
	private static $DOCUMENT_ID = 'digitalgov_search_document_id';

	public static function create_from_post( WP_POST $post ) {
		$document = new self;
		$document->populate_from_post( $post );
		return $document;
	}

	public function populate_from_post( WP_POST $post ) {
		// use document ids like `blog-title-123`
		// if the blog name changes, this will break indexing, implicitly reindexing everything
		$this->document_id = self::get_document_id($post->ID);
		$this->title = $post->post_title;
		$this->path = self::apply_url_filters(get_permalink( $post->ID ));
		$this->created = $post->post_date;
		$this->content = $post->post_content;
		$this->changed = $post->post_modified;
	}

	public static function apply_url_filters($url) {
		$parsed_url = parse_url($url);

		$remove_edit_from_host = function($host) {
			return str_replace('edit.', '', $host);
		};

		$parsed_url['host'] = $remove_edit_from_host($parsed_url['host']);

		return "{$parsed_url['scheme']}://{$parsed_url['host']}{$parsed_url['path']}";
	}


	public function index( $post_id ) {
		$already_indexed = self::already_indexed( $post_id );

		$res = ( $already_indexed ) ? DigitalGov_Search_API::update_existing_document($this) : DigitalGov_Search_API::index_new_document($this);
		if ( is_a( $res, 'WP_ERROR' ) ) {
			return self::$API_ERROR;
		}

		if ( $res['response']['code'] == 201 ) {
			update_post_meta( $post_id, self::$ALREADY_INDEXED, true );
			update_post_meta( $post_id, self::$DOCUMENT_ID, $this->document_id );
			return self::$DOCUMENT_CREATED;
		} elseif ($res['response']['code'] == 200) {
			update_post_meta( $post_id, self::$ALREADY_INDEXED, true );
			return self::$DOCUMENT_UPDATED;
		} else {
			$body = json_decode($res['body']);
			throw new APICouldNotIndexDocumentException($body->developer_message);
		}
	}

	public static function already_indexed( $post_id ) {
		return get_post_meta( $post_id, self::$ALREADY_INDEXED, true );
	}

	public static function get_document_id( $post_id ) {
		if (self::already_indexed($post_id)) {
			return get_post_meta( $post_id, self::$DOCUMENT_ID, true );
		} else {
			return self::create_document_id($post_id);
		}
	}

	public function create_document_id($post_id) {
		return sanitize_title(get_bloginfo()) . "-" . $post_id;
	}

	public function unindex( $post_id ) {
		if ( ! self::already_indexed( $post_id ) ) {
			return false;
		}

		DigitalGov_Search_API::unindex_document($this);

		delete_post_meta( $this->document_id, self::$ALREADY_INDEXED );

		return self::$DOCUMENT_UNINDEXED;
	}
}
