<?php
/**
 * Run some codes after some seconds
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\interval;

use core\factory\call;

/**
 * Class interval
 * Run codes after some seconds, for example imagine you have a application with login ability and you want to expire login token after a hour, with this class you can do it easier.
 * You just set the time (in seconds) and your given function will run at the time you wanted.
 * @package core\interval
 */
class interval {
	/**
	 * All of functions that ate waiting to run
	 * @var array
	 */
	protected static $holdings  = [];
	/**
	 * Last run time
	 * @var int
	 */
	protected static $time      = 0;

	/**
	 * Run a code after some seconds
	 * @param callable $function
	 * @param int      $seconds
	 *
	 * @return void
	 */
	public static function timeout(callable $function,$seconds = 60){
		$seconds    = time()+$seconds;
		if(!isset(static::$holdings[$seconds]))
			static::$holdings[$seconds] = [];
		static::$holdings[$seconds][]  = $function;
	}

	/**
	 * Do all holding jobs
	 * @return void
	 */
	public static function run(){
		$time   = time();
		if($time == static::$time)
			return;
		$keys   = array_keys(static::$holdings);
		$i      = count($keys)-1;
		for(;$i > -1;$i--){
			if(($key = $keys[$i]) <= $time){
				$c  = count(static::$holdings[$key])-1;
				for(;$c > -1;$c--){
					call::func(static::$holdings[$key][$c]);
				}
				unset(static::$holdings[$key]);
			}
		}
	}
}