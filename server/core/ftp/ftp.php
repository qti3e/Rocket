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

namespace core\ftp;

/**
 * Class ftp
 * @package core\ftp
 */
class ftp {
	/**
	 * @var bool|resource
	 */
	protected $connection;
	/**
	 * @var null
	 */
	protected $username;
	/**
	 * @var null
	 */
	protected $password;
	/**
	 * @var bool
	 */
	protected $is_connect   = true;
	/**
	 * @var bool
	 */
	protected $is_login     = false;

	/**
	 * ftp constructor.
	 *
	 * @param            $server
	 * @param int        $port
	 * @param int        $timeout
	 * @param bool|false $ssl
	 */
	public function __construct($server,$port = 21,$timeout = 90,$ssl = false) {
		if($ssl){
			$this->connection   = ftp_ssl_connect($server,$port,$timeout);
		}else{
			$this->connection   = ftp_connect($server,$port,$timeout);
		}
		if(!$this->connection){
			$this->is_connect   = false;
		}
	}

	/**
	 * @return bool
	 */
	public function isConnect() {
		return $this->is_connect;
	}

	/**
	 * @return bool
	 */
	public function isLogin() {
		return $this->is_login;
	}

	/**
	 * @return bool|resource
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 * @param $username
	 *
	 * @return void
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @param $password
	 *
	 * @return void
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @param null $username
	 * @param null $password
	 *
	 * @return bool
	 */
	public function login($username = null,$password = null){
		if($username === null){
			$username   = $this->username;
		}
		if($password === null){
			$password   = $this->password;
		}
		$this->username = $username;
		$this->password = $password;
		if(@ftp_login($this->connection,$username,$password)){
			$this->is_login = true;
			return true;
		}
		$this->is_login = false;
		return false;
	}

	/**
	 * @return string
	 */
	public function where(){
		return ftp_pwd($this->connection);
	}

	/**
	 * @return string
	 */
	public function pwd(){
		return ftp_pwd($this->connection);
	}

	/**
	 * @return bool|string
	 */
	public function sysType(){
		if($type = ftp_systype($this->connection)){
			return $type;
		}
		return false;
	}

	/**
	 * @param $option
	 * @param $value
	 *
	 * @return bool
	 */
	public function set_option($option,$value){
		return ftp_set_option($this->connection,$option,$value);
	}

	/**
	 * @param $option
	 *
	 * @return mixed
	 */
	public function get_option($option){
		return ftp_get_option($this->connection,$option);
	}

	/**
	 * @param      $option
	 * @param null $value
	 *
	 * @return bool|mixed
	 */
	public function option($option,$value = null){
		if($value === null){
			return $this->get_option($option);
		}
		return $this->set_option($option,$value);
	}

	/**
	 * @param $option
	 *
	 * @return mixed
	 *
	 */
	public function __get($option) {
		return $this->get_option($option);
	}

	/**
	 * @param $option
	 * @param $value
	 *
	 * @return mixed
	 */
	public function __set($option, $value) {
		$this->set_option($option,$value);
		return $value;
	}

	/**
	 * @param $command
	 *
	 * @return bool
	 */
	public function site($command){
		if($re = ftp_site($this->connection,$command)){
			return $re;
		}
		return false;
	}

	/**
	 * @param $command
	 *
	 * @return bool
	 */
	public function exec($command){
		if($re = ftp_exec($this->connection,$command)){
			return $re;
		}
		return false;
	}

	/**
	 * @param $mode
	 * @param $file
	 *
	 * @return array|bool
	 */
	public function chmod($mode,$file){
		if(is_array($file)){
			$keys   = array_keys($file);
			$count  = count($file);
			$re     = [];
			for($i  = 0;$i < $count;$i++){
				$re[$keys[$i]]  = $this->chmod($mode,$file[$keys[$i]]);
			}
			return $re;
		}
		return (ftp_chmod($this->connection,$mode,$file) !== false);
	}

	/**
	 * @return bool
	 */
	public function cdup(){
		if(ftp_cdup($this->connection)){
			return true;
		}
		return false;
	}

	/**
	 * @param $dir
	 *
	 * @return bool
	 */
	public function chdir($dir){
		if(ftp_chdir($this->connection,$dir)){
			return true;
		}
		return false;
	}

	/**
	 * @return void
	 */
	public function close(){
		ftp_close($this->connection);
		$this->is_login     = false;
		$this->is_connect   = false;
	}

	/**
	 * @param $file
	 *
	 * @return array|bool
	 */
	public function delete($file){
		if(is_array($file)){
			$keys   = array_keys($file);
			$count  = count($file);
			$re     = [];
			for($i  = 0;$i < $count;$i++){
				$re[$keys[$i]]  = $this->delete($file[$file[$keys[$i]]]);
			}
			return $re;
		}
		return (ftp_delete($this->connection,$file) !== false);
	}

	/**
	 * @param $handler
	 * @param $remoteFile
	 *
	 * @return bool
	 */
	public function fget($handler,$remoteFile){
		return ftp_fget($this->connection,$handler,$remoteFile,FTP_ASCII,0);
	}

	/**
	 * @param $handler
	 * @param $remoteFile
	 *
	 * @return bool
	 */
	public function fput($handler,$remoteFile){
		return ftp_fput($this->connection,$handler,$remoteFile,FTP_ASCII,0);
	}

	/**
	 * @param $local_file
	 * @param $remote_file
	 *
	 * @return bool
	 */
	public function get($local_file,$remote_file){
		return ftp_get($this->connection,$local_file,$remote_file,FTP_BINARY);
	}

	/**
	 * @param $remote_file
	 * @param $local_file
	 *
	 * @return bool|int
	 */
	public function put($remote_file,$local_file){
		if(ftp_alloc($this->connection,filesize($local_file),$result)){
			return ftp_put($this->connection,$remote_file,$local_file,FTP_BINARY);
		}else{
			return -1;
		}
	}

	/**
	 * @param $file
	 *
	 * @return array|bool|int
	 */
	public function mdtm($file){
		if(is_array($file)){
			$keys   = array_keys($file);
			$count  = count($file);
			$re     = [];
			for($i  = 0;$i < $count;$i++){
				$re[$keys[$i]]  = $this->mdtm($file[$file[$keys[$i]]]);
			}
			return $re;
		}
		$tm = ftp_mdtm($this->connection,$file);
		return ($tm == -1) ? false : $tm;
	}

	/**
	 * @param $dir
	 *
	 * @return bool
	 */
	public function mkdir($dir){
		if(ftp_mkdir($this->connection,$dir)){
			return true;
		}
		return false;
	}

	/**
	 * @param string $directory
	 *
	 * @return array
	 */
	public function nlist($directory = '.'){
		return ftp_nlist($this->connection,$directory);
	}

	/**
	 * @param $pasv
	 *
	 * @return bool
	 */
	public function pasv($pasv){
		return ftp_pasv($this->connection,$pasv);
	}

	/**
	 * @param $command
	 *
	 * @return array
	 */
	public function raw($command){
		return ftp_raw($this->connection,$command);
	}

	/**
	 * @param string $dir
	 *
	 * @return array
	 */
	public function rawlist($dir = '.'){
		return ftp_rawlist($this->connection,$dir);
	}

	/**
	 * @param $old_name
	 * @param $new_name
	 *
	 * @return bool
	 */
	public function rename($old_name,$new_name){
		if(ftp_rename($this->connection,$old_name,$new_name)){
			return true;
		}
		return false;
	}

	/**
	 * @param $dir
	 *
	 * @return array|bool
	 */
	public function rmdir($dir){
		if(is_array($dir)){
			$keys   = array_keys($dir);
			$count  = count($dir);
			$re     = [];
			for($i  = 0;$i < $count;$i++){
				$re[$keys[$i]]  = $this->rmdir($dir[$keys[$i]]);
			}
			return $re;
		}
		return (bool)(ftp_rmdir($this->connection,$dir));
	}

	/**
	 * @param $file
	 *
	 * @return array|bool
	 */
	public function size($file){
		if(is_array($file)){
			$keys   = array_keys($file);
			$count  = count($file);
			$re     = [];
			for($i  = 0;$i < $count;$i++){
				$re[$keys[$i]]  = $this->rmdir($file[$keys[$i]]);
			}
			return $re;
		}
		return (bool)(ftp_size($this->connection,$file));
	}
}
//Finished!