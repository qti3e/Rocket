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

namespace core\console;

/**
 * Class CommandController
 * @package core\console
 */
class CommandController {
	/**
	 * @var
	 */
	protected static $return;
	/**
	 * CommandController constructor.
	 * Print welcome message and ask commands form user
	 */
	public function __construct() {
		print "Welcome to youn cli mode\n>";
		while(strtolower($input = trim(fgets(STDIN)).' ') !== 'exit '){
			if(!empty(trim($input))){
				$input      .=  ' | echo';
				$commands   = static::getCommands($input);
				$count  = count($commands);
				$keys   = array_keys($commands);
				for($i  = 0;$i < $count;$i++){
				    $command    = $commands[$keys[$i]][0];
				    $input      = $commands[$keys[$i]][1];
					if($class = self::where($command)){
						new $class(new getopt($input));
					}else{
						echo 'Sorry but <'.$command.'> command was not found.';
						break;
					}
				}
			}
			echo "\n>";
		}
	}

	/**
	 * @return mixed
	 */
	public static function getLastReturn(){
		return  static::$return;
	}

	/**
	 * @param $re
	 *
	 * @return mixed
	 */
	public static function setReturn($re){
		return static::$return    = $re;
	}

	/**
	 * Print good bye message
	 */
	public function __destruct() {
		print "Good bye!\n";
	}

	/**
	 * @param $string
	 *
	 * @return array
	 */
	protected function getOpt($string){
		return getopt::parse($string);
	}

	/**
	 * @param $input
	 *
	 * @return array
	 */
	public static function getCommands($input){
		$input  = trim($input);
		$input  = str_replace('=',' ',$input);
		/**
		 * @link http://stackoverflow.com/a/2202489/6126002
		 */
		preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $input, $matches);
		$matches        = $matches[0];
		$currentCommand = 0;
		$name           = null;
		$return = [];
		$count  = count($matches);
		for($i  = 0;$i < $count;$i++){
		    $val= $matches[$i];
			if($name === null){
				$currentCommand++;
				$return[$currentCommand]    = ['',''];
				$name   = $val;
				$return[$currentCommand][0] = $name;
			}elseif($val === '|'){
				$name                       = null;
			}else{
				$return[$currentCommand][1] .= ' '.$val;
			}
		}
		return $return;
	}

	/**
	 * @param $command
	 *
	 * @return bool|string
	 */
	public static function where($command){
		$command    = strtolower($command);
		if(class_exists($class = 'core\\console\\commands\\'.$command.'Command')){
			return  $class;
		}
		if(class_exists($class = 'application\\commands'.$command.'Command')){
			return $class;
		}
		return false;
	}
}