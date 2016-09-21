<?php
/**
 * Your router function must be extends of this class
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\rocket;

/**
 * Class routerParent
 * @package core\rocket
 */
abstract class routerParent {
	/**
	 * Check user origin
	 * @param $origin
	 *
	 * @return bool
	 */
	public function checkOrigin($origin){return true;}

	/**
	 * Check user host name before connection
	 * @param $hostName
	 *
	 * @return void
	 */
	public function checkHost($hostName){}

	/**
	 * Check user ip (for blocking)
	 * @param $ip
	 *
	 * @return bool
	 */
	public function checkIP($ip){return true;}

	/**
	 * Add some custom headers to the response header
	 * @return array
	 */
	public function customHeaders(){return [];}

	/**
	 * Check GET address
	 * @param $app
	 *
	 * @return bool
	 *
	 */
	public function checkApplication($app){return true;}

	/**
	 * Process new users
	 * @return void
	 */
	public function connected(){}

	/**
	 * Process all of messages
	 * @return void
	 */
	public function process(){}

	/**
	 * Process close status
	 * @return void
	 */
	public function closed(){}

	/**
	 * Check client protocol
	 * @param array $protocol
	 *  List of all sub-protocols that client supports
	 * @return bool
	 * Return false when you don't support any of them
	 */
	public function checkProtocol($protocol){return true;}

	/**
	 * Select a protocol to speak with client
	 * @param $protocol
	 *  List of all sub-protocols that client supports
	 *
	 * @return string
	 */
	public function processProtocol($protocol){return '';}
}