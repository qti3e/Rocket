<?php
/**
 * Redis driver for logger library
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\logger\drivers;

use core\config\config;
use core\logger\driverInterface;
use core\redis\redis as cRedis;

/**
 * Class redis
 * @package core\logger\drivers
 */
class redis implements driverInterface {
	/**
	 * Instance of the Redis library
	 * @var cRedis
	 */
	protected static $instance;

	/**
	 * Make a connection to the logger's Redis server
	 */
	public function __construct(){
		$host       = config::get('logger_redis_host');
		$port       = config::get('logger_redis_port');
		$timeout    = config::get('logger_redis_timeout');
		$db         = config::get('logger_redis_timeout');
		$persistent = config::get('logger_redis_persistent');
		$password   = config::get('logger_redis_password');
		static::$instance   = new cRedis($host,$port,$timeout,$persistent,$db,$password);
	}

	/**
	 * Write a info to the log database
	 * @param $message
	 * @param $line
	 * @param $file
	 * @param $time
	 *
	 * @return string
	 */
	public function infoWriter($message, $line, $file, $time) {
		$id = hash(config::get('logger_redis_hash_algo'),uniqid('log_i_'));
		static::$instance->hMSet($id,[
			'message'   => $message,
			'line'      => $line,
			'file'      => $file,
			'time'      => $time
		]);
		static::$instance->lPush('logs',$id);
		static::$instance->lPush('info',$id);
		return $id;
	}

	/**
	 * Write a warning to the database
	 * @param $message
	 * @param $line
	 * @param $file
	 * @param $time
	 *
	 * @return string
	 */
	public function warningWriter($message, $line, $file, $time) {
		$id = hash(config::get('logger_redis_hash_algo'),uniqid('log_w_'));
		static::$instance->hMSet($id,[
			'message'   => $message,
			'line'      => $line,
			'file'      => $file,
			'time'      => $time
		]);
		static::$instance->lPush('logs'     ,$id);
		static::$instance->lPush('warning'  ,$id);
		return $id;
	}

	/**
	 * Write a error to the error database
	 * @param $message
	 * @param $line
	 * @param $file
	 * @param $time
	 *
	 * @return string
	 */
	public function errorWriter($message, $line, $file, $time) {
		$id = hash(config::get('logger_redis_hash_algo'),uniqid('log_e_'));
		static::$instance->hMSet($id,[
			'message'   => $message,
			'line'      => $line,
			'file'      => $file,
			'time'      => $time
		]);
		static::$instance->lPush('logs'     ,$id);
		static::$instance->lPush('errors'   ,$id);
		return $id;
	}

	/**
	 * Return first few items
	 * @param $limit
	 *
	 * @return array
	 */
	public function getAll($limit) {
		$limit  = $limit <= 0 ? -1 : $limit;
		$logs   = static::$instance->lRange('logs',0,$limit);
		$count  = count($logs);
		$return = [];
		for($i  = 0;$i < $count;$i++){
			$return[]   = static::$instance->hGetAll($logs[$i]);
		}
		return $return;
	}
}