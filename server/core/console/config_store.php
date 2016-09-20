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

namespace core\console;

/**
 * Class config_store
 * @package core\console
 */
class config_store {
	/**
	 * @var array
	 */
	protected static $configs  = [];

	/**
	 * @return void
	 */
	public static function reset(){
		 static::$configs   = [];
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public static function remove($name){
		$name   = strtolower($name);
		if(isset(static::$configs[$name])){
			unset(static::$configs[$name]);
			return true;
		}
		return false;
	}

	/**
	 * @param            $name
	 * @param bool|false $def
	 *
	 * @return bool
	 */
	public static function get($name,$def   = false){
		$name   = strtolower($name);
		if(isset(static::$configs[$name])){
			return static::$configs[$name];
		}
		return $def;
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function set($name,$value){
		$name   = strtolower($name);
		return static::$configs[$name] = $value;
	}

	/**
	 * Set if key does not exists.
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public static function setX($name,$value){
		$name   = strtolower($name);
		if(isset(static::$configs[$name])){
			return false;
		}
		return static::$configs[$name]  = $value;
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public static function exists($name){
		return isset(static::$configs[strtolower($name)]);
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public function __isset($name) {
		return static::exists($name);
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return mixed
	 */
	public function __set($name, $value) {
		return static::set($name,$value);
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return static::get($name);
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public function __unset($name) {
		return static::remove($name);
	}
}