<?php
/*
PHP Picture Index 1.0.1
--------------------------
Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://code.google.com/p/phppi/
Licence: GNU General Public License v3                   		 

This file is part of PHP Picture Index (PHPPI).

PHPPI is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

PHPPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with PHPPI. If not, see <http://www.gnu.org/licenses/>.
*/

require('phppi/includes/classes/phppi.php');

$phppi = new PHPPI;

$phppi->vars['version'] = '1.0.1';

$phppi->startTimer();

$phppi->loadSettings();
$phppi->initialize();
?>