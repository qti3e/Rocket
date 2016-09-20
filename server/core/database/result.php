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
 * Class result
 * @package core\database
 */
class result {
	/**
	 * @var
	 */
	private $result;
	/**
	 * @var mixed
	 */
	public $num_rows;

	/**
	 * result constructor.
	 *
	 * @param $result
	 */
	public function __construct($result){
		$this->result   = $result;
		$this->num_rows = query::getObj()->num_rows($result);
	}

	/**
	 * @return mixed
	 */
	public function num_rows(){
		return query::getObj()->num_rows($this->result);
	}

	/**
	 * @return mixed
	 */
	public function fetch_assoc() {
		return query::getObj()->fetch_assoc($this->result);
	}

	/**
	 * @return mixed
	 */
	public function fetch_all(){
		return query::getObj()->fetch_all($this->result);
	}

	/**
	 * @param null $key
	 *
	 * @return array
	 */
	public function data($key = null){
		$re = [];
		while($row = $this->fetch_assoc()){
			if(isset($row[$key])){
				$re[$row[$key]] = $row;
			}else{
				$re[]   = $row;
			}
		}
		return $re;
	}
}