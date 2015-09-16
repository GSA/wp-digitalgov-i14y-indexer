<h2>DigitalGov Search</h2>
<p>DigitalGov Search is used to index your agency's WordPress website.</p>

<pre>
<?php
	$MAX_ATTEMPTS_PER_POST = 3;
	$args = array(
		'post_type' => 'any',
		'post_status' => 'publish',
		'posts_per_page' => -1
	);
        $posts_array = get_posts( $args );

	function saveDocument($document) {
		$retry = false;
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
				$retry = true;
			break;
		}
		$message = "{$status}: {$document->title}";

		if ($retry) {
			throw new Exception($status);
		} else {
			echo $message . "\n";
		}
	}

        foreach($posts_array as $post) {
		$attempts = 0;
                $document = DigitalGov_Search_Document::create_from_post( $post );

		do {
			try {
				saveDocument($document);
			} catch (Exception $e) {
				$attempts++;
				echo "{$e->getMessage()} {$document->title}.";
				if ($attempts < $MAX_ATTEMPTS_PER_POST) {
					echo " Trying again...";
				}
				echo "\n";
				continue;
			}
			break;
		} while ($attempts < $MAX_ATTEMPTS_PER_POST);
        }
?>
</pre>

<p>Indexing complete!</p>
<a href="<?php echo esc_url( DigitalGov_Search_Admin::get_page_url() ); ?>"><span class="button button-primary"><?php esc_attr_e('Finish', 'digitalgov_search'); ?></span></a>
