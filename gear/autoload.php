<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

require 'Core/Autoloader.php';

use \Gear\Core\Autoloader;

$autoloader = new Autoloader('Gear\\', __DIR__);
$autoloader->register();
