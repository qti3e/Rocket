<?php
/*****************************************************************************
 *         In the name of God the Most Beneficent the Most Merciful          *
 *___________________________________________________________________________*
 *   This program is free software: you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation, either version 3 of the License, or       *
 *   (at your option) any later version.                                     *
 *___________________________________________________________________________*
 *   This program is distributed in the hope that it will be useful,         *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *   GNU General Public License for more details.                            *
 *___________________________________________________________________________*
 *   You should have received a copy of the GNU General Public License       *
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *___________________________________________________________________________*
 *                             Created by  Qti3e                             *
 *        <http://Qti3e.Github.io>    LO-VE    <Qti3eQti3e@Gmail.com>        *
 *****************************************************************************/

namespace core\database;

/**
 * Class KeyValueStore
 * @package core\regex
 */
class KeyValueStore {
	/**
	 * @var array
	 */
	private static $store;

	/**
	 * @return mixed
	 */
	public static function getStore(){
		return static::$store;
	}

	/**
	 * @param $store
	 *
	 * @return array
	 */
	public static function setStore($store) {
		$re = static::$store;
		self::$store = $store;
		return $re;
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return mixed
	 */
	protected static function _set($name,$value){
		return static::$store[$name]   = $value;
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public static function get($name){
		$db = static::getStore();
		if(isset($db[$name])){
			return $db[$name];
		}
		return false;
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function set($name,$value){
		return static::_set($name,$value);
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public static function isDefined($name){
		return isset(static::getStore()[$name]);
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return bool|mixed
	 */
	public static function setIfNotExists($name,$value){
		if(self::isDefined($name)){
			return false;
		}
		return static::set($name,$value);
	}

	/**
	 * @param null $pattern
	 *
	 * @return array
	 */
	public static function keys($pattern = null){
		if($pattern === null){
			return array_keys(static::getStore());
		}
		$keys   = array_keys(static::getStore());
		$count  = count($keys);
		$return = [];
		for($i  = 0;$i < $count;$i++){
			if(preg_match($pattern,$keys[$i])){
				$return[]   = $keys[$i];
			}
		}
		return $return;
	}

	/**
	 * @param null $pattern
	 *
	 * @return array
	 */
	public static function values($pattern = null){
		if($pattern === null){
			return array_values(static::getStore());
		}
		$values = array_values(static::getStore());
		$count  = count($values);
		$return = [];
		for($i  = 0;$i < $count;$i++){
			if(preg_match($pattern,$values[$i])){
				$return[]   = $values[$i];
			}
		}
		return $return;
	}

	/**
	 * @param $name
	 *
	 * @return void
	 */
	protected static function _remove($name){
		unset(static::$store[$name]);
	}

	/**
	 * @param $name
	 *
	 * @return void
	 */
	public static function remove($name){
		static::_remove($name);
	}

	/**
	 * @param $pattern
	 *
	 * @return array
	 */
	public static function removePattern($pattern){
		if($pattern === null){
			return array_keys(static::getStore());
		}
		$keys   = array_keys(static::getStore());
		$count  = count($keys);
		$return = [];
		for($i  = 0;$i < $count;$i++){
			if(preg_match($pattern,$keys[$i])){
				static::remove($keys[$i]);
			}
		}
		return $return;
	}
}