<?php
/**
 * Sender helper class
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\sender;


use application\protocol;
use core\factory\call;
use core\ws\server;
use core\ws\user;

class sender {
	/**
	 * @var bool|array
	 */
	protected static $users = false;
	/**
	 * The simplest way to send data to the one single user
	 * @param      $message
	 * @param user $user
	 *
	 * @return void
	 */
	public static function toUser($message,user $user){
		$user->send($message);
	}

	/**
	 * Fastest way to send messages to multiple users
	 * @param                 $message
	 * @param \core\ws\user[] ...$users
	 *
	 * @return void
	 */
	public static function toUsers($message,user ...$users){
		$protocols  = [];
		$i          = count($users)-1;
		for(;$i > -1;$i--){
			if(!isset($protocols[$protocol  = $users[$i]->protocol]))
				$protocols[$protocol]       = protocol::$protocol($message);
			$message    = $protocol[$protocol];
			server::send($users[$i],$message);
		}
	}

	/**
	 * @param $message
	 *
	 * @return void
	 */
	public static function toAll($message){
		static::toUsers($message,...server::$users);
	}

	/**
	 * Filter user with your own filter function
	 * @example sending message to all user who are registered and their score is more than 10
	 *  sender::filter(function(user $user){
	 *      return $user->status == 'registered' && $user->score > 10;
	 * });
	 * sender::send("You've been passed the filter!");
	 * @param callable $filter
	 *      The filter function, only gives one argument that is instance of user.
	 * @return int
	 */
	public static function filter(callable $filter){
		if(static::$users === false)
			static::$users  = array_values(server::$users);
		$i  = count(static::$users)-1;
		$r  = 0;
		for(;$i > -1;$i--){
			if(!$filter(static::$users[$i])) {
				unset(static::$users[$i]);
				$r++;
			}
		}
		static::$users  = array_values(static::$users);
		return $r;
	}

	/**
	 * Select users where they property($key) is equal to the value($value).
	 * @param $key
	 *  User's property name
	 * @param $value
	 *
	 * @return int
	 */
	public static function where($key,$value){
		$filter = function(user $user) use ($key,$value){
			return $user->$key == $value;
		};
		return static::filter($filter);
	}

	/**
	 * Select users where they property($key) is NOT equal to the value($value).
	 * @param $key
	 *  User's property name
	 * @param $value
	 *
	 * @return int
	 */
	public static function whereNot($key,$value){
		$filter = function(user $user) use ($key,$value){
			return $user->$key != $value;
		};
		return static::filter($filter);
	}

	/**
	 * Send message to all users in the $users array
	 * @example send message to the all users that are in 5th room
	 *  sender::where('room','5');
	 *  sender::send('You are in room 5');
	 * @param $message
	 *
	 * @return void
	 */
	public static function send($message){
		if(static::$users === false)
			static::$users  = array_values(server::$users);
		static::toUsers($message,...static::$users);
		static::$users  = false;
	}
}