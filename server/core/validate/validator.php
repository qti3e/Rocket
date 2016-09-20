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

namespace core\validate;

use core\validate\validators\email;
use core\validate\validators\ipv4;
use core\validate\validators\ipv6;
use core\validate\validators\username;

/**
 * Class validator
 * @package core\validate
 */
class validator {
	/**
	 * @param                    $input
	 * @param validatorInterface $validator
	 *
	 * @return bool
	 */
	public static function validate($input,validatorInterface $validator){
		if(is_array($input)){
			$re     = true;
			$keys   = array_keys($input);
			$count  = count($input);
			for($i  = 0;$i < $count;$i++){
				$re &= static::validate($input[$keys[$i]],$validator);
			}
			return $re;
		}
		return $validator->is_valid($input);
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	public static function email($input){
		return static::validate($input,new email());
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	public static function ipv4($input){
		return static::validate($input,new ipv4());
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	public static function ipv6($input){
		return static::validate($input,new ipv6());
	}

	/**
	 * @param $input
	 *
	 * @return bool
	 */
	public static function username($input){
		return static::validate($input,new username());
	}
}