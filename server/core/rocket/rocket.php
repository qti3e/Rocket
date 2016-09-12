<?php
/**
 * This is main file of Rocket framework and index.php file must to include it first and then
 * create an instance from the rocket class and set the configs
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\rocket;

include_once "core/helpers/helper.php";

use core\config\config;
use core\helpers\helper;

/**
 * Class rocket
 * Main class for framework
 *
 * @package core\rocket
 */
class rocket {
	/**
	 * Interface of config class
	 * @see core\config\config
	 * @var config
	 */
	private $config;
	/**
	 * @var objInterface
	 */
	private $object;

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
		$file   = helper::url($class).'.php';
		//Check if file exists
		if(file_exists($file)){
			//Include file and return true as success return
			include($file);
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
		$this->config   = new config();
		$args           = implode(' ',$args).' ';
		if(preg_match('/\s+(-c|--command)\s+/',$args)){
			echo "command line mode";
		}else{
			$host   = $this->getConfig('host');
			$port   = $this->getConfig('port');
			if(preg_match('/\s+(-s|--server)\s+([\w.]+)(:([0-9]+))*\s+/',$args,$matches)){
				$host   = isset($matches[2])    ? $matches[2]   : $host;
				$port   = isset($matches[4])    ? $matches[4]   : $port;
			}
			$this->object   = new socket($host,$port,$this->getConfig('bufferLength'));
		}
	}

	/**
	 * Set a config
	 *  This method is not static because
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public function setConfig($name,$value){
		return $this->config->set($name,$value);
	}

	/**
	 * Return value of config
	 *
	 * @param string    $name
	 *  Name of config you want to get the value of it
	 *
	 * @param mixed     $def
	 *  It will return when config does not exists, see return to learn more.
	 *
	 * @return bool|mixed
	 *  if config does not exist returns value stored at $default array and if it does not exists too it will return $def value
	 */
	public function getConfig($name,$def = false){
		return $this->config->get($name,$def);
	}

	/**
	 * Start the program
	 * @return void
	 */
	public function run(){
		$this->object->run();
	}
}