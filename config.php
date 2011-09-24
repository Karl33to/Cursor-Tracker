<?php
/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | config.php                                                                 |
 | part of Cursor Tracker - version 1.5.1 - 01/02/2008                        |
 | Copyright 2007 Karl Payne                                                  |
 |____________________________________________________________________________|
  _________
 | LICENSE |__________________________________________________________________
 |                                                                            |
 | Cursor Tracker is free software; you can redistribute it and/or modify     |
 | it under the terms of the GNU General Public License as published by       |
 | the Free Software Foundation; either version 3 of the License, or          |
 | (at your option) any later version.                                        |
 |                                                                            |
 | Cursor Tracker is distributed in the hope that it will be useful,          |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of             |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              |
 | GNU General Public License for more details.                               |
 |                                                                            |
 | You should have received a copy of the GNU General Public License          |
 | along with Cursor Tracker.  If not, see <http://www.gnu.org/licenses/>     |
 |____________________________________________________________________________|

*/

// database structure
/*
CREATE TABLE `ctracker` (
  `id` int(6) NOT NULL auto_increment,
  `userId` int(6) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `resolution` varchar(15) NOT NULL default '',
  `path` varchar(80) NOT NULL default '',
  `positions` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `resolution` (`resolution`)
);
*/

// setup the database connection variables
$username 	= "";
$password 	= "";
$host 		= "localhost";
$database 	= "";

// the location which the heatmap images will get saved to
// relative to the draw.php file
$outputDirectory = 'output/';

// domain name of the site - used as the base url when viewing the overlayed heatmaps
$domain = 'http://www.karlpayne.co.uk';

$version = '1.5.1';
?>
