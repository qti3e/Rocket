<?php
/**
 * Applications interface
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\rocket;

/**
 * Interface app
 * @package core\rocket
 */
interface app{
	/**
	 * Handle messages
	 * @param $user
	 * @param $message
	 *
	 * @return mixed
	 */
	public function onMessage($user,$message);

	/**
	 * Handle connection
	 * @param $user
	 *
	 * @return mixed
	 */
	public function connected($user);

	/**
	 * Handle disconnection
	 * @param $user
	 *
	 * @return mixed
	 */
	public function disconnected($user);
}