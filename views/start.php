<h3>hello world.</h3>

<pre>
<?php
        $posts_array = get_posts();

	foreach($posts_array as $post) {
		$document = USASearch_Document::create( $post );
		$document->save();
	}
?>
</pre>
