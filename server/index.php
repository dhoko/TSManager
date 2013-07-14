<?php 
require '../vendor/autoload.php';

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
	public function get($id = NULL) {

		if(empty($id)) {
			echo json_encode(Db::read());
		}else{
			echo json_encode(Db::read((int)$id));
		}
	}

	public function put($id) {
		print_r(Input::rest());
	}

	public function post() {
		if(DB::create(array(Input::cleanArray(Input::rest())))) {
			echo json_encode(array(
				'status' => 'success',
				))
		}else {
			echo json_encode(array(
				'status' => 'error',
				))
		}
	}

	public function delete($id) {
		DB::create(array(Input::cleanArray(Input::rest())));
	}
}