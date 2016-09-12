<?php
/**
 * WebSocketUser object
 */

namespace core\ws;

/**
 * Class WebSocketUser
 * @package core\ws
 */
class WebSocketUser {
  /**
   * Collect users data
   * @var array
   */
  private $data = [];
  /**
   * Socket resource
   * @var
   */
  public $socket;
  /**
   * Connection id
   * @var
   */
  public $id;
  /**
   * Users header
   * @var array
   */
  public $headers = array();
  /**
   * Check user handshake
   * @var bool
   */
  public $handshake = false;
  /**
   * Check if user sends messages in partial packet
   * @var bool
   */
  public $handlingPartialPacket = false;
  /**
   * Part of full message
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
   * Is connection closed
   * @var bool
   */
  public $hasSentClose = false;

  /**
   * WebSocketUser constructor.
   *
   * @param $id
   *  Connection id
   * @param $socket
   *  Socket resource
   */
  function __construct($id, $socket) {
    $this->id = $id;
    $this->socket = $socket;
  }

  /**
   * Set a user data
   * @param $name
   *  Name of data
   * @param $value
   *  Value
   *
   * @return mixed
   */
  public function __set($name, $value) {
    return $this->data[$name]  = $value;
  }

  /**
   * Get value of a data
   * @param $name
   *  Name of data you want to get it's value
   * @return mixed
   */
  public function __get($name) {
    if(isset($this->data[$name])){
      return $this->data[$name];
    }
    return null;
  }

  /**
   * Check if data is exists or not
   * @param $name
   *  Parameter name
   * @return bool
   *  True if data exists
   */
  public function __isset($name) {
    return isset($this->data);
  }

  /**
   * Serialize all of user data
   * @return array
   */
  public function __sleep() {
    return get_object_vars($this);
  }
}