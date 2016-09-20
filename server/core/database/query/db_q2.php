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

class db_q2 extends db_q1{
	/**
	 * @return db_q1
	 */
	public function COUNT(){
		$this->clearQuery();
		$this->add2Query("SELECT COUNT(".$this->quote(query::getLastParams(1)[0],'`').") FROM ".$this->quote(query::getLastParams(0),'`'));
		return new db_q1($this->query);
	}

	/**
	 * @return db_q1
	 */
	public function SUM(){
		$this->clearQuery();
		$this->add2Query("SELECT SUM(".$this->quote(query::getLastParams(1)[0],'`').") FROM ".$this->quote(query::getLastParams(0),'`'));
		return new db_q1($this->query);
	}

	/**
	 * @return db_q1
	 */
	public function MAX(){
		$this->clearQuery();
		$this->add2Query("SELECT MAX(".$this->quote(query::getLastParams(1)[0],'`').") FROM ".$this->quote(query::getLastParams(0),'`'));
		return new db_q1($this->query);
	}

	/**
	 * @return db_q1
	 */
	public function MIN(){
		$this->clearQuery();
		$this->add2Query("SELECT MIN(".$this->quote(query::getLastParams(1)[0],'`').") FROM ".$this->quote(query::getLastParams(0),'`'));
		return new db_q1($this->query);
	}

	/**
	 * @return db_q1
	 */
	public function AVG(){
		$this->clearQuery();
		$this->add2Query("SELECT AVG(".$this->quote(query::getLastParams(1)[0],'`').") FROM ".$this->quote(query::getLastParams(0)),'`');
		return new db_q1($this->query);
	}
}