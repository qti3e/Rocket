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

namespace core\console;

/**
 * Class help
 * Simple helper class for making more beautiful help pages.
 * @package core\console
 */
class help{
	/**
	 * @var string
	 */
	protected $title    =   '';
	/**
	 * @var string
	 */
	protected $des      =   '';
	/**
	 * @var string
	 */
	protected $usage    =   '';
	/**
	 * @var array
	 */
	protected $switches =   [];
	/**
	 * @var array
	 */
	protected $flags    = [];

	/**
	 * Set page title
	 * @param $name
	 *
	 * @return $this
	 */
	public function title($name){
		$this->title    = $name;
		return $this;
	}

	/**
	 * Set a short description for help page
	 * @param $des
	 *
	 * @return $this
	 */
	public function description($des){
		$this->des      = $des;
		return $this;
	}

	/**
	 * Set a one line usage description like:
	 *  command -a "value" -b
	 * @param $usage
	 *
	 * @return $this
	 */
	public function usage($usage){
		$this->usage    = $usage;
		return $this;
	}

	/**
	 * Add a switch with description
	 * @param $name
	 * @param $description
	 *
	 * @return $this
	 */
	public function addSwitch($name,$description){
		$this->switches[strtolower($name)]  = $description;
		return $this;
	}

	/**
	 * Remove a switch
	 * @param $name
	 *
	 * @return bool|string
	 *  Returns false if switch does not exists.
	 *  Returns last description of switch
	 */
	public function rmSwitch($name){
		$name   = strtolower($name);
		if(isset($this->switches[$name])){
			$last   =   $this->switches[$name];
			unset($this->switches[$name]);
			return $last;
		}
		return false;
	}

	/**
	 * Add descriptions for a flag
	 * @param $name
	 * @param $description
	 *
	 * @return $this
	 */
	public function addFlag($name,$description){
		$this->flags[strtolower($name)] = $description;
		return $this;
	}

	/**
	 * Remove a flag
	 * @param $name
	 *
	 * @return bool
	 */
	public function rmFlag($name){
		$name   = strtolower($name);
		if(isset($this->flags[$name])){
			$last   =   $this->flags[$name];
			unset($this->flags[$name]);
			return $last;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$return = "\t".trim($this->title)."\n";
		$return .= "Usage:\n\t";
		if(empty(trim($this->usage))){
			$return .= "No usage entered.\n";
		}else{
			$return .= trim($this->usage)."\n";
		}
		$return .= "Des.:\n\t";
		if(empty($this->des)){
			$return .= "No description entered\n";
		}else{
			$return .= trim($this->des)."\n";
		}
		$return .= "Switches:\n";
		if(empty($this->switches)){
			$return .= "\tThis command doesn't have any switch\n";
		}else{
			//--
			$keys   = array_keys($this->switches);
			$count  = count($this->switches);
			for($i  = 0;$i < $count;$i++){
			    $key= $keys[$i];
			    $val= $this->switches[$key];
			    $return .=  "--".trim($key).":\n\t".trim($val)."\n";
			}
		}
		$return .= "Flags:\n";
		if(empty($this->flags)){
			$return .= "\tThis command doesn't have any flag\n";
		}else{
			//-
			$keys   = array_keys($this->flags);
			$count  = count($this->flags);
			for($i  = 0;$i < $count;$i++){
				$key= $keys[$i];
				$val= $this->flags[$key];
				$return .=  "-".trim($key).":\n\t".trim($val)."\n";
			}
		}
		$return .= "End of documentation";
		return $return;
	}

	/**
	 * @return string
	 */
	public function string(){
		return $this->__toString();
	}

	/**
	 * Make output in a html page format
	 * @return string
	 */
	public function html(){
		$return =   "<!DOCTYPE html>
<html>
<head>
	<title>Manual for ".trim($this->title)."</title>
</head>
<body>
	<h1>".trim($this->title)."</h1>
	<hr>
	<h3>Usage:</h3><p>";
		if(empty(trim($this->usage))){
			$return .= "No usage entered.</p><br>";
		}else{
			$return .= trim($this->usage)."</p><br>";
		}
		$return .= "<h3>Switches:</h3>";
		if(empty($this->switches)){
			$return .= "<p>This command doesn't have any switch.</p>";
		}else{
			//--
			$keys   = array_keys($this->switches);
			$count  = count($this->switches);
			for($i  = 0;$i < $count;$i++){
				$key= $keys[$i];
				$val= $this->switches[$key];
				$return .=  "--<b>".trim($key)."</b>:<br>&#9;<p>".trim($val)."</p><br>";
			}
		}
		$return .= "<h3>Flags:</h3>";
		if(empty($this->flags)){
			$return .= "<p>This command doesn't have any flag.</p>";
		}else{
			//-
			$keys   = array_keys($this->flags);
			$count  = count($this->flags);
			for($i  = 0;$i < $count;$i++){
				$key= $keys[$i];
				$val= $this->flags[$key];
				$return .=  "-<b>".trim($key)."</b>:<br>&#9;<p>".trim($val)."</p><br>";
			}
		}
		$return .= "</body>
</html>";
		return $return;
	}
}