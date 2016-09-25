<?php
/**
 * Main class of Rocket framework
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\rocket;

use core\config\config;
use core\console\CommandController;
use core\redis\redis;
use core\ws\server;

/**
 * Class rocket
 * @package core\rocket
 */
class rocket {
	/**
	 * @var
	 */
	public static $redis;
	/**
	 * Autoload function
	 * This function is registered as auto load function with spl_autoload_register in __construct method
	 *
	 * @param $class
	 *  Name of the class
	 *  Note: class name contains namespace, too
	 *
	 * @return bool
	 *  True on success and False on failing
	 */
	public function autoLoad($class){
		//convert class name to a file name that works both on Unix-based (Linux and Mac) and Windows
		$file   = str_replace('\\','/',$class).'.php';
		//Check if file exists
		if(file_exists($file)){
			//Include file and return true as success return
			include_once($file);
			return true;
		}
		//Return false because class doesn't exists
		return false;
	}

	/**
	 * rocket constructor.
	 * This class check that if program should run in the socket mode or it should run in the command line mode
	 * Arguments:
	 *  -c|--command:
	 *      This argument actives command prompt mode.
	 *      Example: php index.php -c
	 *  -s|--server:
	 *      Give optional host and port from command line.
	 *      Example: php -s host[:port]
	 *      Example: php -s 0.0.0.0:8080
	 * @note Those arguments does not work in the same time if you enter something like:
	 *  "php index.php -s 0.0.0.0:8888 -c"
	 *  only -c arg will works
	 * @param $args
	 *  It's command line arguments ($argv) that should pass to this class
	 */
	public function __construct($args) {
		spl_autoload_register([$this,'autoLoad']);
		$args           = implode(' ',$args).' ';
		if(config::get('need_redis',true)){
			$redis_host = config::get('redis_host');
			$redis_port = config::get('redis_port');
			$redis_db   = config::get('redis_db');
			$redis_timeout      = config::get('redis_timeout');
			$redis_persistent   = config::get('redis_persistent');
			$redis_password     = config::get('redis_password');
			static::$redis      = new redis($redis_host,$redis_port,$redis_timeout,$redis_persistent,$redis_db,$redis_password);
		}
		if(preg_match('/\s+(-c|--command)\s+/',$args)){
			$this->object   = new CommandController();
		}else{
			$host   = $this->getConfig('host');
			$port   = $this->getConfig('port');
			if(preg_match('/\s+(-s|--server)\s+([\w.]+)(:([0-9]+))*\s+/',$args,$matches)){
				$host   = isset($matches[2])    ? $matches[2]   : $host;
				$port   = isset($matches[4])    ? $matches[4]   : $port;
			}
			$this->object   = new server($host,$port,$this->getConfig('bufferLength'));
		}
	}

	/**
	 * Get config value
	 * @param      $name
	 * @param bool $def
	 *
	 * @return bool|mixed
	 */
	public function getConfig($name,$def = false){
		return config::get($name,$def);
	}

	/**
	 * Set a config
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public function setConfig($name,$value){
		return config::set($name,$value);
	}

	/**
	 * Set multiple configs at once
	 * @param $configs
	 *
	 * @return int
	 */
	public function setConfigs($configs){
		return config::mSet($configs);
	}

	public function run(){
		$this->object->run();
	}
}