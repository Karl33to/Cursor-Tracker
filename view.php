<?php
/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | view.php                                                                   |
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
$title = 'View Heatmaps';

// include the header
include('header.php');

// connect to the database  
dbConnect($host, $username, $password, $database);

$backgroundUrl = $domain.$_POST['path'];
$overlayImage = $_POST['overlayImage'];

?>

    <div class="section">
      <div class="title">View</div>  

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    
        <b>Background URL</b> 
        <?php
        echo $domain;
        ?><select name="path">
        <?php
        $arrPaths = listPaths();
        foreach($arrPaths as $path){
          echo '<option value="'.$path.'">'.$path.'</option>';
        }
        ?>
        </select><br />
    
        <b>Heatmap</b> <select name="overlayImage">
        <?php
        $arrHeatmaps = listFiles($outputDirectory);
        foreach($arrHeatmaps as $heatmap){
          echo '<option value="'.$heatmap.'">'.$heatmap.'</option>';
        }
        ?>
        </select><br />
    
        <input type="submit" name="submit" value="submit" />
    
      </form>

    </div>


    <div class="section">
      <div class="title">Window Display</div>  

        <a href="javascript: void(0);" onclick="changeClass('page', 'hide'); changeClass('restore', '');">hide this window</a>

    </div>

<?php

// include the footer
include('footer.php');
?>
<div style="position: absolute; top: 0; left: 0; z-index: 2;"><img src="output/<?php echo $overlayImage; ?>" /></div>
<iframe src="<?php echo $backgroundUrl; ?>" width="100%" height="100%" style="position: absolute; top: 0; left: 0; z-index: 1;"></iframe>
<div id="restore" class="hide"><a href="javascript: void(0);" onclick="changeClass('page', 'above'); changeClass('restore', 'hide');">restore</a></div>
