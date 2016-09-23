<?php
/**
 * Cach function's output to redis
 *  Now it only supports redis
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\caching;

use core\factory\call;
use core\redis\redis;

/**
 * Class mCatch
 * @package core\mCatch
 */
class caching {
	/**
	 * Catch the output of function to the storage so it won't run again if it's
	 * @param          $seconds
	 * @param callable $function
	 * @param array    $dependencies
	 *
	 * @return mixed
	 */
	public function caching($seconds,callable $function,$dependencies = []){
		array_multisort($dependencies);
		$seconds    = (int)$seconds;
		$backTrace  = debug_backtrace()[0];
		$hash       = md5(json_encode($dependencies).($backTrace['file'].$backTrace['line'].$backTrace['function']));
		$redis      = new redis();
		$return     = $redis->get($hash);
		if($return){
			return unserialize($return);
		}
		$return     = call::func($function);
		$redis->set($hash,serialize($return));
		if($seconds > 0){
			$redis->expire($hash,$seconds);
		}
		return $return;
	}
}