<?php

class DigitalGov_Search_API {

	public static $API_URL = 'https://i14y.usa.gov/api/v1/documents';

	public static function index_new_document($document) {
		$url = self::$API_URL;
		$credentials = DigitalGov_Search::get_handle() .":". DigitalGov_Search::get_token();
		$headers = array(
                        'headers' => array(
                                'Authorization' => 'Basic '.base64_encode( $credentials )
                        ),
			'method' => 'POST',
                        'body' => $document
                );
		$headers['method'] = 'POST';

		return wp_remote_request( $url, $headers );
	}

	public static function update_existing_document($document) {
		$url = self::$API_URL . "/{$document->document_id}";
		$credentials = DigitalGov_Search::get_handle() .":". DigitalGov_Search::get_token();
		$headers = array(
                        'headers' => array(
                                'Authorization' => 'Basic '.base64_encode( $credentials )
                        ),
			'method' => 'PUT',
                        'body' => $document
                );

		return wp_remote_request( $url, $headers );
	}

	public static function unindex_document($document) {
		$url = "https://i14y.usa.gov/api/v1/documents/{$document->document_id}";

		// headers
		$credentials = DigitalGov_Search::get_handle() .":". DigitalGov_Search::get_token();
		$headers = array(
			'headers' => array(
				'Authorization' => 'Basic '.base64_encode( $credentials )
			),
			'method' => 'DELETE'
		);

		return wp_remote_request( $url, $headers );
	}
}
