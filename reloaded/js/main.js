var Users = {
	ClassName: function( rights ) {
		if ( rights <= 20 ) {
			// user
			return "user_user";
		}
		else if ( rights <= 30 ) {
			return "journalist";
		}
		else if ( rights <= 50 ){
			return "operator";
		}
		else {
			return "developer";
		}
	}
};


var d = document;

function g(i){
	return d.getElementById(i);
}
function popup(url, name, width, height, returnWin, statusBar)
{
	url = url || "";
	width = width || 500;
	name = name || "CC";
	height = height || 600;
	var left = (screen.width - width)/2;
	var top = (screen.height - height)/2.1;
	var win = window.open(url, name, 'left = ' + left + ', top = ' + top + ', toolbar = 0, scrollbars = 1, location = 0, status = ' + (statusBar ? 1 : 0) + ', statusmenubar = 0, resizable = 1, width=' + width + ', height=' + height);
	if ( returnWin ){
		if (window.focus) {
			win.focus();
		}
		return win;
	}
	return false;
}

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function encode64(input) {
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   do {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);

      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;

      if (isNaN(chr2)) {
         enc3 = enc4 = 64;
      } 
	  else if (isNaN(chr3)) {
         enc4 = 64;
      }

      output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + 
         keyStr.charAt(enc3) + keyStr.charAt(enc4);
   } while (i < input.length);
   
   return output;
}

function ShowMore( section, ext ) {
	switch ( section ) {
		case "shoutbox":
			id = "moreshouts";
			break;
		case "comments":
			id = "morecomments";
			break;
		case "categories":
			id = "morecategories";
			if ( ext ) {
				g( "categorieslink" ).href = "index.php?p=allcategories";
				g( "categorieslink" ).title = "Εμφάνιση όλων των κατηγοριών";
			}
			break;
		case "onlineusers":
			id = "moreonlineusers";
			break;
	}
	if( g(id).style.display == "none" || g(id).style.display === "" ) { // this doesn't work without || g(id).style.display == ""
		g(id).style.display = "block";
		g( section + "link").className = "arrowup";
	}
	else {
		g(id).style.display = "none";
		g( section + "link").className = "arrow";
	}
}

function submitenter( myform, e ) {
	var keycode;
	if (window.event) {
		keycode = window.event.keyCode;
	}
	else if ( e ) {
		keycode = e.which;
	}
	else {
		return true;
	}
	if (keycode == 13) {
		if ( typeof myform.onsubmit == 'function' ) {
			myform.onsubmit();
		}
		else {
			myform.submit();
		}
		return false;
	}
	return true;
}

var Userbox = {
	Top: 36
	,onShowAnimation: false
	,Status: null
	,CookieStatusInfo: null
	,SetCookieTimeout: null
	,AnimationStep: 0
	,Animate: function() {
		if( Userbox.Status === null ) {
			Userbox.FindStatus();
		}
		g('userbox').style.position = "absolute";
		if( Userbox.Status == "hidden" ) {
			Userbox.Show();
		}
		else {
			Userbox.Hide();
		}
	}
	,FindStatus: function () {
		if( g('userbox').style.top == "-51px" ) {
			Userbox.Status == "hidden";
		}
		else {
			Userbox.Status == "shown";
		}
	}
	,Hide: function () {
		g('userboxhide').style.visibility = "hidden";
		Userbox.HideAnimation();
	}
	,HideAnimation: function () {
		if (Userbox.Top > -51) {
			setTimeout('Userbox.HideAnimation()', 50);
		}
		else {
			Userbox.Status = "hidden";
			g('userboxshow').style.visibility = "visible";
			Userbox.onShowAnimation = false;
		}
	}
	,Show: function () {
		if( Userbox.onShowAnimation === false) {
			Userbox.onShowAnimation = true;
			g('userboxshow').style.visibility = "hidden";
			Userbox.ShowAnimation();
		}
	}
	,ShowAnimation: function () {
		if ( Userbox.Top < 36 ) {
			setTimeout('Userbox.ShowAnimation()', 50);
		}
		else {
			Userbox.Status = "shown";
			g('userboxhide').style.visibility = "visible";
		}
	}
};
