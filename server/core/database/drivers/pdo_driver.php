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
 * Class pdo_driver
 * @package core\database\drivers
 */
class pdo_driver implements driver{
	/**
	 * @var \PDO
	 */
	protected $object;
	/**
	 * @var bool
	 */
	private $error = false;
	/**
	 * @var bool
	 */
	private $errorDetail;

	/**
	 * pdo_driver constructor.
	 */
	public function __construct() {
		$dsn    = 'mysql:dbname='.db_name.';host='.db_host.';charset='.db_charset.'';
		try{
			$this->object = new \PDO($dsn,db_user,db_pass);
		}catch (\PDOException $e){
			$this->error = true;
			$this->object = $e->getMessage();
		}
	}

	/**
	 * @param $query
	 *
	 * @return \PDOStatement
	 */
	public function query($query) {
		$sth    = $this->object->prepare($query);
		$sth->execute();
		return $sth;
	}

	/**
	 * @return void
	 */
	public function close() {
		$this->object = null;
	}

	/**
	 * @return bool
	 */
	public function isError() {
		return $this->error;
	}

	/**
	 * @return bool
	 */
	public function getErrorDetail() {
		return $this->errorDetail;
	}

	/**
	 * @return \PDO|string
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @param \PDOStatement $result
	 *
	 * @return int
	 */
	public function num_rows($result) {
		return $result->rowCount();
	}

	/**
	 * @param \PDOStatement $result
	 *
	 * @return array
	 */
	public function fetch_all($result) {
		return $result->fetchAll();
	}

	/**
	 * @param \PDOStatement $result
	 *
	 * @return mixed
	 */
	public function fetch_assoc($result) {
		return $result->fetch(\PDO::FETCH_ASSOC);
	}
}