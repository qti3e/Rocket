<?php
/**
 * User object
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\ws;

/**
 * Class user
 *      Users instance
 * @package core\ws
 */
class user {
	/**
	 * Instance of socket connection
	 * @var
	 */
	public $socket;
	/**
	 * User unique id
	 * @var
	 */
	public $id;
	/**
	 * This array contains all of request header
	 * @var array
	 */
	public $headers = array();
	/**
	 * Handshake buffer
	 * @var bool
	 */
	public $handshake = false;
	/**
	 * User ip address
	 * @var
	 */
	public $ip;
	/**
	 * Request path
	 * @var
	 */
	public $path;
	/**
	 * Request resource query part
	 * (It's like $_GET in http requests)
	 * @var array
	 */
	public $query   = [];
	/**
	 * The string after # sign
	 * abc/a#5 => 5
	 * @var
	 */
	public $fragment;
	/**
	 * Accepted WebSocket protocol
	 * @var
	 */
	public $protocol;
	/**
	 * User's unique session id
	 * @var
	 */
	public $sessionId;
	/**
	 * All of data that you set to user will be here
	 * @var array
	 */
	public $data   = [];
	/**
	 * @var bool
	 */
	public $handlingPartialPacket = false;
	/**
	 * @var string
	 */
	public $partialBuffer = "";
	/**
	 * @var bool
	 */
	public $sendingContinuous = false;
	/**
	 * @var string
	 */
	public $partialMessage = "";
	/**
	 * @var bool
	 */
	public $hasSentClose = false;

	/**
	 * Set user id and socket resource
	 *
	 * @param $id
	 * @param $socket
	 */
	function __construct($id, $socket) {
		$this->id = $id;
		$this->socket = $socket;
	}

	/**
	 * Set a new property
	 * @param $name
	 * @param $value
	 *
	 * @return mixed
	 */
	public function __set($name, $value) {
		return $this->data[$name]  = $value;
	}

	/**
	 * Get custom property
	 * @param $name
	 *
	 * @return mixed|null
	 */
	public function __get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	/**
	 * Check if a property is set or not
	 * @param $name
	 *
	 * @return bool
	 */
	public function __isset($name) {
		return isset($this->data[$name]);
	}
}