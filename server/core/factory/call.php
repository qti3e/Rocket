<?php
/**
 * This class will call the functions of any class with the right options
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\factory;


/**
 * Class caller
 * @package core\factory
 */
class call {
	/**
	 * Will return it when class does not exists
	 */
	const ClassNotEx    = -100;
	/**
	 * Will return it when method does not exists
	 */
	const MethodNotEx   = -101;
	/**
	 * Convert name to class name
	 * @var array
	 */
	protected static $map   = [];
	/**
	 * Call a function
	 * @param       $class
	 * @param       $method
	 *
	 * @param array $defaultValues
	 *
	 * @return mixed
	 */
	public static function method($class,$method,$defaultValues = []){
		if(!is_object($class) && !class_exists($class)){
			return static::ClassNotEx;
		}
		$class = new \ReflectionClass($class);
		if($class->hasMethod($method) && $class->getMethod($method)->isPublic()){
			$values     = [];
			$parameters = $class->getMethod($method)->getParameters();
			$count      = count($parameters);
			for($i  = 0;$i < $count;$i++){
				$name   = $parameters[$i]->getName();
				$type   = $parameters[$i]->getType();
				if(isset($defaultValues[$name])){
					$values[]   = $defaultValues[$name];
				}elseif ($type === null){
					$values[]   = self::newInstance(self::name2type($name));
				}else{
					$values[]   = self::newInstance($type);
				}
			}
			return call_user_func_array([$class,$method],$values);

		}
		return static::MethodNotEx;
	}

	/**
	 * Create new instance of given class
	 * @param       $class
	 *  Class name or object
	 * @param array $defaultValues
	 *
	 * @return null|object
	 */
	public static function newInstance($class,$defaultValues = []){
		if(class_exists($class) || is_object($class)){
			$class = new \ReflectionClass($class);
			if($class->getConstructor() === null || $class->getConstructor()->getNumberOfParameters() == 0){
				return $class->newInstance();
			}
			$parameters = $class->getConstructor()->getParameters();
			$values     = [];
			$count      = count($parameters);
			for($i  = 0;$i < $count;$i++){
				$name   = $parameters[$i]->getName();
				$type   = $parameters[$i]->getType();
				if(isset($defaultValues[$name])){
					$values[]   = $defaultValues[$name];
				}elseif($type === null){
					$values[]   = self::newInstance(self::name2type($name));
				}else{
					$values[]   = self::newInstance($type);
				}
			}
			return $class->newInstance(...$values);
		}
		return null;
	}

	/**
	 * Convert parameter's name to class name
	 * @param $name
	 *
	 * @return mixed
	 */
	private static function name2type($name){
		return isset(static::$map[$name]) ? static::$map[$name] : $name;
	}
}