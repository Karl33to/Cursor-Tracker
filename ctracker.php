<?php
/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | ctracker.php                                                               |
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

// this file needs to be set up along with some URL re-writing so that any request
// for the image ctracker.gif will get sent to this page

// include the settings file
require('config.php');

// set the header so that the browser thinks its an actual gif
header('Content-type: image/gif');

// connect to the database provider
mysql_connect($host,$username,$password) or die("Cannot connect to the database.<br>" . mysql_error());
// select the database
mysql_select_db($database) or die("Cannot select the database.<br>" . mysql_error());
// build a query to insert the data from the url vaiables
$sql = "INSERT INTO ctracker VALUES (NULL, ".$_GET['id'].",  NULL, '".$_GET['res']."', '".$_GET['path']."', '".$_GET['data']."')";
// run the query
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
?>