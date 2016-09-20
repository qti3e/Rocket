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
 * Class sqlite_driver
 * @package core\database\drivers
 */
class sqlite_driver implements driver{
	/**
	 * @var \SQLite3
	 */
	protected $object;

	/**
	 * sqlite_driver constructor.
	 */
	public function __construct() {
		$this->object   = new \SQLite3(db_name,null,db_pass);
	}

	/**
	 * @return void
	 */
	public function close() {
		$this->object->close();
	}

	/**
	 * @return void
	 */
	public function getErrorDetail() {}

	/**
	 * @return void
	 */
	public function isError() {}

	/**
	 * @return \SQLite3
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @param $query
	 *
	 * @return \SQLite3Result
	 */
	public function query($query) {
		return $this->object->query($query);
	}

	/**
	 * @param \SQLite3Result $result
	 *
	 * @return int
	 */
	public function num_rows($result) {
		return sqlite_num_rows($result);
	}

	/**
	 * @param \SQLite3Result $result
	 *
	 * @return array
	 */
	public function fetch_all($result) {
		return sqlite_fetch_all($result);
	}

	/**
	 * @param \SQLite3Result $result
	 *
	 * @return array
	 */
	public function fetch_assoc($result) {
		return $result->fetchArray(SQLITE_ASSOC);
	}
}