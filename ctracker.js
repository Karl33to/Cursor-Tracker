/*
  _________
 |  INFO   |__________________________________________________________________
 |                                                                            |
 | ctracker.js                                                                |
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

var iMoveInterval = 8; // track every Xth position
var iSendInterval = 15; // sends coordinates in groups of X (must be less than 25 - database field size)
var iMoveCounter = 0;
var iSendCounter = 0;
var sPositionData = '';
var iIdentifyer = 0;
var sResolution = '';
var sPath = '';

function sendData(sData) {
	if (document.images) {
		if(typeof(pic1) == "undefined") {
			pic1 = new Image(10,10); 
		}
		pic1.src = "http://www.cherrystudios.co.uk/ctracker/ctracker.gif?id=" + iIdentifyer + "&res=" + sResolution + "&path=" + sPath + "&data=" + sData;
	}
}

function checkInterval() {
	if (iMoveCounter == iMoveInterval) {
		savePosition();
		iMoveCounter = 0;
	} else {
		iMoveCounter++;
	}
}

function savePosition() {	
	var iPosX = 0;
	var iPosY = 0;
	if(window.event) {
		// internet explorer
		var oEvent =  window.event;
		iPosX = oEvent.clientX + document.body.scrollLeft;
		iPosY = oEvent.clientY + document.body.scrollTop;
	} else {
		// dom compliant
		var oEvent = savePosition.caller.arguments[0];
		iPosX = oEvent.pageX;
		iPosY = oEvent.pageY;
	}
	sPositionData += iPosX + '-' + iPosY + ':';
	if (iSendCounter == iSendInterval) {
		sendData(sPositionData);
		sPositionData = '';
		iSendCounter = 0;
	} else {
		iSendCounter++;
	}
}

function init() {
	document.onmousemove = checkInterval;
	sResolution = screen.width + 'x' + screen.height;
	sPath = escape(window.location.pathname);
}

window.onload = init;
