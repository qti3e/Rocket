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

namespace core\i18n;

use core\exception\error_handler;

/**
 * Class lang
 * @package core\i18n
 */
class lang {
	/**
	 * @var string
	 */
	private static $lang = '';

	/**
	 * @param $str
	 *
	 * @return string
	 */
	public static function get($str){
		$str    = strtolower($str);
		if(isset(static::$lang[$str])){
			return static::$lang[$str];
		}
		return ucwords(str_replace('_',' ',$str));
	}

	/**
	 * @param $langCode
	 *
	 * @return bool
	 */
	public static function load($langCode){
		if(file_exists('application/langs/'.$langCode.'.php')){
			static::$lang = include('application/langs/'.$langCode.'.php');
		}
		if(file_exists('core/i18n/langs/'.$langCode.'.php')){
			static::$lang = include 'core/i18n/langs/'.$langCode.'.php';
			return true;
		}
		error_handler::DisplayError('Can\'t load language file','Can not find "core/i18n/langs/'.$langCode.'.php"');
		return false;
	}

	/**
	 * lang constructor.
	 *
	 * @param string $langCode
	 */
	public function __construct($langCode = '') {
		if($langCode !== ''){
			static::load($langCode);
		}
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function __get($name) {
		return static::get($name);
	}
}