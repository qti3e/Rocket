<?php
/**
 * Manage socket connections and handle all of messages
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\rocket;


use core\ws\WebSocketServer;
use core\ws\WebSocketUser;

/**
 * Class socket
 * Manage socket connection based on the WebSocketServer library
 * @see     WebSocketServer
 * @package core\rocket
 */
class socket extends WebSocketServer implements objInterface{
	/**
	 * This class will call when a new socket connection opens
	 *
	 * @param WebSocketUser $user
	 *  User interface
	 * @return void
	 */
	protected function connected(WebSocketUser $user) {

	}
	protected function closed(WebSocketUser $user) {
		// TODO: Implement closed() method.
	}
	protected function process(WebSocketUser $user, $message) {
		// TODO: Implement process() method.
	}
}