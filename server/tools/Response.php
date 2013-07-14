<?php
class Response {

	/**
	 * Manage the view rendering. You can customize the HTTP status code
	 * @param  Array  $data   Data to JSON
	 * @param  integer $status HTTP status code
	 * @return String          View
	 */
	private static function render($data, $status = 200) {

		$codes = array(
			'200' => 'OK',
			'201' => 'Created',
			'204' => 'No Content',
			'400' => 'Bad Request',
			'401' => 'Unauthorized',
			'402' => 'Payment Required',
			'403' => 'Forbidden',
			'404' => 'Not Found',
			'405' => 'Method Not Allowed',
			'412' => 'Precondition Failed',
			'428' => 'Precondition Required',
			'500' => 'Internal Server Error',
			'501' => 'Not Implemented',
		);

		$message = (isset($codes[(string)$status])) ? $codes[(string)$status] : 'You should not pass';
		ob_start();
		ob_implicit_flush(false);
		require(ROOT . 'server/ajax.php');
		return ob_get_clean();
	}

	/**
	 * Render a valid JSON to your client please.
	 * HTTP headers are here
	 * @param  Array  $data   
	 * @param  integer $status HTTP status code
	 * @return void
	 */
	public static function json($data, $status =  200) {
		echo self::render($data, $status);
		die();
	}

}