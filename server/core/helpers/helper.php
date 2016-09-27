<?php
/**
 * This file contains helper class.
 *
 * @see     helper
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\helpers;

/**
 * Class helper
 * This class contains some useful helper functions, you might need.
 *  All of functions are static methods
 *
 * @package core\helpers
 */
class helper {
	/**
	 * Make a url that works both on Unix bases (Linux and Mac) and Windows
	 *  In Linux, the path separator is /, and in Windows, it is either \ or /.
	 *  So we just use forward slashes (/) and the path will be fine in both OS.
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	public static function url($url){
		return str_replace('\\','/',$url);
	}

	/**
	 * Convert simple search pattern to regex patterns
	 *  Symbols:
	 *      * excepts any character with any length
	 *      + excepts only one character
	 * @param $pattern
	 *
	 * @return mixed
	 */
	public static function pattern2regex($pattern){
		//* -> except any thing with any length
		//? -> except only one charter
		$pattern    = preg_replace('/[^\/]*/','.*',$pattern);
		return '/^'.preg_replace('/[^\/]?/','.',$pattern).'$/';
	}

	/**
	 * Check simple search pattern
	 * @see   pattern2regex
	 * @param $pattern
	 * @param $subject
	 *
	 * @return bool
	 */
	public static function checkPattern($pattern,$subject){
		$regex  = self::pattern2regex($pattern);
		return (bool)preg_match($regex,$subject);
	}
}