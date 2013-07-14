<?php 

define('USERDATA', dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR);

/**
 * Log fonction it builds 3 files :
 *  - log.txt
 *  - log_error.txt
 *  - log_server.txt
 * Files are in USERDATA -> data/
 * @param  String $msg  Message to log
 * @param  string $type Type of message
 */
function TSlog($msg,$type="") {
	$name = 'log';
	if($type === "error") $name = 'log_error';
	if($type === "server") $name = 'log_server';
	file_put_contents(USERDATA.DIRECTORY_SEPARATOR.$name.'.txt',date('Y-m-d H:i:s').' '.$msg."\n",FILE_APPEND);
}

include('TSManagerIncludes.php');

Toro::serve(array(
    "/tasks" => "Tasks",
    "/tasks/:number" => "Tasks",
));





class Tasks {

	private static function response($msg,$type = 'success') {

		$data = (is_array($msg) || is_object($data)) ? $msg : array();
		echo json_encode(array(
				'status' => $type,
				'message' => $msg,
				'data' => $data
				));
		die();

	}

	public function get($id = NULL) {


		if(empty($id)) {
			$data = Db::read();
		}else{
			$data = Db::read((int)$id);
		}

		if($data) {
			Response::json($data);
		}else{
			self::response('No data','error');
		}
	}

	public function put($id) {

		$update = DB::update($id,Input::cleanArray(Input::rest()));

		if(!$update) {
			self::response('Something goes wrong', 'error');
		}else{
			self::response('Your update is a success');
		}
	}

	public function post() {
		if(DB::create(array(Input::cleanArray(Input::rest())))) {
			self::response('Yup it is ok');
		}else {
			self::response('Something goes wrong', 'error');
		}
	}

	public function delete($id) {
		if(DB::delete($id)) {
			self::response('Yup it is ok, not in database anymore');
		}else{
			self::response('Something goes wrong', 'error');
		}
	}
}