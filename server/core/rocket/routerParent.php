<?php
/**
 * Your router function must be looh like this class
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
	 * @return bool
	 */
	public function checkHost($hostName){return true;}

	/**
	 * Check user ip (for blocking)
	 * @param $ip
	 *
	 * @return bool
	 */
	public function checkIP($ip){return true;}

	/**
	 * Check GET address
	 * @param $app
	 *
	 * @return bool
	 *
	 */
	public function checkApplication($app){return true;}

}