<?php
	global $post;
	$indexable = DigitalGov_Search::is_indexable( $post );
	function selected_option($indexable, $check = true) {
		if ($indexable == $check) {
			return 'checked';
		}
		return '';
	}
?>
<div id="minor">
	<h3>Should this page be indexed?</h3>
	<p><input type="radio" name="index" value="yes" <?php echo selected_option($indexable); ?>> Yes</p>
	<p><input type="radio" name="index" value="no"<?php echo selected_option($indexable, false); ?>> No</p>
</div>
<div id="major-publishing-actions">
	<div id="publishing-actions">
		<input name="save" type="submit" class="button button-primary button-large right" id="publish" value="Update">
	</div>
	<div class="clear"></div>
</div>
