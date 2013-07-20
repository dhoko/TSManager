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
	"/" => "App",
    "/tasks" => "Tasks",
    "/tasks/:number" => "Tasks",
));


class App {

	public function get() {
		Response::view('index');
	}
}



class Tasks {

	public function get($id = NULL) {
		if(empty($id)) {
			$data = Db::read();
		}else{
			$data = Db::read((int)$id);
		}

		if($data) {
			Response::json($data);
		}else{
			Response::http(400);
		}
	}

	public function put($id) {

		if(is_null($id)) Response::http(428);

		$update = DB::update($id,Input::cleanArray(Input::rest()));

		if(!$update) {
			Response::http(500);
		}
		Response::http(200);
	}

	public function post() {
		if(!DB::create(array(Input::cleanArray(Input::rest())))) {
			Response::http(412);
		}
		
		Response::http(200);
	}

	public function delete($id) {

		if(is_null($id)) Response::http(428);

		if(!DB::delete($id)) {
			Response::http(500);
		}
		Response::http(200);
	}
}