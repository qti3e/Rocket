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
use core\controller\URLController;

/**
 * Class configCommand
 * @package core\console\commands
 */
class configCommand implements command{
	/**
	 * Store and save run time changes
	 * @var array
	 */
	private static $changes = [];
	/**
	 * configCommand constructor.
	 *
	 * @param getopt $options
	 */
	public function __construct(getopt $options) {
		$re     = '';
		if(!empty($options->getSwitches())){
			$keys   = array_keys($options->getSwitches());
			$count  = count($options->getSwitches());
			for($i  = 0;$i < $count;$i++){
			    $key= $keys[$i];
			    $val= $options->getSwitches()[$key];
				$re.= str_pad($val,40,' ',STR_PAD_LEFT)."\t->";
				if(isset(static::$changes[$val])){
					$re .= static::$changes[$val];
				}elseif(defined($val)){
				    eval('$re .= '.$val.'."\n";');
			    }else{
				    $re .= "Not defined.\n";
			    }
			}
		}
		$flags  = $options->getFlags();
		$keys   = array_keys($flags);
		$count  = count($flags);
		$file   = str_replace('?>','',file_get_contents(URLController::getConfigFile()));
		for($i  = 0;$i < $count;$i++){
		    $key= $keys[$i];
		    $val= $flags[$key];
			$pa = 'undefined';//last value
			$replace    = 'define(\''.$key.'\',\''.addslashes($val).'\');';
			if(defined($key)){
				eval('$pa   = $val;');
				preg_match_all('/define\(\s*[\'"]('.$key.')[\'"]\s*,\s*[\'"]([\w\.-_]+)[\'"]\s*\)\s*;/',$file,$matches);
				$matches    = $matches[0][0];
				$file   = str_replace($matches,$replace,$file);
			}else{
				$file   .= $replace;
			}
			static::$changes[$key]  = $val;
			$re .= "\n".str_pad($key,40,' ',STR_PAD_LEFT)."\t$pa->$val";
		}
		file_put_contents(URLController::getConfigFile(),$file);
		CommandController::setReturn($re);
	}

	/**
	 * @param help $help
	 *
	 * @return void
	 */
	public static function getHelp(help $help){
		$help->title('Help')
				->description('Use switches to see a value of constant(ex: look at usage)')
				->usage('config -db_host [,...] --db_username "root"')
				->addSwitch('[custom]','get value of constant')
				->addFlag('[custom]','set value of constant.(Value will change after restart the system)');
	}
}