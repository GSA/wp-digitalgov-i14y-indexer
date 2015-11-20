<h2>DigitalGov Search i14y Indexer</h2>
<p>DigitalGov Search is used to index your agency's WordPress website's content with the DigitalGov Search search service.</p>

<pre>
<?php

	class WordPressCouldNotConnectToAPIException extends Exception {};
	$MAX_ATTEMPTS_PER_POST = 3;
	$args = array(
		'post_type' => 'any',
		'post_status' => 'publish',
		'posts_per_page' => -1
	);
        $posts_array = get_posts( $args );

	function indexDocument($post_id, $document) {
		switch( $document->index( $post_id ) ) {
			case $document::$DOCUMENT_CREATED:
				$status = "Created document";
			break;
			case $document::$DOCUMENT_UPDATED:
				$status = "Updated document";
			break;
			case $document::$API_ERROR:
				$status = "API Error: Failed to index";
				throw new WordPressCouldNotConnectToAPIException($status);
			break;
		}
		echo "<span style=\"color:#7ad03a\">{$status} \"{$document->title}\" (post id {$document->document_id})" . "</span>\n";
	}

	function attemptToIndexDocument($post_id, $document, $attempt) {
			try {
				indexDocument($post_id, $document);
			} catch (APICouldNotIndexDocumentException $e) {
				echo "<span style=\"color:#dd3d36\">Error indexing document \"{$document->title}\": {$e->getMessage()}</span>\n";
			} catch (WordPressCouldNotConnectToAPIException $e) {
				echo "{$e->getMessage()} {$document->title}.";
				if (++$attempt < $MAX_ATTEMPTS_PER_POST) {
					echo " Trying again...";
					attemptToIndexDocument($post_id, $document, $attempt);
				}
				echo "\n";
			}
	}

        foreach($posts_array as $post) {
		try {
			$document = DigitalGov_Search_Document::create_from_post( $post );
			attemptToIndexDocument($post->ID, $document, 0);
		} catch (Exception $e) {
			echo "Unknown Exception: {$e->getMessage()}";
		}
		continue;
        }
?>
</pre>

<p>Indexing complete!</p>
<a href="<?php echo esc_url( DigitalGov_Search_Admin::get_page_url() ); ?>"><span class="button button-primary"><?php esc_attr_e('Finish', 'digitalgov_search'); ?></span></a>
