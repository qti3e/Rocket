<?php
/**
 * Manage user sessions
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\session;


use core\factory\call;
use core\interval\interval;
use core\redis\redis;
use core\ws\user;

/**
 * Session manager based on Redis
 * @package core\session
 */
class session {
	/**
	 * Session id
	 * @var
	 */
	protected $sessionId;
	/**
	 * @var redis
	 */
	protected static $redis = null;
	/**
	 * session constructor.
	 *
	 * @param user $user
	 * @param redis $redis
	 *
	 */
	public function __construct(user $user,$redis) {
		$this->sessionId    = $user->sessionId;
		if(static::$redis == null){
			static::$redis  = call::newInstance('redis',[],true);
		}
	}

	/**
	 * Set a value in session storage
	 * @param $name
	 * @param $value
	 *
	 * @return bool|int
	 */
	public function set($name,$value){
		return static::$redis->hSet($this->sessionId,$name,serialize($value));
	}

	/**
	 * Get a value from session storage
	 * @param $name
	 *
	 * @return mixed|null
	 */
	public function get($name){
		$ret = static::$redis->hGet($this->sessionId,$name);
		if($ret){
			return unserialize($ret);
		}
		return null;
	}

	/**
	 * Remove a key from session storage
	 * @param $name
	 *
	 * @return bool
	 */
	public function del($name){
		return static::$redis->hDel($this->sessionId,$name);
	}

	/**
	 * Return true when the given key exists
	 * @param $name
	 *
	 * @return bool
	 */
	public function exists($name){
		return (bool)static::$redis->hExists($this->sessionId,$name);
	}

	/**
	 * Return array contains only session keys
	 * @return array
	 */
	public function keys(){
		return static::$redis->hKeys($this->sessionId);
	}

	/**
	 * Remove key after some seconds
	 * @param $name
	 * @param $seconds
	 *
	 * @return void
	 */
	public function expire($name,$seconds){
		interval::timeout(function() use ($name,$this){
			$this->del($name);
		},$seconds);
	}
	
	/**
	 * Return all of session storage at once
	 * @return array
	 */
	public function getAll(){
		$ret    = static::$redis->hGetAll($this->sessionId);
		$keys   = array_keys($ret);
		$i      = count($ret)-1;
		for(;$i > -1;$i--){
			$ret[$keys[$i]] = unserialize($ret[$keys[$i]]);
		}
		return $ret;
	}

	/**
	 * Alias of set
	 * @see   set
	 * @param $name
	 * @param $value
	 *
	 * @return bool|int
	 */
	public function __set($name, $value) {
		return $this->set($name,$value);
	}

	/**
	 * Alias of get
	 * @see   get
	 * @param $name
	 *
	 * @return mixed|null
	 */
	public function __get($name){
		return $this->get($name);
	}

	/**
	 * Alias of exists
	 * @see   exists
	 * @param $name
	 *
	 * @return bool
	 */
	public function __isset($name) {
		return $this->exists($name);
	}

	/**
	 * Alias of del
	 * @see   del
	 * @param $name
	 *
	 * @return bool
	 */
	public function __unset($name) {
		return $this->del($name);
	}

	/**
	 * Return object vars for serializing
	 * @return array
	 */
	public function __sleep() {
		return get_object_vars($this);
	}

	/**
	 * Return session-id when calls as a string
	 * @return mixed
	 */
	public function __toString() {
		return $this->sessionId;
	}
}