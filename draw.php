<?php
/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | draw.php                                                                   |
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

// include the settings file
require('config.php');
require('functions.php');

// some settings relevant to only this file
$title = 'Analyse Data';

// include the header
include('header.php');

// connect to the database  
dbConnect($host, $username, $password, $database);


// check if the form has been submitted
if($_POST['submit']) {

  // decide whether we need to create a new heatmap or modify an existing one
  if($_POST['choice1'] === 'new') {

    // check the save-as name is valid
    if(!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['saveas'])){
      die('Error: save-as filename not vlaid');
    }

    // set the image to be loaded to the blank image
    $loadImagePath = 'trans.png';
    $saveImageName = $_POST['saveas'].'.png';

  } elseif ($_POST['choice1'] === 'existing') {

    // check the file actually exists
    if(!file_exists($outputDirectory.$_POST['existingname'])){
      echo $outputDirectory.$_POST['existingname'];
      die('Error: current file does not exist');
    }

    // set the image to be loaded to the existing image
    $loadImagePath = $outputDirectory.$_POST['existingname'];
    $saveImageName = $_POST['existingname'];

  } else {

    die('Error: new/existing choice not selected');

  }

  
  
  // load the image to which we will add our positional dots
  $im = imagecreatefrompng($loadImagePath);
  
  // preserve the alpha transparency
  imagesavealpha ($im, true);
  
  // allocate a load of colours, these colours were picked from the heatmap.bmp image and range from blue to red
  // they are used to indicate the number of times a certain area has been visited, red being the most popular
  $c_0 = imagecolorallocate($im, 183, 231, 248);
  $c_1 = imagecolorallocate($im, 111, 207, 241);
  $c_2 = imagecolorallocate($im, 118, 207, 224);
  $c_3 = imagecolorallocate($im, 127, 207, 202);
  $c_4 = imagecolorallocate($im, 137, 207, 177);
  $c_5 = imagecolorallocate($im, 148, 208, 153);
  $c_6 = imagecolorallocate($im, 159, 209, 132);
  $c_7 = imagecolorallocate($im, 170, 211, 116);
  $c_8 = imagecolorallocate($im, 183, 217, 108);
  $c_9 = imagecolorallocate($im, 197, 227, 105);
  $c_10 = imagecolorallocate($im, 212, 239, 107);
  $c_11 = imagecolorallocate($im, 226, 244, 109);
  $c_12 = imagecolorallocate($im, 240, 244, 110);
  $c_13 = imagecolorallocate($im, 250, 244, 108);
  $c_14 = imagecolorallocate($im, 255, 237, 100);
  $c_15 = imagecolorallocate($im, 255, 208, 86);
  $c_16 = imagecolorallocate($im, 255, 168, 70);
  $c_17 = imagecolorallocate($im, 255, 123, 51);
  $c_18 = imagecolorallocate($im, 255, 78, 32);
  $c_19 = imagecolorallocate($im, 255, 38, 16);
  $c_20 = imagecolorallocate($im, 255, 8, 3);
  
  // validate result size
  $arrAllowedSizes = array('pixel', '2square', '4square', '6circle', '8circle', '10circle');
  if(!in_array($_POST['resultsize'], $arrAllowedSizes)){
    die('invalid result size');
  }  

  // build a query to get some data from the database

  // path
  if($_POST['path']) {
    if(!preg_match('/([a-z0-9\/_\.])/i', $_POST['path'])){
      die('invalid path format: '.$_POST['path']);
    }  
    $arrWhere[] = 'path = \''.$_POST['path'].'\'';
  }
  
  // resolution
  if($_POST['resolution']) {
    if(!preg_match('/([0-9]{3,4})x([0-9]{3,4})/', $_POST['resolution'])){
      die('invalid resolution format: '.$_POST['resolution']);
    }
    $arrWhere[] = 'resolution = \''.$_POST['resolution'].'\'';
  }
  
  // include and instantiate the validation class
  require_once('class_validate.php');
  $validator = new validate;
  
  // date from
  if($_POST['datefrom']) {
    // validate the date format
    if(!$validator->valDate($_POST['datefrom'], 'dd/mm/yy')){
      die('invalid date from format');
    }
    $dateMatches = $validator->fetchMatches();
    // re-build the date in sql format
    $arrWhere[] = 'date >= \''.$dateMatches[3].'-'.$dateMatches[2].'-'.$dateMatches[1].'\'';
  }
  
  // date to
  if($_POST['dateto']) {
    // validate the date format
    if(!$validator->valDate($_POST['dateto'], 'dd/mm/yy')){
      die('invalid date to format');
    }
    $dateMatches = $validator->fetchMatches();
    // re-build the date in sql format
    $arrWhere[] = 'date <= \''.$dateMatches[3].'-'.$dateMatches[2].'-'.$dateMatches[1].'\'';
  }
  
  // user id
  if($_POST['userid']) {
    if(!preg_match('/([0-9])/', $_POST['userid'])){
      die('invalid userid format');
    }  
    $arrWhere[] = 'userid = '.$_POST['userid'];
  }

  $where = '';
  if(count($arrWhere)){
    // compile all the where's into some nice tidy sql
    $where .= 'WHERE ';
    foreach($arrWhere as $clause) {
      $where .= $clause.' AND ';
    }
    $where = substr($where, 0, strlen($where)-5);
  }


  // add the where clause(es) to the query string
  $sql = "SELECT * FROM ctracker ".$where;

  // run the query
  $result = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
  // check if the query was valid
  if (!$result) {
    // kill the script
    die('Invalid query: ' . mysql_error());
  } else {
    // loop through the results
    while($row = mysql_fetch_array($result)) {
      // split the string of positions down into an array of sets of X and Y coordinates
      $arrCoords = explode(":", $row["positions"]);
      // loop through all of the sets of positon
      foreach($arrCoords as $value) {
        // split this set of positions down into its X and Y components
        list($xpos, $ypos) = explode("-", $value);
        // find the current colour of the overlay image at those co-ordinates
        $rgb = imageColorAt($im, $xpos, $ypos);
        // run the function to get the next colour in the heatmap sequence
        $this_colour = nxtColour($rgb);

        // decide what size of result dot to display
        switch($_POST['resultsize']){
          case 'pixel':
            imagesetpixel ($im, $xpos, $ypos, $this_colour);
            break;
          case '2square':
            imagefilledrectangle ($im, $xpos-1, $ypos-1, $xpos+1, $ypos+1, $this_colour);
            break;
          case '4square':
            imagefilledrectangle ($im, $xpos-2, $ypos-2, $xpos+2, $ypos+2, $this_colour);
            break;
          case '6circle':
            imagefilledellipse($im, $xpos, $ypos, 6, 6, $this_colour);
            break;
          case '8circle':
            imagefilledellipse($im, $xpos, $ypos, 8, 8, $this_colour);
            break;
          case '10circle':
            imagefilledellipse($im, $xpos, $ypos, 10, 10, $this_colour);
            break;
        }

      }
    }
  }
  
  // output the image to a file
  if(imagepng($im, $outputDirectory.$saveImageName)) {
    // success message
    echo '<div class="message">';
    echo 'Heatmap created successfully.<br />';
    echo '<form action="view.php" method="post">';
    echo '<input type="hidden" name="overlayImage" value="'.$saveImageName.'" />';
    echo '<input type="submit" name="submit" value="Click here to view it" />';
    echo '</form>';
    echo '</div>';
  } else {
    // something went wrong ! don't blame me :)
    echo '<div class="error">';
    echo 'Problem creating heatmap image.';
    echo '</div>';
  }
  
  // delete the image from memory
  imagedestroy($im);


  // purge the results if needed
  if($_POST['purge'] == 'Yes'){
    $sql = "DELETE FROM ctracker ".$where;

    $result = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
    // check if the query was valid
    if (!$result) {
      // kill the script
      die('Invalid query: ' . mysql_error());
    } else {
      if(mysql_affected_rows()){
        echo '<p>Records have been purged from database.<p>';
      } else {
        echo 'Purged failed.';
      }
    }

  }
  
}

?>
  
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="section">
      <div class="title">Input</div>  
  
      <p>
        Select a resolution
          <select name="resolution">
            <option value="">All</option>
            <?php
            // query to find all the different screen resolutions
            $sql_res = "SELECT COUNT(resolution) AS count, resolution FROM ctracker GROUP BY resolution ORDER BY count DESC";
            // run the query
            $result_res = mysql_query($sql_res) or die("Cannot query the database.<br>" . mysql_error());
            // check if the query was valid
            if (!$result_res) {
              // kill the script
              die('Invalid query: ' . mysql_error());
            } else {
              // loop through the results
              while($row_res = mysql_fetch_array($result_res)) {
                echo '<option value="'.$row_res['resolution'].'">'.$row_res['resolution'].'</option>';
              }
            }
            ?>
          </select>
      </p>
    
      <p>
        Analyse the following user ID <br />
          <select name="userid">
            <option value="">All</option>
            <?php
            // query to find all the different user id's
            $sql_usr = "SELECT DISTINCT userid FROM ctracker ORDER BY userid ASC";
            // run the query
            $result_usr = mysql_query($sql_usr) or die("Cannot query the database.<br>" . mysql_error());
            // check if the query was valid
            if (!$result_usr) {
              // kill the script
              die('Invalid query: ' . mysql_error());
            } else {
              // loop through the results
              while($row_usr = mysql_fetch_array($result_usr)) {
                echo '<option value="'.$row_usr['userid'].'">'.$row_usr['userid'].'</option>';
              }
            }
            ?>
          </select>
      </p>
    
      <p>
        Analyse the following pages <br />
        <?php
        echo $domain;
        ?><select name="path">
        <option value="">All</option>
        <?php
        $arrPaths = listPaths();
        foreach($arrPaths as $path){
          echo '<option value="'.$path.'">'.$path.'</option>';
        }
        ?>
        </select>
      </p>
    
      <p>
        Set a date range <br />
        <input type="text" name="datefrom" /> Date from <br />
        <input type="text" name="dateto" /> Date to <br />
        date format (dd/mm/yyyy)
      </p>
  
      <p>
        Show results as:
          <select name="resultsize">
            <option value="pixel">Pixel</option>
            <option value="2square">2 x 2 pixel square</option>
            <option value="4square">4 x 4 pixel square</option>
            <option value="6circle">6 pixel circle</option>
            <option value="8circle">8 pixel circle</option>
            <option value="10circle">10 pixel circle</option>
          </select>
      </p>
    </div>

    <div class="section">
      <div class="title">Output</div>  
  
      <p>
        <input type="radio" name="choice1" value="new" /> <b>Create a new heatmap</b>
      </p>
      <p>
        Save output as <input type="text" name="saveas" />.png<br />
        (Note: filename must be made up from letters, numbers and underscores only)<br />
        e.g. 1024x768_Feb_2007_allpages
      </p>
    
      Or
    
      <p>
        <input type="radio" name="choice1" value="existing" /> <b>Add results to an existing heatmap</b>
      </p>
      <p>
        Existing image name: 
        <select name="existingname">
        <?php
        $arrHeatmaps = listFiles($outputDirectory);
        foreach($arrHeatmaps as $heatmap){
          echo '<option value="'.$heatmap.'">'.$heatmap.'</option>';
        }
        ?>
        </select>
      </p>
    </div>



    <div class="section">
      <div class="title">Tidy Up</div>  
       <p>
        Purge these results from the database once complete:
          <select name="purge">
            <option value="Yes">Yes</option>
            <option value="No" selected>No</option>
          </select>
      </p>
    </div>

    <div class="section">
      <div class="title">Process</div>    
      <p>
        <input type="submit" name="submit" value="submit" />
      </p>
    </div>
  
  </form>

<?php

// include the footer
include('footer.php');
?>

