<?php
/**
 * Simple logger file
 *  Now only supports Redis as storage
 *  I wil write driver
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\logger;

use core\config\config;

/**
 * Class logger
 * @todo write these methods
 *  * getInfoLogs
 *  * getWarningLogs
 *  * getErrorLogs
 *  * clear
 * @todo add optional start from to all get methods for better page navigate
 * @package core\logger
 */
class logger {
	/**
	 * When it's true it means driver loaded
	 * @var bool
	 */
	protected static $driverLoaded  = false;
	/**
	 * The driver object
	 * @var driverInterface
	 */
	protected static $driver;

	/**
	 * Create driver instance
	 * @return void
	 */
	protected static function load(){
		static::$driver         = new (config::get('logger_driver'));
		static::$driverLoaded   = true;
	}
	/**
	 * Write an system info to logs
	 *  This type of logs are only for give some news about system status
	 *  Like: Server started at 9:30
	 * @param $message
	 *
	 * @return void
	 */
	public static function info($message){
		if(!static::$driverLoaded)
			static::load();
		$backtrace  = debug_backtrace()[0];
		$line       = $backtrace['line'];
		$file       = $backtrace['file'];
		$time       = time();
		static::$driver->infoWriter($message,$line,$file,$time);
	}

	/**
	 * Write a runtime warning to logs
	 * @param $message
	 *
	 * @return void
	 */
	public static function warning($message){
		if(!static::$driverLoaded)
			static::load();
		$backtrace  = debug_backtrace()[0];
		$line       = $backtrace['line'];
		$file       = $backtrace['file'];
		$time       = time();
		static::$driver->warningWriter($message,$line,$file,$time);
	}

	/**
	 * Write an error to logs
	 * @param $message
	 *
	 * @return void
	 */
	public static function error($message){
		if(!static::$driverLoaded)
			static::load();
		$backtrace  = debug_backtrace()[0];
		$line       = $backtrace['line'];
		$file       = $backtrace['file'];
		$time       = time();
		static::$driver->errorWriter($message,$line,$file,$time);
	}

	/**
	 * Get last log items from log database/file
	 * @param $limit
	 *  Number of items you want to get, zero will return all of logs from beginning to now
	 * @return array
	 */
	public static function get($limit = 0){
		return static::$driver->getAll($limit);
	}
}