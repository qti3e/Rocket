<?php
/**
 * Transfer user to the right app
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\router;


use core\helpers\helper;
use core\logger\logger;
use core\ws\user;

/**
 * Class routerProvider
 * It helps you to transfer user to the right app
 * @package core\router
 */
class routerProvider {
	/**
	 * User path
	 * @var string
	 */
	protected $path;
	/**
	 * User instance
	 * @var user
	 */
	protected $user;
	/**
	 * Class name of otherwise
	 * @var string
	 */
	protected $other_wise;
	/**
	 * Check if classed matched to any item or not
	 * @var bool
	 */
	protected $is_match = false;

	/**
	 * routerProvider constructor.
	 *
	 * @param user $user
	 */
	public function __construct(user $user) {
		$this->path = $user->path;
		$this->user = $user;
	}

	/**
	 * Select app with the exactly path
	 * @param $path
	 * @param $appName
	 *
	 * @return $this
	 */
	public function exact($path,$appName){
		if(!$this->is_match && $this->path == $path){
			if(class_exists($class  = 'application\\apps\\'.$appName)){
				$this->user->app    = new $class();
				$this->is_match     = true;
				return $this;
			}
			logger::error('Session provider error: Application does not exits.',1);
			return $this;
		}
		return $this;
	}

	/**
	 * Select app with the match pattern
	 * @param $pattern
	 * @param $appName
	 *
	 * @return $this
	 */
	public function pattern($pattern,$appName){
		if(!$this->is_match && helper::checkPattern($pattern,$appName)){
			if(class_exists($class  = 'application\\apps\\'.$appName)){
				$this->user->app    = new $class();
				$this->is_match     = true;
				return $this;
			}
			logger::error('Session provider error: Application does not exits.',1);
			return $this;
		}
		return $this;
	}

	/**
	 * Select this app if other status are not match.
	 * @param $appName
	 *
	 * @return $this
	 */
	public function otherwise($appName){
		if($this->is_match && class_exists($class  = 'application\\apps\\'.$appName)){
			$this->other_wise   = $class;
			return $this;
		}
		logger::error('Session provider error: Application does not exits.',1);
		return $this;
	}

	/**
	 * Set user's app interface
	 */
	public function __destruct() {
		if(!$this->is_match){
			$this->user->app    = new ($this->other_wise)();
		}
	}
}