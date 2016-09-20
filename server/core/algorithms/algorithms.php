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

namespace core\algorithms;

/**
 * Class algorithms
 * @package core\algorithms
 */
class algorithms {
	/**
	 * Golden ratio
	 */
	const gr    = 1.6180339887498948482045868343656;
	/**
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public static function GCD($a, $b){
		while($b > 0){
			$a  = $a % $b;
			$a ^= $b;
			$b ^= $a;
			$a ^= $b;
		}
		return $a;
	}

	/**
	 * @param $a
	 * @param $b
	 *
	 * @return float
	 */
	public static function LCM($a,$b){
		return ($a * $b) / static::GCD($a,$b);
	}

	/**
	 * @param $n
	 *
	 * @return mixed
	 */
	public static function square($n){
		return $n*$n;
	}

	/**
	 * @param $base
	 * @param $power
	 *
	 * @return int|mixed
	 */
	public static function fastexp($base, $power){
		if($power == 0){
			return 1;
		}
		if($power % 2 == 2){
			return static::square(static::fastexp($base,$power/2));
		}
		return $base * (static::fastexp($base,$power - 1));
	}

	/**
	 * @param     $n
	 * @param int $to
	 *
	 * @return int
	 * @throws algorithmsException
	 */
	public static function factorial($n , $to = 1){
		if($n > 500){
			throw new algorithmsException('LargeNumber','factorial');
		}
		$result = 1;
		for($i = $to;$i <= $n;$i++){
			$result *= $i;
		}
		return $result;
	}

	/**
	 * Return nth fibonacci number
	 * @param $n
	 *
	 * @return float
	 * @throws algorithmsException
	 */
	public static function fibonacci($n){
		if($n > 1000){
			throw new algorithmsException('LargeNumber','fibonacci');
		}
		return (static::fastexp(static::gr,$n) - (1/static::fastexp(-static::gr,$n))) / (2*static::gr-1);
	}

	/**
	 * @param $a
	 *
	 * @return bool
	 * @throws algorithmsException
	 */
	public static function  is_prime($a){
		/**
		 * Largest 8 bit number
		 *  Dec: ‭4,294,967,295
		 * It's not very large because this function takes lots of time‬
		 */
		if($a > 0XFFFFFFFF){
			throw new algorithmsException('LargeNumber','is_prime');
		}
		if ($a == 1) return false;
		if ($a == 2) return true;
		if ($a % 2 == 0) return false;
		$lim = (int)sqrt($a);
		for ($i = 2; $i <= $lim; $i++) {
			if ($a % $i == 0) {
				return false;
			}
		}
		return true;
	}
}