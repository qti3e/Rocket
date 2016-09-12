<?php
/**
 * This is the index file you need to run this file via command line by typing this command:
 * php index.php
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 * @author  QTIƎE <Qti3eQti3e@Gmail.com>
 */

include "core/rocket/rocket.php";
$rocket = new \core\rocket\rocket($argv);
$rocket->run();