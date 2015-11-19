<?php
class APICouldNotSaveDocumentException extends Exception {};

class DigitalGov_Search_Document {
	public $document_id; // required
	public $title;       // required
	public $path;        // required
	public $created;     // required
	public $content;     // optional
	public $changed;     // optional

	public static $DOCUMENT_CREATED     = 0;
	public static $DOCUMENT_UPDATED     = 1;
	public static $DOCUMENT_INDEX_ERROR = 2;
	public static $API_ERROR            = 3;

	private static $ALREADY_INDEXED = 'digitalgov_search_indexed';

	public static function create_from_post( WP_POST $post ) {
		$document = new self;
		$document->populate_from_post( $post );
		return $document;
	}

	public function populate_from_post( WP_POST $post ) {
		// use document ids like `blog-title-123`
		// if the blog name changes, this will break indexing, implicitly reindexing everything
		$this->document_id = $post->ID;
		$this->title = $post->post_title;
		$this->path = get_permalink( $post->ID );
		$this->created = $post->post_date;
		$this->content = $post->post_content;
		$this->changed = $post->post_modified;
	}

	public function set_id($id) {
		$this->document_id = $id;
	}

	public function set_path($path) {
		$this->path = $path;
	}

	public static function filter_url($url) {
		$parsed_url = parse_url($url);

		$remove_edit_from_host = function($host) {
			return str_replace('edit.', '', $host);
		};

		$parsed_url['host'] = $remove_edit_from_host($parsed_url['host']);

		return "{$parsed_url['scheme']}://{$parsed_url['host']}{$parsed_url['path']}";
	}

	public function save() {
		$already_indexed = $this->already_indexed();

		// url
		$url = 'https://i14y.usa.gov/api/v1/documents';
		if ( $already_indexed ) {
			$url .= "/{$this->document_id}";
		}

		$obj = clone $this;
		$obj->set_id(sanitize_title(get_bloginfo()) . "-" . $this->document_id);
		$obj->set_path(self::filter_url($this->path));

		// headers
		$credentials = DigitalGov_Search::get_handle() .":". DigitalGov_Search::get_token();
		$headers = array(
                        'headers' => array(
                                'Authorization' => 'Basic '.base64_encode( $credentials )
                        ),
                        'body' => $obj
                );
		$headers['method'] = ( $already_indexed ) ? 'PUT' : 'POST';

		$res = wp_remote_request( $url, $headers );
		if ( is_a( $res, 'WP_ERROR' ) ) {
			return self::$API_ERROR;
		}

		if ( $res['response']['code'] == 201 ) {
			update_post_meta( $this->document_id, self::$ALREADY_INDEXED, true );
			return self::$DOCUMENT_CREATED;
		} elseif ($res['response']['code'] == 200) {
			return self::$DOCUMENT_UPDATED;
		} else {
			$body = json_decode($res['body']);
			throw new APICouldNotSaveDocumentException($body->developer_message);
		}
	}

	public function already_indexed() {
		return get_post_meta( $this->document_id, self::$ALREADY_INDEXED, true );
	}

	public function delete() {
		if ( ! $this->already_indexed() ) {
			return false;
		}

		$url = "https://i14y.usa.gov/api/v1/documents/{$this->document_id}";

		// headers
		$credentials = DigitalGov_Search::get_handle() .":". DigitalGov_Search::get_token();
		$headers = array(
			'headers' => array(
				'Authorization' => 'Basic '.base64_encode( $credentials )
			),
			'method' => 'DELETE'
		);

		wp_remote_request( $url, $headers );
		delete_post_meta( $this->document_id, self::$ALREADY_INDEXED );

		return $this;
	}
}
