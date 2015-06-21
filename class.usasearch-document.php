<?php

class USASearch_Document {
	public $document_id; // required
	public $title;       // required
	public $path;        // required
	public $created;     // required
	public $content;     // optional
	public $changed;     // optional

	public static $ALREADY_INDEXED = 'usasearch_indexed';

	public static $DOCUMENT_CREATED     = 0;
	public static $DOCUMENT_UPDATED     = 1;
	public static $DOCUMENT_INDEX_ERROR = 2;

	public static function create_from_post( WP_POST $post ) {
		$document = new self;
		$document->populate_from_post( $post );
		return $document;
	}

	public function populate_from_post( WP_POST $post ) {
		$this->document_id = $post->ID;
		$this->title = $post->post_title;
		$this->path = get_permalink( $post->ID ); 
		$this->created = $post->post_date;
		$this->content = $post->post_content;
		$this->changed = $post->post_modified;
	}

	public function save() {
		$already_indexed = $this->already_indexed();

		// url
		$url = 'https://i14y.usa.gov/api/v1/documents';
		if ( $already_indexed ) {
			$url .= "/{$this->document_id}";
		}

		// headers
		$credentials = USASearch::get_handle() .":". USASearch::get_token();
		$headers = array(
                        'headers' => array(
                                'Authorization' => 'Basic '.base64_encode( $credentials )
                        ),
                        'body' => $this
                );
		$headers['method'] = ( $already_indexed ) ? 'PUT' : 'POST';

		$res = wp_remote_request( $url, $headers );

		if ( $res['response']['code'] == 201) {
			update_post_meta( $this->document_id, self::$ALREADY_INDEXED, true );
			return self::$DOCUMENT_CREATED;
		} elseif ($res['response']['code'] == 200) {
			return self::$DOCUMENT_UPDATED;
		} else {
			return self::$DOCUMENT_INDEX_ERROR;
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
		$credentials = USASearch::get_handle() .":". USASearch::get_token();
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
