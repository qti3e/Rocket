<?php
/**
 * This file contains objInterface interface
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\rocket;

/**
 * Interface objInterface
 *  This class only contains run
 * @package core\rocket
 */
interface objInterface {
	/**
	 * This function will call when we wan't to start program
	 * @return mixed
	 */
	public function run();
}