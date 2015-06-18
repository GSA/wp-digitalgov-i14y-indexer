<?php

class USASearch {
	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		if (! self::requirements_met()) {
			$message = "this was a disaster.";
			self::failure_to_activate( $message );
		}
	}

	private static function requirements_met() {
		return true;
	}

	private static function failure_to_activate( $message ) {
		echo $message;
		exit;
	}

	public static function view( $name ) {
		$file = USASEARCH__PLUGIN_DIR . 'views/'. $name . '.php';
		include( $file );
	}
}
