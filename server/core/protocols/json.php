<?php
/**
 * JSON sub protocol
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\protocols;

/**
 * Class json
 * You can use this trait in application\protocol
 * @package core\protocols
 */
trait json {
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