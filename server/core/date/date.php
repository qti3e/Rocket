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

namespace core\date;

/**
 * Class date
 * @package core\date
 */
class date{
	const ATOM = 'Y-m-d\TH:i:sP';
	const COOKIE = 'l, d-M-y H:i:s T';
	const ISO8601 = 'Y-m-d\TH:i:sO';
	const RFC822 = 'D, d M y H:i:s O';
	const RFC850 = 'l, d-M-y H:i:s T';
	const RFC1036 = 'D, d M y H:i:s O';
	const RFC1123 = 'D, d M Y H:i:s O';
	const RFC2822 = 'D, d M Y H:i:s O';
	const RFC3339 = 'Y-m-d\TH:i:sP';
	const RSS = 'D, d M Y H:i:s O';
	const W3C = 'Y-m-d\TH:i:sP';
	/**
	 * @var
	 */
	protected static $error;

	/**
	 * @return mixed
	 */
	public static function getLastError(){
		return static::$error;
	}

	/**
	 * @param $string
	 *
	 * @return int
	 */
	public static function str2time($string){
		return strtotime($string);
	}

	/**
	 * @param $date
	 * @param $new_format
	 *
	 * @return bool|string
	 */
	public static function changeFormat($date,$new_format){
		return date($new_format,strtotime($date));
	}

	/**
	 * @param $month
	 * @param $day
	 * @param $year
	 *
	 * @return bool
	 */
	public static function checkDate($month,$day = null,$year = null){
		if($day === null){
			$day    = (int)date('d');
		}
		if($year === null){
			$year   = (int)date('y');
		}
		return checkdate($month,$day,$year);
	}

	/**
	 * @param      $format
	 * @param null $date
	 *
	 * @return bool|string
	 */
	protected static function _get($format,$date = null){
		if($date   === null){
			$date  = time();
		}elseif(is_string($date)){
			$date  = static::str2time($date);
		}
		return date($format,$date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function getYear($date = null){
		return static::_get('y', $date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function getMonth($date = null){
		return static::_get('m', $date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function getDay($date = null){
		return static::_get('d', $date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function getHour($date = null){
		return static::_get('h', $date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function getMin($date = null){
		return static::_get('i', $date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function getSecond($date = null){
		return static::_get('s', $date);
	}

	/**
	 * @param null $date
	 *
	 * @return bool|string
	 */
	public static function standard($date = null){
		return static::_get('D, d M Y H:i:s',$date);
	}

	/**
	 * Format: "Y-m-d\TH:i:sO"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function iso8601($timestamp = null){
		return static::_get(static::ISO8601,$timestamp);
	}

	/**
	 * Format: "D, d M y H:i:s O"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rfc822($timestamp = null){
		return static::_get(static::RFC822,$timestamp);
	}

	/**
	 * Format: "l, d-M-y H:i:s T"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rfc850($timestamp = null){
		return static::_get(static::RFC850,$timestamp);
	}

	/**
	 * Format: "D, d M y H:i:s O"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rfc1036($timestamp = null){
		return static::_get(static::RFC1036,$timestamp);
	}

	/**
	 * Format: "D, d M Y H:i:s O"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rfc1123($timestamp = null){
		return static::_get(static::RFC1123,$timestamp);
	}

	/**
	 * Format: "D, d M Y H:i:s O"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rfc2822($timestamp = null){
		return static::_get(static::RFC2822,$timestamp);
	}

	/**
	 * Format: "Y-m-d\TH:i:sP"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rfc3339($timestamp = null){
		return static::_get(static::RFC3339,$timestamp);
	}

	/**
	 * Same as RFC1123 and RFC2822
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function rss($timestamp = null) {
		return static::_get(static::RSS,$timestamp);
	}

	/**
	 * Same as RFC3339
	 * Format: "Y-m-d\TH:i:sP"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function w3c($timestamp = null){
		return static::_get(static::W3C,$timestamp);
	}

	/**
	 * Same as RFC339 and W3C
	 * Format: "Y-m-d\TH:i:sP"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function atom($timestamp = null){
		return static::_get(static::ATOM,$timestamp);
	}

	/**
	 * Same as RFC850
	 * Format: "l, d-M-Y H:i:s T"
	 * @param null $timestamp
	 *
	 * @return bool|string
	 */
	public static function cookie($timestamp = null){
		return static::_get(static::COOKIE,$timestamp);
	}

	/**
	 * @param               $time1
	 * @param \DateInterval $time2
	 * @param null          $format
	 *
	 * @return string
	 */
	public static function add($time1,\DateInterval $time2,$format = null){
		$date   = new \DateTime($time1);
		$re     = $date->add($time2);
		if($re){
			if($format === null){
				$format = static::W3C;
			}
			return $date->format($format);
		}else{
			static::$error = $date->getLastErrors();
			return -1;
		}
	}

	/**
	 * @param      $date
	 * @param      $modify
	 * @param null $format
	 *
	 * @return int|string
	 */
	public static function modify($date,$modify,$format = null){
		$date   = new \DateTime($date);
		$re     = $date->modify($modify);
		if($re){
			if($format === null){
				$format = static::W3C;
			}
			return $date->format($format);
		}else{
			static::$error  = $date->getLastErrors();
			return -1;
		}
	}

	/**
	 * @param        $date1
	 * @param        $date2
	 * @param string $format
	 *
	 * @return int|string
	 */
	public static function diff($date1,$date2,$format = '%R%s'){
		$date   = new \DateTime($date1);
		$re     = $date->diff($date2);
		if($re){
			return $re->format($format);
		}else{
			static::$error  = $date->getLastErrors();
			return -1;
		}
	}
}