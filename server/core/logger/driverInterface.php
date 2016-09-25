<?php
/**
 * A simple driver interface
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\logger;

/**
 * Interface driverInterface
 * @package core\logger
 */
interface driverInterface {
	/**
	 * Write a new info in the log system
	 * @param $message
	 * @param $line
	 * @param $file
	 * @param $time
	 *
	 * @return mixed
	 */
	public function infoWriter($message,$line,$file,$time);

	/**
	 * Write a new warning in the log system
	 * @param $message
	 * @param $line
	 * @param $file
	 * @param $time
	 *
	 * @return mixed
	 */
	public function warningWriter($message,$line,$file,$time);

	/**
	 * Write a new error in the log system
	 * @param $message
	 * @param $line
	 * @param $file
	 * @param $time
	 *
	 * @return mixed
	 */
	public function errorWriter($message,$line,$file,$time);

	/**
	 * Return some recent logs
	 * @param $limit
	 *  The limitation of return array sometimes you just need last 20 items
	 *  When limit is 0 or less, method must return all of stored logs
	 * @return array
	 */
	public function getAll($limit);
}