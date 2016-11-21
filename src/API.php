<?php

/**
 * Utility functions for API operations
 */

class API {

	/**
	 * Outputs JSON data with correct header
	 *
	 * @param  mixed  $data PHP data to be converted to JSON and served
	 * @return string JSON-encoded data
	 */
	public static function json($data = '') {
		self::etag($data);
		echo json_encode($data);
		header('Content-Type: application/json');
	}

	/**
	 * Stops execution and sends a response
	 *
	 * @param int    $code HTTP status code
	 * @param string $body Response body
	 */
	public static function send($code = 200, $body = '') {
		http_response_code($code);
		self::etag($body);
		echo $body;
		die;
	}

	/**
	 * Handles etag headers
	 *
	 * @param mixed $data Data to be represented by etag
	 */
	public static function etag($data) {

		// Generate key for current data
		$id = md5(serialize($data));

		// If etag sent, attempt to use it
		if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
			$etag = $_SERVER['HTTP_IF_NONE_MATCH'];

			// If given etag matches generated etag, throw 304
			if ($etag === $id) {
				http_response_code(304);
				header('Etag: ' . $etag);
				die;
			}

		}

		// Otherwise, send generated etag
		header('Etag: ' . $id);

	}

}
