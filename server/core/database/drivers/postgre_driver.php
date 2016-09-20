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

namespace core\database\drivers;


use core\database\driver;

/**
 * Class postgre_driver
 * @package core\database\drivers
 */
class postgre_driver implements driver{
	/**
	 * @var resource
	 */
	protected $object;
	/**
	 * @var bool
	 */
	protected $error    = false;

	/**
	 * postgre_driver constructor.
	 */
	public function __construct() {
		$dsn    = "host=".db_host." port=".db_port." dbname=".db_name." user=".db_user." password=".db_pass."options='--client_encoding=".db_charset."'";
		$this->object   = pg_connect($dsn);
		if(!$this->object){
			$this->error = true;
		}
	}

	/**
	 * @param $query
	 *
	 * @return array|bool
	 */
	public function query($query) {
		$result =  pg_query($this->object,$query);
		if(!$result){
			return false;
		}
		return $result;
	}

	/**
	 * @return void
	 */
	public function close() {
		pg_close($this->object);
	}

	/**
	 * @return resource
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @return bool
	 */
	public function isError() {
		return $this->error;
	}

	/**
	 * @return string
	 */
	public function getErrorDetail() {
		return '';
	}

	/**
	 * @param $result
	 *
	 * @return int
	 */
	public function num_rows($result) {
		return pg_num_rows($result);
	}

	/**
	 * @param $result
	 *
	 * @return array
	 */
	public function fetch_all($result) {
		return pg_fetch_all($result);
	}

	/**
	 * @param $result
	 *
	 * @return array
	 */
	public function fetch_assoc($result) {
		return pg_fetch_assoc($result);
	}
}