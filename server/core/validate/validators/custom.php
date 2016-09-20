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

namespace core\validate\validators;


use core\validate\validatorInterface;

/**
 * Class custom
 * @package core\validate\validators
 */
class custom implements validatorInterface{
	/**
	 * @var string
	 */
	protected $pattern  = '';
	/**
	 * @var bool
	 */
	protected $callable = false;
	/**
	 * custom constructor.
	 *
	 * @param $pattern
	 */
	public function __construct($pattern) {
		if(is_callable($pattern)){
			$this->callable = true;
		}
		$this->pattern  = $pattern;
	}

	/**
	 * @return string
	 */
	public function get_pattern() {
		if($this->callable){
			return 'CALLABLE';
		}
		return $this->pattern;
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	public function is_valid($input) {
		if($this->callable){
			return (bool)$this->pattern($input);
		}
		return preg_match($this->pattern,$input);
	}
}