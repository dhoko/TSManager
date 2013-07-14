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

class DB {

	private $db = null;

	public function __construct() {
		try {
			if(!file_exists(USERDATA.'tsmanager.sqlite')) {
				$this->db = new PDO('sqlite:'.USERDATA.'tsmanager.sqlite');

				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//create the database
				$this->db->exec("CREATE TABLE IF NOT EXISTS Tasks (
					id INTEGER PRIMARY KEY,
					name VARCHAR(255), 
					url VARCHAR(255), 
					branch_name VARCHAR(255), 
					branch_url VARCHAR(255), 
					description VARCHAR(255), 
					done INTEGER,
					production INTEGER,
					user VARCHAR(100),
					added_time DATETIME DEFAULT CURRENT_TIMESTAMP
				);"); 
				
				return $this->db;
			}else {
				$this->db = new PDO('sqlite:'.USERDATA.'tsmanager.sqlite');
				return $this->db;
			}
			 
		} catch (Exception $e) {
			TSlog($e->getMessage(),'error');
		}

	}

	public static function model() {

		$db = new DB();
		return $db->db;
	}

	public static function read($id = null, $condition) {
		
		if(is_null($id)) return self::findAll();
		if(isset($id)) return self::findByPk($id);
	}

	private static function findByPk($id) { 
		$req = self::model()->prepare('SELECT * FROM Tasks WHERE id = :id;');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetch(PDO::FETCH_OBJ);
	}
	private static function findAll() {
		$req = self::model()->prepare('SELECT * FROM Tasks;');
		$req->execute();
		return $req->fetchAll(PDO::FETCH_OBJ);
	}

	public static function create(Array $data) {

		$inserts = array();
		$max = count($data);
		$keys = array();
		$tmp = array();

		for ($i=0; $i < $max ; $i++) { 

			foreach ($data[$i] as $key => $value) {

				if($i === 0) {
					if( $key !== 'id' ) $keys[] = trim($key);
				}

				if( $key !== 'id' ) {

					if($key === 'done' || $key === 'production') $value = (int)$value;
					$tmp[] = (string)$value;

				}
			}
			$inserts[] = '("'.implode('","', $tmp).'")';
			$tmp = array();
		}

		$sql = "INSERT INTO Tasks (%s) VALUES %s;";
		$sql = sprintf($sql,implode(',', $keys),implode(',', $inserts));

		TSlog('SQLLITE Insert data to DB - ' . $sql);
		self::model()->exec($sql);
		return self::model()->lastInsertId();

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