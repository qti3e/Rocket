<?php
/**
 * All of WebSocket sub-protocols are here
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace application;

use core\protocols\json;

/**
 * Class protocol
 * @package application
 */
class protocol {
	use json;
	/**
	 *  Default protocol name
	 */
	const def  = "json";
}