<?php

class USASearch_Document {
	public $document_id;          // required
	public $title;       // required
	public $path;        // required
	public $created;     // required
	public $content;     // optional
	public $changed;     // optional

	public static function create( WP_POST $post ) {
		$document = new self;
		$document->populate_from_post( $post );
		return $document;
	}

	private function populate_from_post( WP_POST $post ) {
		$this->document_id = $post->ID;
		$this->title = $post->post_title;
		$this->path = get_permalink( $post->ID ); 
		$this->created = $post->post_date;
		$this->content = $post->post_content;
		$this->changed = $post->post_modified;
	}

	public function save() {
		print_r( $this );
	}
}
