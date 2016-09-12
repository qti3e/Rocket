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
}