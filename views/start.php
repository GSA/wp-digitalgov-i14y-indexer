<h3>hello world.</h3>
<pre>
stuff:

<?php
	echo USASearch::get_handle() . "\n";
	echo USASearch::get_token();
?>
</pre>

<pre>
<?php
        $posts_array = get_posts();

	foreach($posts_array as $post) {
		$document = USASearch_Document::create( $post );
		if ($document->save()) {
			echo "created or updated\n";
		}

		if ( $document->delete() ) {
			echo "deleted\n";
		};
	}
?>
</pre>

<?php if ( ! USASearch_Admin::get_server_connectivity() ) { ?>
<div class="wrap alert critical">
        <h3 class="key-status failed"><?php esc_html_e("We can&#8217;t connect to your site.", 'akismet'); ?></h3>
        <p class="description"><?php printf( __('Your firewall may be blocking us. Please contact your host and refer to <a href="%s" target="_blank">our guide about firewalls</a>.', 'akismet'), 'http://blog.akismet.com/akismet-hosting-faq/'); ?></p>
</div>
<?php } ?>

<div class="activate-highlight secondary activate-option">
        <div class="option-description">
                <strong><?php esc_html_e('Manually enter an API key', 'usasearch'); ?></strong>
                <p><?php esc_html_e('If you already know your API key.', 'usasearch'); ?></p>
        </div>
	<form action="<?php echo esc_url( USASearch_Admin::get_page_url() ); ?>" method="post" id="usasearch-enter-api-key" class="right">
		<input type="hidden" name="action" value="enter-key">
                <p><input id="handle" name="handle" type="text" size="15" value="" class="regular-text code"></p>
                <p><input id="token" name="token" type="text" size="15" value="" class="regular-text code"></p>
                <input type="submit" name="submit" id="submit" class="button button-secondary" value="<?php esc_attr_e('Use this key', 'usasearch');?>">
        </form>
</div>
