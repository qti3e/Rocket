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

use core\database\drivers\mysqli_driver;
use core\database\drivers\pdo_driver;
use core\database\drivers\postgre_driver;
use core\database\drivers\sqlite_driver;
use core\database\drivers\sqlserver_driver;
use core\database\query\where;
use core\exception\youn_exception;

/**
 * Class query
 *  A helper class to write safe sql queries without errors , and problems like sql injection and etc...
 * @package core\database
 */
class query {
	/**
	 * Save and storage created sql query
	 * @var string
	 */
	protected static $query;
	/**
	 * Save last executed sql query
	 * @var string
	 */
	protected static $lastQuery;
	/**
	 * @var array
	 */
	protected static $lastParams;
	/**
	 * @var driver
	 */
	protected static $driver;

	/**
	 * query constructor.
	 */
	public function __construct() {
		switch(strtolower(db_driver)){
			case 'mysqli':
				static::$driver = new mysqli_driver();
				break;
			case 'pdo':
				static::$driver = new pdo_driver();
				break;
			case 'postgre':
				static::$driver = new postgre_driver();
				break;
 			case 'sqlite':
			    static::$driver = new sqlite_driver();
			    break;
 			case 'sqlserver':
			    static::$driver = new sqlserver_driver();
			    break;
			default:
				if(function_exists('mysqli_connect')){
					static::$driver = new mysqli_driver();
				}elseif(extension_loaded('pdo')){
					static::$driver = new pdo_driver();
				}else{
					throw new youn_exception('Can\'t load application because both of pdo and mysqli are disabled');
				}
		}
	}

	/**
	 * Rewrite mysql_real_scape_string because this function is disabled in PHP 5.4.0 and it removed in PHP 7
	 * @param $string
	 *
	 * @return string
	 */
	protected static function RES($string){
		return addcslashes($string,"\x00\"'\\\x1a\n\r");
	}

	/**
	 * @param $array
	 *
	 * @return mixed
	 */
	protected static function RES_array($array){
		if(is_string($array)){
			return static::RES($array);
		}
		$keys   = array_keys($array);
		$count  = count($array);
		for($i  = 0;$i < $count;$i++){
			$array[$keys[$i]]   = addcslashes($array[$keys[$i]],"\x00\"'\\\x1a\n\r");
		}
		return $array;
	}

	/**
	 * @param        $string
	 * @param string $str
	 *
	 * @return string
	 */
	protected static function Quote($string,$str = '\''){
		return $str.static::RES($string).$str;
	}

	/**
	 * @param array  $columns
	 * @param string $str
	 *
	 * @return string
	 */
	protected static function Columns2String(array $columns,$str = '\''){
		if($columns === []){
			return  '*';
		}else{
			$return = '';
			$keys   = array_keys($columns);
			$count  = count($columns);
			for($i  = 0;$i < $count;$i++){
				$return .= $str.static::RES($columns[$keys[$i]]).$str.', ';
			}
			return substr($return,0,-2);
		}
	}

	/**
	 * @param null $i
	 *
	 * @return array|bool
	 */
	public static function getLastParams($i = null){
		if($i === null){
			return static::$lastParams;
		}
		if(isset(static::$lastParams[$i])){
			return static::$lastParams[$i];
		}
		return false;
	}

	/**
	 * @param $query
	 *
	 * @return result
	 */
	public static function query($query){
		static::$lastQuery    = $query;
		return new result(static::$driver->query($query));
	}

	/**
	 * @return string
	 */
	public static function getLastQuery() {
		return static::$lastQuery;
	}

	/**
	 * @param string    $table
	 * @param array     $columns
	 *
	 * @return query\db_q2
	 */
	public static function SELECT($table,array $columns = []){
		static::$lastParams   = func_get_args();
		$columns    = static::Columns2String($columns,'`');
		$table      = static::Quote($table,'`');
		static::$query= "SELECT $columns FROM $table";
		return (new query\db_q2(static::$query));
	}

	/**
	 * @param       $table
	 * @param array $values
	 *
	 * @return query\db_q_Parent
	 */
	public static function INSERT($table,array $values = []){
		static::$lastParams   = func_get_args();
		$columns    = static::Columns2String(array_keys($values),'`');
		$values     = static::Columns2String($values);
		$table      = static::RES($table);
		static::$query= "INSERT INTO `$table` ($columns) VALUES ($values)";
		return new query\db_q_Parent(static::$query);
	}

	/**
	 * @param       $table
	 * @param array $values
	 *
	 * @return where
	 */
	public static function UPDATE($table,array $values){
		$table  = static::RES($table);
		$keys   = array_keys($values);
		$count  = count($values);
		$query  = "UPDATE `$table` SET ";
		for($i  = 0;$i < $count;$i++){
			$key= $keys[$i];
			$query  .= static::Quote($key,'`').'='.static::Quote($values[$key]);
		}
		static::$query = $query;
		return new where(static::$query);
	}

	/**
	 * @param $table
	 *
	 * @return where
	 */
	public static function DELETE($table){
		static::$query    = "DELETE FROM ".static::Quote($table,'`');
		return new where(static::$query);
	}

	/**
	 * @param $table
	 *
	 * @return query\db_q_Parent
	 */
	public static function DROP($table){
		static::$query    = "DROP TABLE ".static::Quote($table,'`');
		return new query\db_q_Parent(static::$query);
	}

	/**
	 * @return driver
	 */
	public static function getObj(){
		return self::$driver;
	}
}
