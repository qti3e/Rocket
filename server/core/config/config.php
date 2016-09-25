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
	protected static $configs   = [
		//Rocket name and version (Please don't edit this line, thanks :) )
		'version'   => 'Rocket/0.2-beta',
		//operation system name
		'os'        => PHP_OS
	];
	/**
	 * Defaults value for configs
	 * @note All of keys must be in lower case.
	 * @var array
	 */
	protected static $defaults  = [
		//Default value for host address
		'host'          => '0.0.0.0',
		//Default program port address
		'port'          => 8085,
		//Default buffer length size for socket connections
		'bufferlength'  => 2048,
		//Database driver (type)
		'db_driver'     => 'mysqli',
		//Database host
		'db_host'       => '127.0.0.1',
		//Database username
		'db_user'       => 'root',
		//Database password
		'db_pass'       => '',
		//Database name
		'db_name'       => '',
		//Database connection port
		'db_port'       => '3306',
		//Database character set
		'db_charset'    => 'UTF-8',
		//Database flags, only used in sqlite driver
		'db_flags'      => null,
		//The host name of the Redis server
		'redis_host'    => '127.0.0.1',
		//The port number of the Redis server
		'redis_port'    => 6379,
		//Timeout period in seconds
		'redis_timeout' => null,
		//The selected database of the Redis server
		'redis_db'      => 0,
		//Flag to establish persistent connection
		'redis_persistent'  => '',
		//The authentication password of the Redis server
		'redis_password'    => null,
		//Control  panel access IP regex pattern (Only people with the natch IP will be able to see admin panel)
		'cp_ip_pattern'     => '127.0.0.1',
		//Control panel authentication username
		'cp_username'       => 'admin',
		//Control panel authentication password in sha1 (default value is 12345)
		'cp_password'       => '8cb2237d0679ca88db6464eac60da96345513964',

		//Logger driver name including the namespace
		'logger_driver'     => 'core\\logger\\drivers\\redis',
		//Configs for each driver goes here depends on the driver type

		//Configs for the Redis based logger driver
		//The host name of the logger's Redis server
		'logger_redis_host'         => '127.0.0.1',
		//The port number of the logger's Redis server
		'logger_redis_port'         => 6379,
		//Timeout period in seconds
		'logger_redis_timeout'      => null,
		//The selected database of the logger's Redis server (Note it can be different from your main database)
		'logger_redis_db'           => 1,
		//Flag to establish persistent connection
		'logger_redis_persistent'   => '',
		//The authentication password of the logger's Redis server
		'logger_redis_password'     => null,
		//Hash the ids for compress them, we use crc32 as default because it's the shortest hash in PHP (8 ASCII characters)
		'logger_redis_hash_algo'    => 'crc32'

		//</End of driver's config
	];

	/**
	 * Set a config value
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public static function set($name,$value){
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
	public static function get($name,$def = false){
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
		return static::set($name,$value);
	}

	/**
	 * Get the config value
	 * @see   get
	 * @param $name
	 *
	 * @return bool|mixed
	 */
	public function __get($name) {
		return static::get($name,false);
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

	/**
	 * Multiple config set
	 * @param $configs
	 *
	 * @return int
	 */
	public static function mSet($configs){
		$count  = count($configs);
		$keys   = array_keys($configs);
		$return = 0;
		for($i  = 0;$i < $count;$i++){
			if(static::set($keys[$i],$configs[$keys[$i]])){
				$return++;
			}
		}
		return $return;
	}

	/**
	 * Set default values for config
	 * It's not static because only system plugins should be able to change default values
	 * @param $configs
	 *
	 * @return int
	 *  Returns number of successful sets
	 */
	public function mDefSet($configs){
		$count  = count($configs);
		$keys   = array_keys($configs);
		$return = 0;
		for($i  = 0;$i < $count;$i++){
			$name   = strtolower($keys[$i]);
			if(!isset(static::$defaults[$name])){
				$return++;
				static::$defaults[$name]    = $configs[$keys[$i]];
			}
		}
		return $return;
	}

	/**
	 * Return all of library for third-party libraries
	 * @return array
	 */
	public static function get_all(){
		return static::$configs+static::$defaults;
	}
}