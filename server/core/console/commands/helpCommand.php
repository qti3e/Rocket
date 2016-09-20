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
use core\console\CommandController;
use core\console\getopt;
use core\console\help;

/**
 * Class helpCommand
 * @package core\console\commands
 */
class helpCommand implements command{
	/**
	 * helpCommand constructor.
	 *
	 * @param getopt $opts
	 */
	public function __construct(getopt $opts) {
		$class      = 'core\\console\\commands\\'.strtolower($opts->getSubCommand()).'Command';
		if(empty(trim($opts->getSubCommand()))){
			$class  = 'core\\console\\commands\\helpCommand';
		}
		if(class_exists($class)){
			$help   = new help();
			$class::getHelp($help);
			if($opts->getSwitch('html') || $opts->getSwitch('h')){
				$re = ($help->html());
			}else{
				$re = $help->string();
			}
			$opts->def('save',$opts->get('s'));
			if($opts->get('save') === null){
				CommandController::setReturn($re);
			}else{
				//todo: use fopen instead of file_put_contents because file_put_contents writes data after console close
				file_put_contents($opts->get('save'),$re);
				CommandController::setReturn("Document saved at <".$opts->get('save').">");
			}
		}elseif(!empty(trim($opts->getSubCommand()))){
			CommandController::setReturn("Command <{$opts->getSubCommand()}> was not found.");
		}
	}

	/**
	 * @param help $help
	 *
	 * @return void
	 */
	public static function getHelp(help $help) {
		$help->title('Help')
				->description('Show helps for a command if exists.')
				->usage("help [command name]")
				->addSwitch('(h)tml','Print output in html page format.')
				->addFlag('(s)ave=file name','Don\'t print output to the screen and save it to entered file name');
	}
}