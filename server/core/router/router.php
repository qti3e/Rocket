<?php
/**
 * This file contains a class that is parent class for all request routers
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\router;

/**
 * Class router
 * Parent class for managing messages and connections
 * Router class (application\router) must contains these 3 functions:
 *      * connected
 *      * disconnected
 *      * process
 *
 * @package core\router
 */
class router {
	/**
	 * Will call when a new socket connection creates
	 *
	 * @param $user
	 *
	 * @return void
	 */
	public static function connected($user){}

	/**
	 * Will call when a socket close
	 *
	 * @param $user
	 *
	 * @return void
	 */
	public static function disconnected($user){}

	/**
	 * Will call when user send a message and handle message
	 *
	 * @param $user
	 * @param $message
	 *
	 * @return void
	 */
	public static function process($user,$message){}

	public static function route($class,$method,$params){

	}
}