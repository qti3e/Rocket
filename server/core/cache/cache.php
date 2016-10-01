<?php
/**
 * Cach function's output to redis
 *  Now it only supports redis
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\cache;

use core\factory\call;
use core\rocket\rocket;

/**
 * Class mCatch
 * @package core\mCatch
 */
class cache {
	/**
	 * Catch the output of function to the storage so it won't run again if it's
	 * @param          $seconds
	 * @param callable $function
	 * @param array    $dependencies
	 * @param string   $id
	 *
	 * @return mixed
	 */
	public static function cache($seconds,callable $function,$dependencies = [],&$id = null){
		array_multisort($dependencies);
		$seconds    = (int)$seconds;
		$backTrace  = debug_backtrace()[0];
		$hash       = md5(json_encode($dependencies).($backTrace['file'].$backTrace['line'].$backTrace['function']));
		$id         = $hash;
		$redis      = rocket::redis();
		$return     = $redis->get($hash);
		if($return){
			return unserialize($return);
		}
		$return     = call::func($function);
		$redis->sAdd('cache_history',$hash);
		$redis->set($hash,serialize($return));
		if($seconds > 0){
			$redis->expire($hash,$seconds);
		}
		return $return;
	}
}