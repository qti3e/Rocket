<?php
/**
 * This is sample router file
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace application;

use core\rocket\routerParent;

/**
 * Class router
 *
 * @package application
 */
class router extends routerParent {
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
	 * Add some custom headers to the response header
	 * @return array
	 */
	public function customHeaders(){return [];}
}