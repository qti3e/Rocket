<?php
/**
 * Mange config variables
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\config;

/**
 * Class config
 * @package core\config
 */
class config {
	/**
	 * This array contains all of config values
	 * @var array
	 */
	protected static $configs   = [];
	/**
	 * Defaults value for configs
	 * @var array
	 */
	protected static $defaults  = [
		//Default value for host address
		'host'          => '0.0.0.0',
		//Default program port address
		'port'          => 98765,
		//Default buffer length size
		'bufferLength'  => 2048
	];

	/**
	 * Set a config value
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public function set($name,$value){
		//In config names upper and lower cases doesn't have any difference
		$name   = strtolower($name);
		//Configs are like constants and we don't allow to them to change when they registered before
		if(isset(static::$configs[$name])){
			return false;
		}
		//Set config file
		static::$configs[$name] = $value;
		return true;
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
	public function get($name,$def = false){
		//In config names upper and lower cases doesn't have any difference
		$name   = strtolower($name);
		if(isset(static::$configs[$name])){
			return static::$configs[$name];
		}elseif(isset(static::$defaults[$name])){
			return static::$defaults[$name];
		}
		return $def;
	}

	/**
	 * Set config value
	 * @see   set
	 * @param $name
	 * @param $value
	 *
	 * @return bool|mixed
	 */
	public function __set($name, $value) {
		return $this->set($name,$value);
	}

	/**
	 * Get the config value
	 * @see   get
	 * @param $name
	 *
	 * @return bool|mixed
	 */
	public function __get($name) {
		return $this->get($name,false);
	}

	/**
	 * Check if a config exists
	 * @param $name
	 *
	 * @return bool
	 */
	public function __isset($name) {
		$name   = strtolower($name);
		return isset(static::$configs[$name]);
	}

	/**
	 * Serialize configs
	 * @return array
	 */
	public function __sleep() {
		return get_object_vars($this);
	}
}