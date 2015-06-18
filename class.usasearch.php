<?php

class USASearch {
	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		echo "this was a disaster";
		exit;
	}
}
