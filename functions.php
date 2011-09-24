<?php
/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | functions.php                                                              |
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


// connects to and selects the database
function dbConnect($host, $username, $password, $database) {
  // connect to the database provider
  mysql_connect($host, $username, $password) or die("Cannot connect to the database.<br>" . mysql_error());
  // select the database
  mysql_select_db($database) or die("Cannot select the database.<br>" . mysql_error());
}


// determines what is the next colour in the heatmap sequence
function nxtColour($col){
  // make all our colours global variables so we have access to them from within the function
  global $c_0,$c_1,$c_2,$c_3,$c_4,$c_5,$c_6,$c_7,$c_8,$c_9,$c_10,$c_11,$c_12,$c_13,$c_14,$c_15,$c_16,$c_17,$c_18,$c_19,$c_20;
  // create a loop that will run the same amount of times as the number of colours 
  // in the heatmap sequence minus one - as we aim to return the next colour, which could be the last one
  for($i=0; $i<=19; $i++) {
    // construct the variable name of the current colour in the sequence
    $this = "c_" . $i;
    // see if this colour passed into the function is the same as the one we have got to in the sequence
    if($$this == $col) {
      // construct the variable name of the next colour in the sequence
      $nextColour = "c_" . ++$i;
      // return it
      return $$nextColour;
    }
  }
  // return the first colour in the sequence
  return $c_0;
}


function listFiles($directory){
  $arrFiles = array();
  // list the names of all the heatmaps we have previously generated
  if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        $arrFiles[] = $file;
      }
    }
    closedir($handle);
  } else {
    die('Could not open directory: '.$directory);
  }
  return $arrFiles;
}

function listPaths() {
  global $db;

  $sql = "SELECT DISTINCT path FROM ctracker ORDER BY path ASC";

  $result = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
  // check if the query was valid
  if (!$result) {
    // kill the script
    die('Invalid query: ' . mysql_error());
  } else {
    $arrPaths = array();
    // loop through the results
    while($row = mysql_fetch_array($result)) {
      $arrPaths[] = $row['path'];
    }
    return $arrPaths;
  }


}

?>