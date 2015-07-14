<h2>DigitalGov Search</h2>
<p>DigitalGov Search is used to index your agency's WordPress website.</p>

<pre>
<?php
	$args = array( 'post_type' => 'any', 'post_status' => 'publish', 'posts_per_page' => -1);
        $posts_array = get_posts( $args );

        foreach($posts_array as $post) {
                $document = DigitalGov_Search_Document::create_from_post( $post );
		switch( $document->save() ) {
			case $document::$DOCUMENT_CREATED:
				$status = "Created document";
			break;
			case $document::$DOCUMENT_UPDATED:
				$status = "Updated document";
			break;
			case $document::$DOCUMENT_INDEX_FAILURE:
				$status = "Failed to index";
			break;
		}
		echo "{$status}: {$document->title}\n";
        }
?>
</pre>

<p>Indexing complete!</p>
<a href="<?php echo esc_url( DigitalGov_Search_Admin::get_page_url() ); ?>"><span class="button button-primary"><?php esc_attr_e('Finish', 'digitalgov_search'); ?></span></a>
