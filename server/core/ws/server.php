<?php
/**
 * A simple websocket class based on https://github.com/ghedipunk/PHP-Websockets
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\ws;


use application\protocol;
use core\config\config;
use core\factory\call;
use core\interval\interval;
use core\logger\logger;
use core\roc\roc;

/**
 * Class server
 * @package core\ws
 */
class server {
	/**
	 * User interface class
	 * @see user
	 * @var string
	 */
	protected $userClass = 'core\\ws\\user';
	/**
	 * Max buffer size config
	 * @var int
	 */
	protected $maxBufferSize;
	/**
	 * @var resource
	 */
	protected $master;
	/**
	 * Array of all connection's sockets
	 * @var array
	 */
	protected $sockets                              = array();
	/**
	 * List of all online users
	 * @var array
	 */
	public static $users                            = array();
	/**
	 * Array of holding messages
	 * @var array
	 */
	public static $heldMessages                     = array();
	/**
	 * Print report to screen when it's true
	 * @var bool
	 */
	protected $interactive                          = true;
	/**
	 * List of all blocked IPs
	 * @var array
	 */
	protected $blockedIP                            = array();

	/**
	 * server constructor.
	 *
	 * @param     $addr
	 * @param     $port
	 * @param int $bufferLength
	 */
	function __construct($addr, $port, $bufferLength = 2048) {
		$this->maxBufferSize = $bufferLength;
		$this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)  or die("Failed: socket_create()");
		socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) or die("Failed: socket_option()");
		socket_bind($this->master, $addr, $port)                      or die("Failed: socket_bind()");
		socket_listen($this->master,20)                               or die("Failed: socket_listen()");
		$this->sockets['m'] = $this->master;
		$this->stdout("Server started\nListening on: $addr:$port\nMaster socket: ".$this->master);
	}

	/**
	 * Decode message and pass it to process method in application\router
	 * @param user $user
	 * @param $message
	 *
	 * @return void
	 */
	protected function process($user,$message){
		$protocol   = $user->protocol;
		$message    = protocol::$protocol($message);
		call::register('user',$user);
		call::register('message',$message);
		call::method('application\\router','process');
		call::clear();
	}

	/**
	 * Handle user connections
	 * @param user $user
	 *
	 * @return void
	 */
	protected function connected($user){
		call::register('user',$user);
		call::method('application\\router','connected');
		call::clear();
	}

	/**
	 * Handle disconnections
	 * @param user $user
	 *
	 * @return void
	 */
	protected function closed($user){
		call::register('user',$user);
		call::method('application\\router','closed');
		call::clear();
	}

	/**
	 * Override to handle a connecting user, after the instance of the User is created, but before
	 *  the handshake has completed.
	 * @param user $user
	 *
	 * @return void
	 */
	protected function connecting($user) {}

	/**
	 * Send message to user (Outside of any sub-protocol)
	 * @param $user
	 * @param $message
	 *
	 * @return void
	 */
	public static function send($user, $message) {
		if ($user->handshake) {
			$message = static::frame($message,$user);
			$result = @socket_write($user->socket, $message, strlen($message));
		}
		else {
			// User has not yet performed their handshake.  Store for sending later.
			$holdingMessage = array('user' => $user, 'message' => $message);
			static::$heldMessages[] = $holdingMessage;
		}
	}

	/**
	 * Override this for any process that should happen periodically.  Will happen at least once
	 * per second, but possibly more often.
	 * @return void
	 */
	protected function tick() {}

	/**
	 * Core maintenance processes, such as retrying failed messages.
	 * @return void
	 */
	protected function _tick() {
		foreach (static::$heldMessages as $key => $hm) {
			$found = false;
			foreach (static::$users as $currentUser) {
				if ($hm['user']->socket == $currentUser->socket) {
					$found = true;
					if ($currentUser->handshake) {
						unset(static::$heldMessages[$key]);
						$this->send($currentUser, $hm['message']);
					}
				}
			}
			if (!$found) {
				// If they're no longer in the list of connected users, drop the message.
				unset(static::$heldMessages[$key]);
			}
		}
	}

	/**
	 * Main processing loop
	 */
	public function run() {
		while(true) {
			//Run time out method
			interval::run();
			if (empty($this->sockets)) {
				$this->sockets['m'] = $this->master;
			}
			$read = $this->sockets;
			$write = $except = null;
			$this->_tick();
			$this->tick();
			@socket_select($read,$write,$except,1);
			foreach ($read as $socket) {
				if ($socket == $this->master) {
					$client = socket_accept($socket);
					if ($client < 0) {
						$this->stderr("Failed: socket_accept()");
						continue;
					}
					else {
						$this->connect($client);
						$this->stdout("Client connected. " . $client);
					}
				}
				else {
					$numBytes = @socket_recv($socket, $buffer, $this->maxBufferSize, 0);
					if ($numBytes === false) {
						$sockErrNo = socket_last_error($socket);
						switch ($sockErrNo)
						{
							case 102: // ENETRESET    -- Network dropped connection because of reset
							case 103: // ECONNABORTED -- Software caused connection abort
							case 104: // ECONNRESET   -- Connection reset by peer
							case 108: // ESHUTDOWN    -- Cannot send after transport endpoint shutdown -- probably more of an error on our part, if we're trying to write after the socket is closed.  Probably not a critical error, though.
							case 110: // ETIMEDOUT    -- Connection timed out
							case 111: // ECONNREFUSED -- Connection refused -- We shouldn't see this one, since we're listening... Still not a critical error.
							case 112: // EHOSTDOWN    -- Host is down -- Again, we shouldn't see this, and again, not critical because it's just one connection and we still want to listen to/for others.
							case 113: // EHOSTUNREACH -- No route to host
							case 121: // EREMOTEIO    -- Rempte I/O error -- Their hard drive just blew up.
							case 125: // ECANCELED    -- Operation canceled

								$this->stderr("Unusual disconnect on socket " . $socket);
								$this->disconnect($socket, true, $sockErrNo); // disconnect before clearing error, in case someone with their own implementation wants to check for error conditions on the socket.
								break;
							default:

								$this->stderr('Socket error: ' . socket_strerror($sockErrNo));
						}

					}
					elseif ($numBytes == 0) {
						$this->disconnect($socket);
						$this->stderr("Client disconnected. TCP connection lost: " . $socket);
					}
					else {
						$user = $this->getUserBySocket($socket);
						if (!$user->handshake) {
							$tmp = str_replace("\r", '', $buffer);
							if (strpos($tmp, "\n\n") === false ) {
								continue; // If the client has not finished sending the header, then wait before sending our upgrade response.
							}
							$this->doHandshake($user,$buffer);
						}
						else {
							//split packet into frame and send it to deframe
							$this->split_packet($numBytes,$buffer, $user);
						}
					}
				}
			}
		}
	}

	/**
	 * Create a new user object when it connects
	 * @param $socket
	 *
	 * @return void
	 */
	protected function connect($socket) {
		$user = new $this->userClass(uniqid('u'), $socket);
		static::$users[$user->id] = $user;
		$this->sockets[$user->id] = $socket;
		$this->connecting($user);
	}

	/**
	 * Close the user's connection
	 * @param      $socket
	 * @param bool $triggerClosed
	 * @param null $sockErrNo
	 *
	 * @return void
	 */
	protected function disconnect($socket, $triggerClosed = true, $sockErrNo = null) {
		$disconnectedUser = $this->getUserBySocket($socket);

		if ($disconnectedUser !== null) {
			unset(static::$users[$disconnectedUser->id]);

			if (array_key_exists($disconnectedUser->id, $this->sockets)) {
				unset($this->sockets[$disconnectedUser->id]);
			}

			if (!is_null($sockErrNo)) {
				socket_clear_error($socket);
			}

			if ($triggerClosed) {
				$this->stdout("Client disconnected. ".$disconnectedUser->socket);
				$this->closed($disconnectedUser);
				socket_close($disconnectedUser->socket);
			}
			else {
				$message = static::frame('', $disconnectedUser, 'close');
				@socket_write($disconnectedUser->socket, $message, strlen($message));
			}
		}
	}

	/**
	 * Parse headers and send the match header response
	 * @param user $user
	 * @param $buffer
	 *
	 * @return void
	 */
	protected function doHandshake($user, $buffer) {
		$magicGUID = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
		$headers = array();
		$lines = explode("\n",$buffer);
		foreach ($lines as $line) {
			if (strpos($line,":") !== false) {
				$header = explode(":",$line,2);
				$headers[strtolower(trim($header[0]))] = trim($header[1]);
			}
			elseif (stripos($line,"get ") !== false) {
				preg_match("/GET (.*) HTTP/i", $buffer, $reqResource);
				$headers['get'] = trim($reqResource[1]);
			}
		}
		if (isset($headers['get'])) {
			$user->requestedResource = $headers['get'];
			$parse              = parse_url($headers['get']);
			$user->path         = trim($parse['path'],'/');
			$user->fragment     = isset($parse['fragment']) ? $parse['fragment']        : null;
			if(isset($parse['query'])){
				parse_str($parse['query'],$user->query);
			}
		}
		socket_getpeername($user->socket,$address);
		$user->ip   = $address;
		$cookies    = [];
		if(isset($headers['cookie'])){
			$headers['cookie']  = explode(';',$headers['cookie']);
			$i  = count($headers['cookie'])-1;
			for(;$i > -1;$i--){
				$a  = explode('=',$headers['cookie'][$i]);
				$cookies[rawurldecode(ltrim($a[0]))]   = rawurldecode(rtrim($a[1],';'));
			}
		}
		$user->cookies  = $cookies;
		if(!isset($headers['sec-websocket-key'])){
			if(preg_match('/^'.config::get('cp_ip_pattern').'$/',$address)){
				$user->path = ($user->path == '') ? 'index.roc' : $user->path;
				//Check user to don't go to the parent directories
				str_replace('..','',$user->path,$count);
				if($count == 0){
					$result     = roc::getParsedFile($file = 'core/www/'.$user->path);
					if($result){
						$handshakeResponse  = "HTTP/1.1 200 OK\r\nServer: ".config::get('version')." (".PHP_OS.")\r\nAccess-Control-Allow-Origin: *\r\n$result";
						socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
						$this->disconnect($user->socket);
						return;
					}elseif(!is_dir($file)){
						$handshakeResponse  = "HTTP/1.1 404 Not Found\r\nServer: ".config::get('version')." (".PHP_OS.")\r\nAccess-Control-Allow-Origin: *\r\n".roc::getParsedFile('core/www/404.roc');
						socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
						$this->disconnect($user->socket);
						return;
					}
				}
			}
			$handshakeResponse  = "HTTP/1.1 403 Forbidden\r\nServer: ".config::get('version')." (".PHP_OS.")\r\nAccess-Control-Allow-Origin: *\r\n".roc::getParsedFile('core/www/403.roc');
			socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
			$this->disconnect($user->socket);
			return;
		}
		else {
			// todo: fail the connection
			$handshakeResponse = "HTTP/1.1 405 Method Not Allowed\r\nServer: ".config::get('version')." (".PHP_OS.")\r\n\r\n";
		}
		if (!isset($headers['host']) || !$this->checkHost($headers['host'])) {
			$handshakeResponse = "HTTP/1.1 400 Bad Request\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		if (!isset($headers['upgrade']) || strtolower($headers['upgrade']) != 'websocket') {
			$handshakeResponse = "HTTP/1.1 400 Bad Request\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		if (!isset($headers['connection']) || strpos(strtolower($headers['connection']), 'upgrade') === FALSE) {
			$handshakeResponse = "HTTP/1.1 400 Bad Request\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		if (!isset($headers['sec-websocket-key'])) {
			$handshakeResponse = "HTTP/1.1 400 Bad Request\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		if (!isset($headers['sec-websocket-version']) || strtolower($headers['sec-websocket-version']) != 13) {
			$handshakeResponse = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		if (!isset($headers['origin']) || !$this->checkOrigin($headers['origin'])) {
			$handshakeResponse = "HTTP/1.1 403 Forbidden\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		if(!$this->checkIP($this->getUserIP($user)['address'])){
			$handshakeResponse = "HTTP/1.1 403 Forbidden\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		$protocol   = true;
		if(!isset($headers['sec-websocket-protocol'])){
			$protocol   = false;
			$headers['sec-websocket-protocol']  = protocol::def;
		}
		if (!$this->checkWebsocProtocol($headers['sec-websocket-protocol'])) {
			$handshakeResponse = "HTTP/1.1 400 Bad Request\r\nServer: ".config::get('version')." (".PHP_OS.")";
		}
		// Done verifying the _required_ headers and optionally required headers.
		if (isset($handshakeResponse)) {
			socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
			$this->disconnect($user->socket);
			return;
		}

		$user->headers      = $headers;
		$user->handshake    = $buffer;
		$webSocketKeyHash   = sha1($headers['sec-websocket-key'] . $magicGUID);

		$rawToken = "";
		for ($i = 0; $i < 20; $i++) {
			$rawToken .= chr(hexdec(substr($webSocketKeyHash,$i*2, 2)));
		}
		$handshakeToken = base64_encode($rawToken) . "\r\n";
		$user->protocol = protocol::def;
		$subProtocol = $protocol ? 'Sec-WebSocket-Protocol: '.($user->protocol = $this->processProtocol($headers['sec-websocket-protocol']))."\r\n" : "";
		$extensions = (isset($headers['sec-websocket-extensions'])) ? $this->processExtensions($headers['sec-websocket-extensions'])."\r\n" : "";
		call::register('user',$user);
		call::register('headers',$headers);
		$customHeaders  = call::method('application\\router','customHeaders');
		if(!is_array($customHeaders)){
			$customHeaders  = [];
		}
		if(isset($cookies['ROCKETSSID']) && preg_match('/^[0-9a-f]{32}$/',$cookies['ROCKETSSID'])){
			$user->sessionId    = $cookies['ROCKETSSID'];
		}else{
			$user->sessionId    = md5(uniqid('session_id'));
			$customHeaders[]    = ["Set-Cookie","ROCKETSSID=".$user->sessionId."; expires=Wed, 21 Oct 2038 00:00:00 GMT"];
		}
		$custom = "";
		$count  = count($customHeaders);
		$keys   = array_keys($customHeaders);
		for($i  = 0;$i < $count;$i++){
			if(is_array($customHeaders[$keys[$i]]) && count($customHeaders[$keys[$i]]) == 2){
				$custom .= $customHeaders[$keys[$i]][0].": ".trim($customHeaders[$keys[$i]][1])."\r\n";
			}elseif(is_array($customHeaders[$keys[$i]])){
				$custom .= $customHeaders[$keys[$i]];
			}
		}
		$handshakeResponse = "HTTP/1.1 101 Switching Protocols\r\n".config::get('version')." (".PHP_OS.")\r\n{$custom}Upgrade: websocket\r\nConnection: Upgrade\r\nSec-WebSocket-Accept: $handshakeToken$subProtocol$extensions";
		socket_write($user->socket,$handshakeResponse,strlen($handshakeResponse));
		$this->connected($user);
	}

	/**
	 * Check if host name is allowed
	 * @param $hostName
	 *
	 * @return mixed
	 */
	protected function checkHost($hostName) {
		call::register('hostName',$hostName);
		$re = call::method('application\\router','checkHost');
		call::clear();
		return $re;
	}

	/**
	 * Check if origin is allowed
	 * @param $origin
	 *
	 * @return mixed
	 */
	protected function checkOrigin($origin) {
		call::register('origin',$origin);
		$re = call::method('application\\router','checkOrigin');
		call::clear();
		return $re;
	}

	/**
	 * Check if the protocol does exits or not
	 * @param arra $protocol
	 *
	 * @return bool
	 */
	protected function checkWebsocProtocol($protocol) {
		$protocol   = explode(',',$protocol);
		$count      = count($protocol)-1;
		for(;$count > -1;$count--){
			if(is_callable(['application\\protocol',trim($protocol[$count])])){
				return true;
			}
		}
		return false;
	}

	/**
	 * Check host name and check if it's allowed
	 * @param $extensions
	 *
	 * @return bool
	 */
	protected function checkWebsocExtensions($extensions) {
		return true;
	}

	/**
	 * Select one of supported protocols
	 * @param array $protocol
	 *
	 * @return bool|string
	 */
	protected function processProtocol($protocol) {
		$protocol   = explode(',',$protocol);
		$count      = count($protocol)-1;
		for(;$count > -1;$count--){
			if(is_callable(['application\\protocol',$n = trim($protocol[$count])])){
				return $n;
			}
		}
		return false;
	}

	/**
	 * Return either "Sec-WebSocket-Extensions: SelectedExtensions\r\n" or return an empty string.
	 * @param $extensions
	 *
	 * @return string
	 */
	protected function processExtensions($extensions) {
		return "";
	}

	/**
	 * Search for a user with it's socket address
	 * @param $socket
	 *
	 * @return mixed|null
	 */
	protected function getUserBySocket($socket) {
		foreach (static::$users as $user) {
			if ($user->socket == $socket) {
				return $user;
			}
		}
		return null;
	}

	/**
	 * Print messages to screen and store them
	 * @param $message
	 *
	 * @return void
	 */
	public function stdout($message) {
		logger::info($message,1);
		if ($this->interactive) {
			echo "$message\n";
		}
	}

	/**
	 * Print errors to screen and store them
	 * @param $message
	 *
	 * @return void
	 */
	public function stderr($message) {
		logger::error($message,1);
		if ($this->interactive) {
			echo "$message\n";
		}
	}

	/**
	 * Encrypt message to write on the socket
	 * @param        $message
	 * @param        $user
	 * @param string $messageType
	 * @param bool   $messageContinues
	 *
	 * @return string
	 */
	public static function frame($message, $user, $messageType='text', $messageContinues=false) {
		switch ($messageType) {
			case 'continuous':
				$b1 = 0;
				break;
			case 'text':
				$b1 = ($user->sendingContinuous) ? 0 : 1;
				break;
			case 'binary':
				$b1 = ($user->sendingContinuous) ? 0 : 2;
				break;
			case 'close':
				$b1 = 8;
				break;
			case 'ping':
				$b1 = 9;
				break;
			case 'pong':
				$b1 = 10;
				break;
		}
		if ($messageContinues) {
			$user->sendingContinuous = true;
		}
		else {
			$b1 += 128;
			$user->sendingContinuous = false;
		}

		$length = strlen($message);
		$lengthField = "";
		if ($length < 126) {
			$b2 = $length;
		}
		elseif ($length < 65536) {
			$b2 = 126;
			$hexLength = dechex($length);
			//$this->stdout("Hex Length: $hexLength");
			if (strlen($hexLength)%2 == 1) {
				$hexLength = '0' . $hexLength;
			}
			$n = strlen($hexLength) - 2;

			for ($i = $n; $i >= 0; $i=$i-2) {
				$lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
			}
			while (strlen($lengthField) < 2) {
				$lengthField = chr(0) . $lengthField;
			}
		}
		else {
			$b2 = 127;
			$hexLength = dechex($length);
			if (strlen($hexLength)%2 == 1) {
				$hexLength = '0' . $hexLength;
			}
			$n = strlen($hexLength) - 2;

			for ($i = $n; $i >= 0; $i=$i-2) {
				$lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
			}
			while (strlen($lengthField) < 8) {
				$lengthField = chr(0) . $lengthField;
			}
		}

		return chr($b1) . chr($b2) . $lengthField . $message;
	}

	/**
	 * check packet if he have more than one frame and process each frame individually
	 * @param $length
	 * @param $packet
	 * @param $user
	 *
	 * @return void
	 */
	protected function split_packet($length,$packet, $user) {
		//add PartialPacket and calculate the new $length
		if ($user->handlingPartialPacket) {
			$packet = $user->partialBuffer . $packet;
			$user->handlingPartialPacket = false;
			$length=strlen($packet);
		}
		$fullpacket=$packet;
		$frame_pos=0;
		$frame_id=1;

		while($frame_pos<$length) {
			$headers = $this->extractHeaders($packet);
			$headers_size = $this->calcoffset($headers);
			$framesize=$headers['length']+$headers_size;

			//split frame from packet and process it
			$frame=substr($fullpacket,$frame_pos,$framesize);

			if (($message = $this->deframe($frame, $user,$headers)) !== FALSE) {
				if ($user->hasSentClose) {
					$this->disconnect($user->socket);
				} else {
					if ((preg_match('//u', $message)) || ($headers['opcode']==2)) {
						//$this->stdout("Text msg encoded UTF-8 or Binary msg\n".$message);
						$this->process($user, $message);
					} else {
						$this->stderr("not UTF-8\n");
					}
				}
			}
			//get the new position also modify packet data
			$frame_pos+=$framesize;
			$packet=substr($fullpacket,$frame_pos);
			$frame_id++;
		}
	}

	/**
	 * @param $headers
	 *
	 * @return int
	 */
	protected function calcoffset($headers) {
		$offset = 2;
		if ($headers['hasmask']) {
			$offset += 4;
		}
		if ($headers['length'] > 65535) {
			$offset += 8;
		} elseif ($headers['length'] > 125) {
			$offset += 2;
		}
		return $offset;
	}

	/**
	 * @param $message
	 * @param $user
	 *
	 * @return bool|int|string
	 */
	protected function deframe($message, &$user) {
		//echo $this->strtohex($message);
		$headers = $this->extractHeaders($message);
		$pongReply = false;
		$willClose = false;
		switch($headers['opcode']) {
			case 0:
			case 1:
			case 2:
				break;
			case 8:
				// todo: close the connection
				$user->hasSentClose = true;
				return "";
			case 9:
				$pongReply = true;
			case 10:
				break;
			default:
				//$this->disconnect($user); // todo: fail connection
				$willClose = true;
				break;
		}

		/* Deal by split_packet() as now deframe() do only one frame at a time.
		if ($user->handlingPartialPacket) {
		  $message = $user->partialBuffer . $message;
		  $user->handlingPartialPacket = false;
		  return $this->deframe($message, $user);
		}
		*/

		if ($this->checkRSVBits($headers,$user)) {
			return false;
		}

		if ($willClose) {
			// todo: fail the connection
			return false;
		}

		$payload = $user->partialMessage . $this->extractPayload($message,$headers);

		if ($pongReply) {
			$reply = self::frame($payload,$user,'pong');
			socket_write($user->socket,$reply,strlen($reply));
			return false;
		}
		if ($headers['length'] > strlen($this->applyMask($headers,$payload))) {
			$user->handlingPartialPacket = true;
			$user->partialBuffer = $message;
			return false;
		}

		$payload = $this->applyMask($headers,$payload);

		if ($headers['fin']) {
			$user->partialMessage = "";
			return $payload;
		}
		$user->partialMessage = $payload;
		return false;
	}

	/**
	 * Extract header values
	 * @param $message
	 *
	 * @return array
	 */
	protected function extractHeaders($message) {
		$header = array('fin'     => $message[0] & chr(128),
			'rsv1'    => $message[0] & chr(64),
			'rsv2'    => $message[0] & chr(32),
			'rsv3'    => $message[0] & chr(16),
			'opcode'  => ord($message[0]) & 15,
			'hasmask' => $message[1] & chr(128),
			'length'  => 0,
			'mask'    => "");
		$header['length'] = (ord($message[1]) >= 128) ? ord($message[1]) - 128 : ord($message[1]);

		if ($header['length'] == 126) {
			if ($header['hasmask']) {
				$header['mask'] = $message[4] . $message[5] . $message[6] . $message[7];
			}
			$header['length'] = ord($message[2]) * 256
				+ ord($message[3]);
		}
		elseif ($header['length'] == 127) {
			if ($header['hasmask']) {
				$header['mask'] = $message[10] . $message[11] . $message[12] . $message[13];
			}
			$header['length'] = ord($message[2]) * 65536 * 65536 * 65536 * 256
				+ ord($message[3]) * 65536 * 65536 * 65536
				+ ord($message[4]) * 65536 * 65536 * 256
				+ ord($message[5]) * 65536 * 65536
				+ ord($message[6]) * 65536 * 256
				+ ord($message[7]) * 65536
				+ ord($message[8]) * 256
				+ ord($message[9]);
		}
		elseif ($header['hasmask']) {
			$header['mask'] = $message[2] . $message[3] . $message[4] . $message[5];
		}
		//echo $this->strtohex($message);
		//$this->printHeaders($header);
		return $header;
	}

	/**
	 * @param $message
	 * @param $headers
	 *
	 * @return string
	 */
	protected function extractPayload($message,$headers) {
		$offset = 2;
		if ($headers['hasmask']) {
			$offset += 4;
		}
		if ($headers['length'] > 65535) {
			$offset += 8;
		}
		elseif ($headers['length'] > 125) {
			$offset += 2;
		}
		return substr($message,$offset);
	}

	/**
	 * @param $headers
	 * @param $payload
	 *
	 * @return int
	 */
	protected function applyMask($headers,$payload) {
		$effectiveMask = "";
		if ($headers['hasmask']) {
			$mask = $headers['mask'];
		}
		else {
			return $payload;
		}

		while (strlen($effectiveMask) < strlen($payload)) {
			$effectiveMask .= $mask;
		}
		while (strlen($effectiveMask) > strlen($payload)) {
			$effectiveMask = substr($effectiveMask,0,-1);
		}
		return $effectiveMask ^ $payload;
	}

	/**
	 * @param $headers
	 * @param $user
	 *
	 * @return bool
	 */
	protected function checkRSVBits($headers,$user) { // override this method if you are using an extension where the RSV bits are used.
		if (ord($headers['rsv1']) + ord($headers['rsv2']) + ord($headers['rsv3']) > 0) {
			//$this->disconnect($user); // todo: fail connection
			return true;
		}
		return false;
	}

	/**
	 * Convert string to hex
	 * @param $str
	 *
	 * @return string
	 */
	protected function strtohex($str) {
		$strout = "";
		for ($i = 0; $i < strlen($str); $i++) {
			$strout .= (ord($str[$i])<16) ? "0" . dechex(ord($str[$i])) : dechex(ord($str[$i]));
			$strout .= " ";
			if ($i%32 == 7) {
				$strout .= ": ";
			}
			if ($i%32 == 15) {
				$strout .= ": ";
			}
			if ($i%32 == 23) {
				$strout .= ": ";
			}
			if ($i%32 == 31) {
				$strout .= "\n";
			}
		}
		return $strout . "\n";
	}

	/** Print  headers to the screen
	 * @param $headers
	 *
	 * @return void
	 */
	protected function printHeaders($headers) {
		echo "Array\n(\n";
		foreach ($headers as $key => $value) {
			if ($key == 'length' || $key == 'opcode') {
				echo "\t[$key] => $value\n\n";
			}
			else {
				echo "\t[$key] => ".$this->strtohex($value)."\n";

			}

		}
		echo ")\n";
	}

	/**
	 * Get user's IP from connection
	 * @param $user
	 *
	 * @return array
	 */
	protected function getUserIP($user){
		socket_getpeername($user->socket,$address,$port);
		return [
			'address' =>$address,
			'port'    =>$port
		];
	}

	/**
	 * Check if user's IP is allowed (not in block list)
	 * @param $ip
	 *
	 * @return bool
	 */
	protected function checkIP($ip){
		if(isset($this->blockedIP[$ip])){
			call::register('ip',$ip);
			if(time() >= $this->blockedIP[$ip] || !call::method('application\\router','checkIP')){
				$this->unblockIP($ip);
				call::clear();
				return true;
			}
			call::clear();
			return false;
		}
		return true;
	}

	/**
	 * Block an IP address
	 * @param     $ip
	 * @param int $expire
	 *
	 * @return void
	 */
	protected function blockIP($ip,$expire = 1800){
		$this->blockedIP[$ip] = time()+$expire;//Unblock user automatically after 30 minutes (1800s)
		/**
		 * Close all of connections with specific ip
		 */
		$count  = count(static::$users);
		$keys   = array_keys(static::$users);
		for($i  = 0;$i < $count;$i++){
			if($this->getUserIP(static::$users[$keys[$i]])['address'] == $ip){
				socket_close(static::$users[$keys[$i]]);
			}
		}
	}

	/**
	 * Block a user
	 * @param     $user
	 * @param int $expire
	 *
	 * @return void
	 */
	protected function blockUser($user,$expire = 1800){
		$ip = $this->getUserIP($user)['address'];
		$this->blockedIP[$ip] = time()+$expire;//Unblock user automatically after 30 minutes (1800s)
		socket_close($user);
	}

	/**
	 * Unblock an IP
	 * @param $ip
	 *
	 * @return void
	 */
	protected function unblockIP($ip){
		unset($this->blockedIP[$ip]);
	}
}