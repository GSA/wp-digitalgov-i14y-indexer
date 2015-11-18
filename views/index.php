<h2>DigitalGov Search</h2>
<p>DigitalGov Search is used to index your agency's WordPress website.</p>

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

	function saveDocument($document) {
		switch( $document->save() ) {
			case $document::$DOCUMENT_CREATED:
				$status = "Created document";
			break;
			case $document::$DOCUMENT_UPDATED:
				$status = "Updated document";
			break;
			case $document::$DOCUMENT_INDEX_ERROR:
				$status = "Failed to index";
			break;
			case $document::$API_ERROR:
				$status = "API Error: Failed to index";
				throw new WordPressCouldNotConnectToAPIException($status);
			break;
		}
		echo "{$status}: {$document->title}" . "\n";
	}

	function attemptToSaveDocument($document, $attempt) {
			try {
				saveDocument($document);
			} catch (APICouldNotSaveDocumentException $e) {
				echo "Error saving document: {$e->getMessage()}";
			} catch (WordPressCouldNotConnectToAPIException $e) {
				echo "{$e->getMessage()} {$document->title}.";
				if (++$attempt < $MAX_ATTEMPTS_PER_POST) {
					echo " Trying again...";
					attemptToSaveDocument($document, $attempt);
				}
				echo "\n";
			}
	}

        foreach($posts_array as $post) {
		try {
			$document = DigitalGov_Search_Document::create_from_post( $post );
			attemptToSaveDocument($document, 0);
		} catch (Exception $e) {
			echo "Unknown Exception: {$e->getMessage()}";
		}
		continue;
        }
?>
</pre>

<p>Indexing complete!</p>
<a href="<?php echo esc_url( DigitalGov_Search_Admin::get_page_url() ); ?>"><span class="button button-primary"><?php esc_attr_e('Finish', 'digitalgov_search'); ?></span></a>
