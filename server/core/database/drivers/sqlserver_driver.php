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
 * Class sqlserver_driver
 * @package core\database\drivers
 */
class sqlserver_driver implements driver{
	/**
	 * @var false|resource
	 */
	protected $object;
	/**
	 * @var bool
	 */
	protected $error    = false;
	/**
	 * @var array|null|string
	 */
	protected $errorD   = '';

	/**
	 * sqlserver_driver constructor.
	 */
	public function __construct() {
		$serverName = db_host.", ".db_port;
		$info       = [
			'Database'  => db_name,
			'UID'       => db_user,
			'PWD'       => db_pass
		];
		$this->object   = sqlsrv_connect($serverName,$info);
		if(!$this->object){
			$this->error    = true;
			$this->errorD   = sqlsrv_errors();
		}
	}

	/**
	 * @return bool
	 */
	public function isError() {
		return $this->error;
	}

	/**
	 * @return false|resource
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @return array|null|string
	 */
	public function getErrorDetail() {
		return $this->errorD;
	}

	/**
	 * @return void
	 */
	public function close() {
		sqlsrv_close($this->object);
	}

	/**
	 * @param $query
	 *
	 * @return array|bool|false|null
	 */
	public function query($query) {
		$stmt   = sqlsrv_query($this->object,$query);
		if($stmt === false){
			return false;
		}
		return $stmt;
	}

	/**
	 * @param $result
	 *
	 * @return bool|int
	 */
	public function num_rows($result) {
		return sqlsrv_num_rows($result);
	}

	/**
	 * @param $result
	 *
	 * @return array|false|null
	 */
	public function fetch_all($result) {
		return sqlsrv_fetch_array($result);
	}

	/**
	 * @param $result
	 *
	 * @return array|false|null
	 */
	public function fetch_assoc($result) {
		return sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	}
}