<?php
/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | class_validate.php - version 0.0.1 - 03/12/2007                            |
 | Copyright 2007 Karl Payne                                                  |
 |____________________________________________________________________________|
  _________
 | LICENSE |__________________________________________________________________
 |                                                                            |
 | This program is free software; you can redistribute it and/or modify       |
 | it under the terms of the GNU General Public License as published by       |
 | the Free Software Foundation; either version 3 of the License, or          |
 | (at your option) any later version.                                        |
 |                                                                            |
 | This program is distributed in the hope that it will be useful,            |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of             |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              |
 | GNU General Public License for more details.                               |
 |                                                                            |
 | You should have received a copy of the GNU General Public License          |
 | along with this program.  If not, see <http://www.gnu.org/licenses/>       |
 |____________________________________________________________________________|

*/


class validate {
  
  var $arrMatches = array();

  // constructor
  function validate() {
  }

  function fetchMatches() {
    // copy the var, so that it can be reset before the function returns
    $retVal = $this->arrMatches;
    // empty the matches variable
    $this->arrMatches = array();
    // return the copy
    return $retVal;
  }

  // email address's
  function valEmail($strEmail){
    if (preg_match("/([a-z0-9][a-z0-9_-]*(\.[a-z0-9_-]+)*)@([a-z0-9][a-z0-9\._-]+)(\.[a-z]{2,4})/i", $strEmail, $this->arrMatches)) {
      return true;
    } else {
      return false;
    }
  }

  // UK postcodes
  function valUKPostcode($strPostcode){
    if (preg_match('/[a-z]{1,2}[0-9]{1,2}\s[0-9][a-z]{2,}/i', $strPostcode, $this->arrMatches)) {
      return true;
    } else {
      return false;
    }
  }

  // telephone and fax numbers
  function valTelFax($strTelephone){
    // matches UK and international tel and fax numbers
    if (preg_match('/[0-9-+\(\)\s]+/', $strTelephone, $this->arrMatches)) {
      return true;
    } else {
      return false;
    }
  }

  // dates
  function valDate($strDate, $strFormat = 'dd/mm/yy'){

    switch($strFormat) {
      // uk format
      case 'dd/mm/yy';
      case 'dd/mm/yyyy':
        if(preg_match('/([0-3][0-9])\/([0|1][0-9])\/((20)?[0-9]{2,2})/', $_POST['datefrom'], $this->arrMatches)){
          return true;
        } else {
          return false;
        }
        break;
      // stupid american format
      case 'mm/dd/yy':
      case 'mm/dd/yyyy':
        if(preg_match('/([0|1][0-9])\/([0-3][0-9])\/((20)?[0-9]{2,2})/', $_POST['datefrom'], $this->arrMatches)){
          return true;
        } else {
          return false;
        }
        break;
      // sorting format
      case 'yy/mm/dd':
      case 'yyyy/mm/dd':
        if(preg_match('/(20[0-9]{2,2})\/([0|1][0-9])\/([0-3][0-9])/', $_POST['datefrom'], $this->arrMatches)){
          return true;
        } else {
          return false;
        }
        break;
    }

     return false;

  }

  function valTime($strTime) {
    // 18:35
    if (preg_match("/([0-1][0-9]|[2][0-3]):([0-5][0-9])/", $strTime, $this->arrMatches)) {
      return true;
    } else {
      return false;
    }
  }

}

?>