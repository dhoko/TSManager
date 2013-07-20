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
		return self::renderFile('ajax.php','server',array(
			'status' => $status,
			'message' => $message,
			'data' => $data
			));
	}

	/**
	 * Build a view from a static file and inject data
	 * @param  string $file   view Filename
	 * @param  string $from   view folder
	 * @param  array  $inject data to inject in the view
	 * @return string         the view
	 */
	private static function renderFile($file,$from, Array $inject = array()) {

		// Inject custom var to the view
		if(!empty($inject)) extract($inject);

		ob_start();
		ob_implicit_flush(false);
		require(ROOT . $from.DIRECTORY_SEPARATOR.$file);
		return ob_get_clean();
	}

	/**
	 * Render a view
	 * @param  string $viewName View filename
	 * @param  array  $data     Data to inject
	 * @return void
	 */
	public static function view($viewName,Array $data = array()) {
		echo self::renderFile($viewName.'.html','app',$data);
		die();
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

	/**
	 * Return a HTTP header
	 * @param  integer $httpCode HTTP code 
	 * @return void
	 */
	public static function http($httpCode) {
		$data = array();
		echo self::render($data, $httpCode);
		die();
	}

}