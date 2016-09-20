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
 * Class db_q_Parent
 * @package core\database\query
 */
class db_q_Parent {
	/**
	 * @var string
	 */
	protected $query    = '';

	/**
	 * db_q_Parent constructor.
	 *
	 * @param $query
	 */
	public function __construct($query) {
		$this->query    = $query;
	}

	/**
	 * @return \core\database\result
	 */
	public function execute(){
		return query::query($this->query.';');
	}

	/**
	 * Rewrite mysql_real_scape_string because this function is disabled in PHP 5.4.0 and it removed in PHP 7
	 * @param $string
	 *
	 * @return string
	 */
	protected function RES($string){
		return addcslashes($string,"\x00\"'\\\x1a\n\r");
	}

	/**
	 * @param array $columns
	 *
	 * @return string
	 */
	protected function Columns2String(array $columns){
		if($columns === []){
			return  '*';
		}else{
			$return = '';
			$keys   = array_keys($columns);
			$count  = count($columns);
			for($i  = 0;$i < $count;$i++){
				$return .= '\''.static::RES($columns[$keys[$i]]).'\', ';
			}
			return substr($return,0,-2);
		}
	}

	/**
	 * @param        $string
	 * @param string $str
	 *
	 * @return string
	 */
	protected function quote($string,$str = '\''){
		return $str.$this->RES($string).$str;
	}

	/**
	 * @param $query
	 *
	 * @return string
	 */
	protected function add2Query($query){
		$re             = $this->query;
		$this->query   .= ' '.$query;
		return $re;
	}

	/**
	 * @return string
	 */
	protected function clearQuery(){
		$re             = $this->query;
		$this->query    = '';
		return $re;
	}

	/**
	 * @return string
	 */
	public function getQuery() {
		return $this->query;
	}
}