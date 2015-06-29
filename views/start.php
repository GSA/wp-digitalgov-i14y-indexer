<h2>DigitalGov Search</h2>
<p>DigitalGov Search is used to index your agency's WordPress website.</p>

<div class="activate-highlight secondary activate-option">
        <div class="option-description">
                <strong><?php esc_html_e('Enter API credentials', 'digitalgov_search'); ?></strong>
                <p><?php esc_html_e('Log in to the search administration portal to find your i14y credentials.', 'digitalgov_search'); ?></p>
        </div>
	<form action="<?php echo esc_url( DigitalGov_Search_Admin::get_page_url() ); ?>" method="post" id="digitalgov_search-enter-api-key" class="right">
		<input type="hidden" name="action" value="enter-key">
                <p><input id="handle" name="handle" type="text" size="15" value="<?php echo DigitalGov_Search::get_handle(); ?>" placeholder="i14y drawer handle" class="regular-text code"></p>
                <p><input id="token" name="token" type="text" size="15" value="<?php echo DigitalGov_Search::get_token(); ?>" placeholder="secret token" class="regular-text code"></p>
                <input type="submit" name="submit" id="submit" class="button button-secondary" value="<?php esc_attr_e('Save Credentials', 'digitalgov_search');?>">
		<?php if ( DigitalGov_Search::credentials_set() ) { ?>
			<a href="<?php echo esc_url( DigitalGov_Search_Admin::get_page_url( 'index' ) ); ?>"><span class="button button-primary"><?php esc_attr_e('Index Wordpress', 'digitalgov_search'); ?></span></a>
		<?php } ?>
        </form>
</div>
