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

namespace core\console\commands;


use core\console\command;
use core\console\getopt;
use core\console\help;
use core\controller\URLController;
use core\cryptography\crypto;

/**
 * Class cryptoCommand
 * @package core\console\commands
 */
class cryptoCommand implements command{
	/**
	 * cryptoCommand constructor.
	 *
	 * @param getopt $options
	 */
	public function __construct(getopt $options) {
		if($options->getSwitch('generate')){
			$options->def('len',256);
			$len    = (int)$options->get('len');
			$key    = crypto::generate_key($len);
			$file   = file_get_contents(URLController::getConfigFile());
			preg_match_all('/define\(\s*[\'"](crypto_key)[\'"]\s*,\s*[\'"]([\w\.-_]+)[\'"]\s*\)\s*;/',$file,$matches);
			$matches= $matches[0][0];
			$replace= 'define(\'crypto_key\',\''.addslashes($key).'\');';
			$file   = str_replace($matches,$replace,$file);
			file_put_contents(URLController::getConfigFile(),$file);
		}
	}

	public static function getHelp(help $help){

	}
}