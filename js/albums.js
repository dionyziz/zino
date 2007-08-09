var Albums = {
	ExpandCreateAlbum : function () {
		if ( document.getElementById( 'newalbum' ).style.display == "none" ) {
			document.getElementById( 'newalbum' ).style.display = "block";
			document.getElementById( 'createalbumlink' ).innerHTML = '&#171;Ακύρωση';
		}
		else {
			document.getElementById( 'newalbum' ).style.display = "none";
			document.getElementById( 'createalbumlink' ).innerHTML = 'Δημιουργία album&#187;';
			document.getElementById( 'albumtitle' ).value = "";
			document.getElementById( 'albumdescription' ).value = "";
		}
	
	}
	,
	CreateAlbum : function () {
		var title = document.getElementById( 'albumtitle' ).value;
		var description = document.getElementById( 'albumdescription' ).value;
		createalbum = true;
		if ( title === "" ) {
			alert( 'Το album σας δεν έχει όνομα. Για να δημιουργηθεί ένα album πρέπει να ορίσεις ένα όνομα.' );
			return;
		}
		if ( description === "" ) {
			if ( !confirm( 'Δεν έχεις ορίσει περιγραφή για το album σου. Θέλεις σίγουρα να προχωρήσεις στη δημιουργία του;' ) ) {
				return;
			}
		}
		Coala.Warm( 'albums/new' , { 'albumname' : title , 'albumdescription' : description } );
	}
	,
	DeleteAlbum : function ( albumid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις αυτό το album;' ) ) {
			Coala.Warm( 'albums/delete' , { 'albumid' : albumid } );
			var albumnode = document.getElementById( 'album' + albumid  );
			var parentnode = document.getElementById( 'albumcontainer' );
			Animations.Create( albumnode , 'opacity' , 1500 , 1 , 0 , Interpolators.Sin );
			Animations.Create( albumnode , 'width' , 900 , albumnode.offsetWidth , 0 , function () { parentnode.removeChild( albumnode ); } , Interpolators.Sin );
		}
	}
	,
	MainImage : function ( albumid , photoid , node ) {
		if ( confirm( 'Είσαι σίγουρος ότι θέλεις να θέσεις την φωτογραφία αυτή ως προεπιλεγμένη;' ) ) {
			Coala.Warm( 'albums/mainimage' , { 'albumid' : albumid , 'photoid' : photoid , 'node' : node } );
		}
	}
	,
	EditListAlbum : function ( albumid , typeid ) {
		//typeid is used to know whether a name or a description is being edited
		//0 for name, 1 for description
		var thealbum = document.getElementById( 'album' + albumid );
		var thealbumchilddivs = thealbum.getElementsByTagName( 'div' );
		var spaninv , theform , imageaccept , imagecancel , acceptlink , cancellink , theform;
		if ( typeid === 0 ) {		
			var intdiv = thealbumchilddivs[ 8 ];
			var intdivchilda = intdiv.getElementsByTagName( 'a' );
			
			var thefirstlink = intdivchilda[ 0 ];
			var thefirstlinkchildspan = thefirstlink.getElementsByTagName( 'span' );
			//thefirstlinkspan = thefirstlinkchildspan[ 1 ];
			//albumname = thefirstlink.innerHTML;
			
			spaninv = thefirstlinkchildspan[ 0 ];
			var albumname = spaninv.innerHTML;
			
			var editlink = intdivchilda[ 1 ];
			var deletelink = intdivchilda[ 2 ];
			
			theform = document.createElement( 'form' );
			theform.method = '';
			theform.action = '';
			theform.onsubmit = (function( albumid , albumname , typeid ) {
				return function() {
					Albums.SaveEditingListAlbum( albumid , albumname , typeid );
					return false;
				};
			})( albumid , albumname , 0 );
			
			var inputelement = document.createElement( 'input' );
			inputelement.type = 'text';
			inputelement.value = albumname;
			

			imageaccept = document.createElement( 'img' );
			imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';

			imagecancel = document.createElement( 'img' );
			imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
			
			acceptlink = document.createElement( 'a' );
			acceptlink.href = '';
			acceptlink.onclick = (function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( theform );
			acceptlink.alt = 'Ενημέρωση';
			acceptlink.title = 'Ενημέρωση';
			acceptlink.className = 'editinfos';
			acceptlink.appendChild( imageaccept );
			
			cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.onclick = (function( albumid, albumname, typeid ) { 
				return function() { 
					Albums.CancelEditingListAlbum( albumid, albumname, typeid ); 
					return false;
				};
			})( albumid, albumname, 0 );
			cancellink.className = 'editinfos';
			cancellink.appendChild( imagecancel );
			
			theform.appendChild( inputelement );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );
			intdiv.insertBefore( theform , deletelink );
			/*
			intdiv.insertBefore( inputelement , deletelink );
			intdiv.insertBefore( acceptlink , deletelink );
			intdiv.insertBefore( cancellink , deletelink );
			*/
			
			intdiv.removeChild( editlink );
			intdiv.removeChild( deletelink );
			intdiv.removeChild( thefirstlink );
			//intdiv.removeChild( spaninv );
			//focus input
			inputelement.focus();
			inputelement.select();
		}
		else if ( typeid == 1 ) {
			var descdiv = thealbumchilddivs[ 9 ];
			var descdivspanchild = descdiv.getElementsByTagName( 'span' );
			
			var thespan = descdivspanchild[ 1 ];
			var spaninv = descdivspanchild[ 0 ];
			var albumdescription = spaninv.innerHTML;
			
			var descdivachild = descdiv.getElementsByTagName( 'a' );
			var thelink = descdivachild[ 0 ];
			//thelink is gonna be removed
			
			theform = document.createElement( 'form' );
			theform.method = '';
			theform.action = '';
			theform.onsubmit = (function ( albumid , albumname , typeid ) {
				return function() {
					Albums.SaveEditingListAlbum( albumid , albumname , typeid );
					return false;
				}
			})( albumid , albumdescription , 1 );
			
			var theinput = document.createElement( 'input' );
			theinput.value = albumdescription;
			theinput.type = 'text';
			
			imageaccept = document.createElement( 'img' );
			imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
			
			imagecancel = document.createElement( 'img' );
			imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
			
			acceptlink = document.createElement( 'a' );
			acceptlink.href = '';
			acceptlink.alt = 'Ενημέρωση';
			acceptlink.title = 'Ενημέρωση';
			acceptlink.className = 'editinfos';
			acceptlink.onclick = (function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( theform );
			acceptlink.appendChild( imageaccept );
			
			cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.className = 'editinfos';
			cancellink.onclick = (function( albumid, albumdescription, typeid ) { 
				return function() { 
					Albums.CancelEditingListAlbum( albumid, albumdescription, typeid ); 
					return false;
				}
			})( albumid, albumdescription, 1 );
			cancellink.appendChild( imagecancel );
			
			theform.appendChild( theinput );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );
			
			descdiv.appendChild( theform );
			descdiv.removeChild( thelink );
			descdiv.removeChild( thespan );
			descdiv.removeChild( spaninv );
			theinput.focus();
			theinput.select();
		}
	}
	,
	CancelEditingListAlbum : function ( albumid , text , typeid ) {
		var thealbum = document.getElementById( 'album' + albumid );
		var thealbumchilddivs = thealbum.getElementsByTagName( 'div' );
		if ( typeid == 0 ) {
			var intdiv = thealbumchilddivs[ 8 ];
			var intdivchilda = intdiv.getElementsByTagName( 'a' );
			var acceptlink = intdivchilda[ 0 ];
			var cancellink = intdivchilda[ 1 ];
			
			var enteralbumlink = document.createElement( 'a' );
			enteralbumlink.href = 'index.php?p=album&id=' + albumid;
			enteralbumlink.className = 'enteralbum';
			
			var spaninv = document.createElement( 'span' );
			spaninv.appendChild( document.createTextNode( text ) );
			spaninv.style.display = 'none';
			
			var albumnamespan = document.createElement( 'span' );
			if ( text.length > 22 ) {
				text = text.substr( 0 , 22 );
				text += '...';
			}
			albumnamespan.appendChild( document.createTextNode( text ) );
			albumnamespan.className = 'albumname';
			
			enteralbumlink.appendChild( spaninv );
			enteralbumlink.appendChild( albumnamespan );
		
			var editimage = document.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.alt = 'Επεξεργασία ονόματος';
			editlink.title = 'Επεξεργασία ονόματος';
			editlink.className = 'editinfos';
			editlink.onclick = (function( albumid, typeid ) { 
				return function() { 
					Albums.EditListAlbum( albumid, typeid ); 
					return false;
				}
			})( albumid, 0 );
			editlink.appendChild( editimage );
			
			var deleteimage = document.createElement( 'img' );
			deleteimage.src = 'http://static.chit-chat.gr/images/icons/delete.png';
			
			var deletelink = document.createElement( 'a' );
			deletelink.href = '';
			deletelink.onclick = (function( albumid ) { 
				return function() { 
					Albums.DeleteAlbum( albumid ); 
					return false;
				}
			})( albumid );
			deletelink.alt = 'Διαγραφή album';
			deletelink.title = 'Διαγραφή album';
			deletelink.className = 'editinfos';
			deletelink.appendChild( deleteimage );
			
			intdiv.appendChild( enteralbumlink );
			intdiv.appendChild( editlink );
			intdiv.appendChild( deletelink );
		
			var intdivchild = intdiv.getElementsByTagName( 'form' );
			var theform = intdivchild[ 0 ];
			
			intdiv.removeChild( theform );
			//intdiv.removeChild( acceptlink );
			//intdiv.removeChild( cancellink );
			

		}
		else if ( typeid == 1 ) {
			var descdiv = thealbumchilddivs[ 9 ];
			
			var descdivformlist = descdiv.getElementsByTagName( 'form' );
			var theform = descdivformlist[ 0 ];		
			
			var spaninv = document.createElement( 'span' );
			spaninv.appendChild( document.createTextNode( text ) );
			spaninv.style.display = 'none';
			
			var thespan = document.createElement( 'span' );
			if ( text.length > 120 ) {
				text = text.substr( 0 , 120 );
				text += '...';
			}
			thespan.appendChild( document.createTextNode( text ) );
			
			var editimage = document.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
		
			var editlink = document.createElement( 'a' );
			editlink.alt = 'Επεξεργασία περιγραφής';
			editlink.title = 'Επεξεργασία περιγραφής';
			editlink.href = '';
			editlink.className = 'editinfos';
			editlink.onclick = (function( albumid, typeid ) { 
				return function() { 
					Albums.EditListAlbum( albumid, typeid ); 
					return false;
				}
			})( albumid, 1 );
			editlink.appendChild( editimage );
			
			descdiv.appendChild( spaninv );
			//descdiv.appendChild( thespan );
			descdiv.appendChild( thespan );
			descdiv.appendChild( editlink );
			descdiv.removeChild( theform );
		}
	},
	SaveEditingListAlbum : function ( albumid , text , typeid ) {
		var thealbum = document.getElementById( 'album' + albumid );
		var thealbumchilddivs = thealbum.getElementsByTagName( 'div' );
		if ( typeid == 0 ) {
			var intdiv = thealbumchilddivs[ 8 ];
			var intdivinputchild = intdiv.getElementsByTagName( 'input' );
			var theinput = intdivinputchild[ 0 ];	
			var newalbumname = theinput.value;
			if ( newalbumname == '' ) {
				alert( 'Πρέπει να ορίσεις ένα όνομα για το album' );
				theinput.value = text;
				theinput.focus();
				theinput.select();
			}
			else {
				Albums.CancelEditingListAlbum( albumid , newalbumname , 0 );
				if ( newalbumname != text ) {
					Coala.Warm( 'albums/edit' , { 'albumid' : albumid , 'newtext' : newalbumname , 'typeid' : 0 } );
				}
			}
		}
		else if ( typeid == 1 ) {
			var descdiv = thealbumchilddivs[ 9 ];
			var descdivinputlist = descdiv.getElementsByTagName( 'input' );
			var theinput = descdivinputlist[ 0 ];
			var newdescription = theinput.value;
			var newdescriptionshow = newdescription;
			if ( newdescription == '' ) {
				newdescriptionshow = '-Δεν έχεις ορίσει περιγραφή-';
			}
			Albums.CancelEditingListAlbum( albumid , newdescriptionshow , 1 );
			if  ( newdescription != text ) {	
				Coala.Warm( 'albums/edit' , { 'albumid' : albumid , 'newtext' : newdescription , 'typeid' : 1 } );
			}
		}
	}
	,
	EditSmallAlbum : function( albumid , typeid ) {
		var outerdiv = document.getElementById( 'smallheader' );
		var outerdivchilddiv = outerdiv.getElementsByTagName( 'div' );
		if ( typeid == 0 ) {
			var thediv = outerdivchilddiv[ 0 ];
			var thedivchildh2 = thediv.getElementsByTagName( 'h2' );
			var theh2 = thedivchildh2[ 0 ];
			var albumname = theh2.innerHTML;
			
			var theform = document.createElement( 'form' );
			theform.method = '';
			theform.action = '';
			theform.onsubmit = (function( albumid , text, typeid ) { 
				return function() { 
					Albums.SaveEditingSmallAlbum( albumid , text, typeid ); 
					return false;
				}
			})( albumid , albumname , 0 );
			var theinput = document.createElement( 'input' );
			theinput.type = 'text';
			theinput.value = albumname;
			
			var imageaccept = document.createElement( 'img' );
			imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
			
			var imagecancel = document.createElement( 'img' );
			imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
			
			var acceptlink = document.createElement( 'a' );
			acceptlink.href = '';
			acceptlink.onclick = (function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( theform );
			acceptlink.alt = 'Ενημέρωση';
			acceptlink.title = 'Ενημέρωση';
			acceptlink.className = 'editinfos';
			acceptlink.appendChild( imageaccept );
			
			var cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.onclick = (function( albumid , text, typeid ) { 
				return function() { 
					Albums.CancelEditingSmallAlbum( albumid , text, typeid ); 
					return false;
				}
			})( albumid , albumname, 0 );
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.className = 'editinfos';
			cancellink.appendChild( imagecancel );
			
			var thedivchilda = thediv.getElementsByTagName( 'a' );
			var editlink = thedivchilda[ 0 ];
			
			theform.appendChild( theinput );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );
			thediv.insertBefore( theform , theh2 );
			thediv.removeChild( theh2 );
			thediv.removeChild( editlink );
			theinput.focus();
			theinput.select();
		
		}
		else if ( typeid == 1 ) {
			var thediv = outerdivchilddiv[ 1 ];
			
			var thedivchildspan = thediv.getElementsByTagName( 'span' );
			var spandescription = thedivchildspan[ 0 ];
			var description = spandescription.innerHTML;
			
			var theform = document.createElement( 'form' );
			theform.action = '';
			theform.method = '';
			theform.onsubmit = (function( albumid , text, typeid ) { 
				return function() { 
					Albums.SaveEditingSmallAlbum( albumid , text, typeid ); 
					return false;
				}
			})( albumid , description , 1 );
			var theinput = document.createElement( 'input' );
			theinput.type = 'text';
			theinput.value = description;

			var imageaccept = document.createElement( 'img' );
			imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
			
			var imagecancel = document.createElement( 'img' );
			imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
			
			var acceptlink = document.createElement( 'a' );
			acceptlink.href = '';
			acceptlink.onclick = (function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( theform );
			acceptlink.alt = 'Ενημέρωση';
			acceptlink.title = 'Ενημέρωση';
			acceptlink.className = 'editinfos';
			acceptlink.appendChild( imageaccept );
			
			var cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.onclick = (function( albumid , text, typeid ) { 
				return function() { 
					Albums.CancelEditingSmallAlbum( albumid , text, typeid ); 
					return false;
				}
			})( albumid , description, 1 );
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.className = 'editinfos';
			cancellink.appendChild( imagecancel );
			
			var thedivchildsmall = thediv.getElementsByTagName( 'small' );
			var thesmall = thedivchildsmall[ 0 ];

			var thesmallchilda = thesmall.getElementsByTagName( 'a' );
			var editlink = thesmallchilda[ 0 ];
			
			theform.appendChild( theinput );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );
			thesmall.insertBefore( theform , spandescription );
			thesmall.removeChild( spandescription );
			thesmall.removeChild( editlink );
			theinput.focus();
			theinput.select();
		}
	
	}
	,
	CancelEditingSmallAlbum : function ( albumid , text , typeid ) {
		var outerdiv = document.getElementById( 'smallheader' );
		var outerdivchilddiv = outerdiv.getElementsByTagName( 'div' );
		if ( typeid == 0 ) {
			var thediv = outerdivchilddiv[ 0 ];
			var thedivchildform = thediv.getElementsByTagName( 'form' );
			var theform = thedivchildform[ 0 ];
			
			var theh2 = document.createElement( 'h2' );
			var albumname = document.createTextNode( text );
			theh2.appendChild( albumname );
			
			var editimage = document.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = (function( albumid , typeid ) { 
				return function() { 
					Albums.EditSmallAlbum( albumid , typeid ); 
					return false;
				}
			})( albumid , 0 );
			editlink.alt = 'Επεξεργασία ονόματος';
			editlink.title = 'Επεξεργασία ονόματος';
			editlink.className = 'editinfos';
			editlink.appendChild( editimage );
			
			thediv.insertBefore( theh2 , theform );
			thediv.insertBefore( editlink , theform );
			thediv.removeChild( theform );
		}
		else if ( typeid == 1 ) {
			var thediv = outerdivchilddiv[ 1 ];
			
			var thedivchildsmall = thediv.getElementsByTagName( 'small' );
			var thesmall = thedivchildsmall[ 0 ];
			
			var thesmallchildform = thesmall.getElementsByTagName( 'form' );
			var theform = thesmallchildform[ 0 ];		

			var thespan = document.createElement( 'span' );
			thespan.className = 'details';
			thespan.style.fontSize = '10pt';
			var description = document.createTextNode( text );
			thespan.appendChild( description );
			
			var editimage = document.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = (function( albumid , typeid ) { 
				return function() { 
					Albums.EditSmallAlbum( albumid , typeid ); 
					return false;
				}
			})( albumid , 1 );
			editlink.alt = 'Επεξεργασία περιγραφής';
			editlink.title = 'Επεξεργασία περιγραφής';
			editlink.className = 'editinfos';
			editlink.appendChild( editimage );
			
			thesmall.insertBefore( thespan , theform );
			thesmall.insertBefore( editlink , theform );
			thesmall.removeChild( theform );
		}
	}
	,
	SaveEditingSmallAlbum : function( albumid , text , typeid ) {
		var outerdiv = document.getElementById( 'smallheader' );
		var outerdivchilddiv = outerdiv.getElementsByTagName( 'div' );
		if ( typeid == 0 ) {
			var thediv = outerdivchilddiv[ 0 ];
			var thedivchildinput = thediv.getElementsByTagName( 'input' );
			var theinput = thedivchildinput[ 0 ];
			var newalbumname = theinput.value;
			
			if ( newalbumname == '' ) {
				alert( 'Πρέπει να ορίσεις ένα όνομα για το album' );
				theinput.value = text;
				theinput.focus();
				theinput.select();
			}
			else {
				Albums.CancelEditingSmallAlbum( albumid , newalbumname , 0 );
				document.title = newalbumname + ' / Chit-Chat';
				if ( newalbumname != text ) {
					Coala.Warm( 'albums/edit' , { 'albumid' : albumid , 'newtext' : newalbumname , 'typeid' : 0 } );
				}
			}
		}
		else if ( typeid == 1 ) {
			var thediv = outerdivchilddiv[ 1 ];
			var thedivchildinput = thediv.getElementsByTagName( 'input' );
			var theinput = thedivchildinput[ 0 ];
			var newdescription = theinput.value;
			var newdescriptionshow = newdescription;
			if ( newdescription == '' ) {
				newdescriptionshow = '-Δεν έχεις ορίσει περιγραφή-';
			}
			Albums.CancelEditingSmallAlbum( albumid , newdescriptionshow , 1 );
			if ( newdescription != text ) {
				Coala.Warm( 'albums/edit' , { 'albumid' : albumid , 'newtext' : newdescription , 'typeid' : 1 } );
			}
		}
	}
}
