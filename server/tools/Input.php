<?php
class Input {

	/**
	 * Return request params for PUT|DELETE|PATCH request
	 * @return Array Associative array
	 */
	public static function rest() {
		return json_decode(trim(file_get_contents('php://input')), true);
	}

	/**
	 * Clean a string to remove some shit
	 * @param  String $txt 
	 * @return String
	 */
	public static function clean($txt){
		return htmlspecialchars(trim($txt),ENT_QUOTES);
	}

	/**
	 * Usefull to clean an associative array in one shot
	 * @param  Array $array Associatuive array such as self::rest()
	 * @return Array
	 */
	public static function cleanArray($array) {
		return array_map(function($e) {
		    return self::clean($e);
		},$array);
	}

}