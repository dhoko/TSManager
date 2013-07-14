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

Toro::serve(array(
    "/tasks" => "Tasks",
    "/tasks/:number" => "Tasks",
));

class Input {

	public static function rest() {
		return json_decode(trim(file_get_contents('php://input')), true);
	}

	public static function clean($txt){
		return htmlspecialchars(trim($txt),ENT_QUOTES);
	}

	public static function cleanArray($array) {
		return array_map(function($e) {
		    return self::clean($e);
		},$array);
	}
}



class Tasks {
	public function get($id = NULL) {
		// $data = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'list.json');

		// $db = new Db();

		// var_dump(Db::create(json_decode($data)));
		// var_dump(Db::read());
		// var_dump(json_decode($data));
		// 
		// 
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
		DB::create(array(Input::cleanArray(Input::rest())));
	}
}