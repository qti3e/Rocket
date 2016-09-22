<?php
/**
 * All of WebSocket sub-protocols are here
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace application;

/**
 * Class protocol
 * @package application
 */
class protocol {
	/**
	 *  Default protocol name
	 */
	const def  = "json";

	/**
	 * Json encode function
	 * @param mixed $value
	 *
	 * @return string
	 */
	public static function json($value){
		return json_encode($value);
	}

	/**
	 * Json decode function
	 * @param string $json
	 *
	 * @return mixed
	 */
	public static function json_decode($json){
		return self::json_decode($json);
	}
}