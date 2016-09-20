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

namespace core\cdn;

/**
 * Class cdns
 * @package core\cdn
 */
class cdns {
	/**
	 * @return string
	 */
	public static function jQuery(){
		return '<script src="https://code.jquery.com/jquery-3.1.0.min.js" type="application/javascript"></script>';
	}

	/**
	 * @return string
	 */
	public static function bootstrapCSS(){
		return '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
	}

	/**
	 * @return string
	 */
	public static function bootstrapJS(){
		return '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
	}

	/**
	 * @return string
	 */
	public static function react(){
		return '<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/react/15.2.1/react.min.js"></script>';
	}

	/**
	 * @return string
	 */
	public static function vue(){
		return '<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.min.js"></script>';
	}

	/**
	 * @return string
	 */
	public static function impress(){
		return '<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/impress.js/0.5.3/impress.min.js"></script>';
	}
}