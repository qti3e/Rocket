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
 * Interface driver
 * @package core\database
 */
interface driver {
	/**
	 * @return mixed
	 */
	public function close();

	/**
	 * @return mixed
	 */
	public function getObject();

	/**
	 * @return mixed
	 */
	public function getErrorDetail();

	/**
	 * @return mixed
	 */
	public function isError();

	/**
	 * @param $query
	 *
	 * @return mixed
	 */
	public function query($query);

	/**
	 * @param $result
	 *
	 * @return mixed
	 */
	public function num_rows($result);

	/**
	 * @param $result
	 *
	 * @return mixed
	 */
	public function fetch_assoc($result);
	
	/**
	 * @param $result
	 *
	 * @return mixed
	 */
	public function fetch_all($result);
}