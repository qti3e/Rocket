<?php
/**
 * Very simple template engine for roc files (just replace config values)
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

namespace core\roc;

use core\cache\cache;
use core\config\config;
use core\helpers\helper;

/**
 * It's a simple parser for roc files
 *  Roc is a simple template engine that gives application's config to rocket pages.
 *  The syntax:
 *  1- Without default value
 *      {%option}
 * 2- With default value
 *      {%option | default value}
 * @Example Simple usage
 *      {%version | rocket} is now running on {%host}:{%port} on a {%os} system!
 * It'll parse to sth like:
 *      Rocket/0.2-beta is now running on 0.0.0.0:8085 on a Linux system!
 * @Example call an undefined config option
 *      {%not_defined_config | 'Hi!'}
 *  It'll parse to:
 *      Hi!
 * @package core\roc
 */
class roc {
	/**
	 * Generated by parsing Nginx's 'mime.types' file
	 * @var array
	 */
	protected static $mimesType    = [
		'roc'   => 'text/html',
		"html"	=> "text/html",
		"htm"	=> "text/html",
		"shtml"	=> "text/html",
		"css"	=> "text/css",
		"xml"	=> "text/xml",
		"gif"	=> "image/gif",
		"jpeg"	=> "image/jpeg",
		"jpg"	=> "image/jpeg",
		"js"	=> "application/javascript",
		"atom"	=> "application/atom+xml",
		"rss"	=> "application/rss+xml",
		"mml"	=> "text/mathml",
		"txt"	=> "text/plain",
		"jad"	=> "text/vnd.sun.j2me.app-descriptor",
		"wml"	=> "text/vnd.wap.wml",
		"htc"	=> "text/x-component",
		"png"	=> "image/png",
		"tif"	=> "image/tiff",
		"tiff"	=> "image/tiff",
		"wbmp"	=> "image/vnd.wap.wbmp",
		"ico"	=> "image/x-icon",
		"jng"	=> "image/x-jng",
		"bmp"	=> "image/x-ms-bmp",
		"svg"	=> "image/svg+xml",
		"svgz"	=> "image/svg+xml",
		"webp"	=> "image/webp",
		"woff"	=> "application/font-woff",
		"jar"	=> "application/java-archive",
		"war"	=> "application/java-archive",
		"ear"	=> "application/java-archive",
		"json"	=> "application/json",
		"hqx"	=> "application/mac-binhex40",
		"doc"	=> "application/msword",
		"pdf"	=> "application/pdf",
		"ps"	=> "application/postscript",
		"eps"	=> "application/postscript",
		"ai"	=> "application/postscript",
		"rtf"	=> "application/rtf",
		"m3u8"	=> "application/vnd.apple.mpegurl",
		"xls"	=> "application/vnd.ms-excel",
		"eot"	=> "application/vnd.ms-fontobject",
		"ppt"	=> "application/vnd.ms-powerpoint",
		"wmlc"	=> "application/vnd.wap.wmlc",
		"kml"	=> "application/vnd.google-earth.kml+xml",
		"kmz"	=> "application/vnd.google-earth.kmz",
		"7z"	=> "application/x-7z-compressed",
		"cco"	=> "application/x-cocoa",
		"jnlp"	=> "application/x-java-jnlp-file",
		"run"	=> "application/x-makeself",
		"pl"	=> "application/x-perl",
		"pm"	=> "application/x-perl",
		"prc"	=> "application/x-pilot",
		"pdb"	=> "application/x-pilot",
		"rar"	=> "application/x-rar-compressed",
		"rpm"	=> "application/x-redhat-package-manager",
		"sea"	=> "application/x-sea",
		"swf"	=> "application/x-shockwave-flash",
		"sit"	=> "application/x-stuffit",
		"tcl"	=> "application/x-tcl",
		"tk"	=> "application/x-tcl",
		"der"	=> "application/x-x509-ca-cert",
		"pem"	=> "application/x-x509-ca-cert",
		"crt"	=> "application/x-x509-ca-cert",
		"xpi"	=> "application/x-xpinstall",
		"xhtml"	=> "application/xhtml+xml",
		"xspf"	=> "application/xspf+xml",
		"zip"	=> "application/zip",
		"bin"	=> "application/octet-stream",
		"exe"	=> "application/octet-stream",
		"dll"	=> "application/octet-stream",
		"deb"	=> "application/octet-stream",
		"dmg"	=> "application/octet-stream",
		"iso"	=> "application/octet-stream",
		"img"	=> "application/octet-stream",
		"msi"	=> "application/octet-stream",
		"msp"	=> "application/octet-stream",
		"msm"	=> "application/octet-stream",
		"docx"	=> "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		"xlsx"	=> "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		"pptx"	=> "application/vnd.openxmlformats-officedocument.presentationml.presentation",
		"mid"	=> "audio/midi",
		"midi"	=> "audio/midi",
		"kar"	=> "audio/midi",
		"mp3"	=> "audio/mpeg",
		"ogg"	=> "audio/ogg",
		"m4a"	=> "audio/x-m4a",
		"ra"	=> "audio/x-realaudio",
		"3gpp"	=> "video/3gpp",
		"3gp"	=> "video/3gpp",
		"ts"	=> "video/mp2t",
		"mp4"	=> "video/mp4",
		"mpeg"	=> "video/mpeg",
		"mpg"	=> "video/mpeg",
		"mov"	=> "video/quicktime",
		"webm"	=> "video/webm",
		"flv"	=> "video/x-flv",
		"m4v"	=> "video/x-m4v",
		"mng"	=> "video/x-mng",
		"asx"	=> "video/x-ms-asf",
		"asf"	=> "video/x-ms-asf",
		"wmv"	=> "video/x-ms-wmv",
		"avi"	=> "video/x-msvideo",
		"jardiff"   => "application/x-java-archive-diff",
	];

	/**
	 * Parse and return file
	 * @param $filename
	 *
	 * @return mixed
	 */
	public static function getParsedFile($filename){
		$filename   = helper::url($filename);
		//Cache output for a day
		return cache::cache(24*3600,function() use ($filename){
			if(file_exists($filename)){
				$fp = fopen($filename,'r');
				$ex = pathinfo($filename,PATHINFO_EXTENSION);
				$re = '';
				while (!feof($fp)){
					$re .= fread($fp,1024);
				}
				$header = "Content-Type: ".self::getMimeType($ex)."\r\nContent-Length: ".strlen($re)."\r\n\r\n";
				if($ex == 'roc'){
					return $header.preg_replace_callback('/\{%(\w+)\s*?(\|\s*(.+?))?\}/',function($replace){
						var_dump($replace);
						$def = isset($replace[3]) ? $replace[3] : null;
						return config::get($replace[1],$def);
					},$re);
				}
				return $header.$re;
			}
			return false;
		},[$filename,file_exists($filename)]);
	}

	/**
	 * Convert file format to mime type
	 * @param $extension
	 *
	 * @return string
	 */
	public static function getMimeType($extension){
		if(isset(static::$mimesType[$extension = strtolower($extension)])){
			return static::$mimesType[$extension = strtolower($extension)];
		}
		return 'text/plain';
	}
}