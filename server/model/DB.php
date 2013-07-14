<?php
class DB {

	/**
	 * Store the Database
	 * @var PdoObject
	 */
	private $db = null;

	public function __construct() {
		try {

			// If we already build our DB we do not need to Create a table
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
				// Serve the dB like a sir
				$this->db = new PDO('sqlite:'.USERDATA.'tsmanager.sqlite');
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $this->db;
			}
			 
		} catch (Exception $e) {
			TSlog($e->getMessage(),'error');
		}

	}

	/**
	 * Return an instance of the model
	 * @return Object PDO
	 */
	public static function model() {
		$db = new DB();
		return $db->db;
	}

	/**
	 * Return a list of data from the DB
	 * @param  Integer $id        Not required
	 * @param  String  $condition Custom filter
	 * @return Array|Object       A row or multiples rows
	 */
	public static function read($id = null, $condition = '') {
		
		if(is_null($id)) return self::findAll();
		if(isset($id)) return self::findByPk($id);
	}

	/**
	 * Find an element by its primary key in the database
	 * @param  Integer $id 
	 * @return Object     A row
	 */
	private static function findByPk($id) { 
		$req = self::model()->prepare('SELECT * FROM Tasks WHERE id = :id;');
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetch(PDO::FETCH_OBJ);
	}

	/**
	 * Find All elements from database
	 * @return Array     [Object]
	 */
	private static function findAll() {
		$req = self::model()->prepare('SELECT * FROM Tasks;');
		$req->execute();
		return $req->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Create a new row in database or multiples
	 * @param  Array  $data Array of associative array
	 * @return Integer      Last recorded row
	 */
	public static function create(Array $data) {

		$inserts = array();
		$keys = array();
		$tmp = array();
		$max = count($data);

		// Loop on each array in Data
		for ($i=0; $i < $max ; $i++) { 

			// For each one we build an array of params to record
			foreach ($data[$i] as $key => $value) {

				// First time, we build wich keys we need
				if($i === 0) {
					if( $key !== 'id' ) $keys[] = trim($key);
				}

				// Never record an id
				if( $key !== 'id' ) {

					// Sometimes we can record boolean so we convert them
					if($key === 'done' || $key === 'production') $value = (int)$value;
					// Push params
					$tmp[] = trim((string)$value);

				}
			}
			// Build an insert params : (param1,param2...)
			$inserts[] = '("'.implode('","', $tmp).'")';
			// Flush data
			$tmp = array();
		}

		// Bind keys and values to a request
		$sql = "INSERT INTO Tasks (%s) VALUES %s;";
		$sql = sprintf($sql,implode(',', $keys),implode(',', $inserts));

		TSlog('SQLLITE Insert data to DB - ' . $sql);

		// Ready sir
		self::model()->exec($sql);
		return self::model()->lastInsertId();

	}

}