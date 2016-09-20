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

namespace core\database\query;


use core\database\query;

/**
 * Class where
 * @package core\database\query
 */
class where extends db_q_Parent{
	protected $whereCalled  = false;
	/**
	 * @param array $validator
	 *
	 * @return db_q1
	 */
	public function WHERE(array $validator = []){
		$this->whereCalled  = true;
		$query  = 'WHERE 1 ';
		$count  = count($validator);
		$keys   = array_keys($validator);
		for($i  = 0;$i < $count;$i++){
			$query .= 'AND '.$this->quote($keys[$i],'`').'='.$this->quote($validator[$keys[$i]]).' ';
		}
		$this->add2Query($query);
		return new db_q1($this->query);
	}

	/**
	 * @param array  $validator
	 * @param        $comparison
	 * @param string $op
	 *
	 * @return db_q1
	 */
	public function whereComparison(array $validator,$comparison,$op = 'AND'){
		$query  = '';
		if(!$this->whereCalled){
			$query  .= 'WHERE ';
		}
		$keys   = array_keys($validator);
		$count  = count($validator);
		$comparison = ' '.$comparison.' ';
		$op         = $op.' ';
		for($i  = 0;$i < $count;$i++){
			if($i !== 0 || $this->whereCalled){
				$query .= $op;
			}
			$query .= $this->quote($keys[$i],'`').$comparison.$this->quote($validator[$keys[$i]]).' ';
		}
		$this->add2Query($query);
		return new db_q1($this->query);
	}
}