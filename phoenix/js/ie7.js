// timestamp: Mon, 04 Feb 2008 17:10:24
/*
  IE7/IE8.js - copyright 2004-2008, Dean Edwards
  http://dean.edwards.name/IE7/
  http://www.opensource.org/licenses/mit-license.php
*/

/* W3C compliance for Microsoft Internet Explorer */

/* credits/thanks:
  Shaggy, Martijn Wargers, Jimmy Cerra, Mark D Anderson,
  Lars Dieckow, Erik Arvidsson, Gellért Gyuris, James Denny,
  Unknown W Brackets, Benjamin Westfarer, Rob Eberhardt,
  Bill Edney, Kevin Newman, James Crompton, Matthew Mastracci,
  Doug Wright, Richard York, Kenneth Kolano, MegaZone,
  Thomas Verelst, Mark 'Tarquin' Wilton-Jones, Rainer Åhlfors,
  David Zulaica, Ken Kolano, Kevin Newman
*/

// =======================================================================
// TO DO
// =======================================================================

// PNG - unclickable content

// =======================================================================
// TEST/BUGGY
// =======================================================================

// hr{margin:1em auto} (doesn't look right in IE5)

(function() {

IE7 = {
  toString: function(){return "IE7 version 2.0 (beta4)"}
};
var appVersion = IE7.appVersion = navigator.appVersion.match(/MSIE (\d\.\d)/)[1];

if (/ie7_off/.test(top.location.search) || appVersion < 5) return;

})();
