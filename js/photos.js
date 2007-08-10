var Photos = {
	EditListPhoto : function( photo , photoid , typeid ) {
		var masterdiv = photo;
		if ( typeid === 0 ) {
			var masterdivchilda = masterdiv.getElementsByTagName( 'a' );
			var firstlink = masterdivchilda[ 0 ];
			var secondlink = masterdivchilda[ 1 ];
			var thirdlink = masterdivchilda[ 2 ];
			var deletelink = masterdivchilda[ 3 ];
			var firstlinkchildspan = firstlink.getElementsByTagName( 'span' );
			var firstspan = firstlinkchildspan [ 0 ];
			var photoname = firstspan.innerHTML;
			
			var theform = document.createElement( 'form' );
			theform.method = '';
			theform.action = '';
			theform.onsubmit = (function ( photo ,  photoid , text , typeid ) {	
				return function()  {
					Photos.SaveEditingListPhoto( photo , photoid , text , typeid );
					return false;
				};
			})( masterdiv , photoid , photoname , 0 );
			var theinput = document.createElement( 'input' );
			theinput.type = 'text';
			theinput.value = photoname;
			//masterdiv.insertBefore( theinput , firstlink );
			
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
			acceptlink.appendChild( imageaccept );
			
			var cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.onclick = (function( photo , photoid , text , typeid ) {
				return function() {
					Photos.CancelEditingListPhoto( photo , photoid , text , typeid );
					return false;
				};
			})( masterdiv , photoid , photoname , 0 );
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.appendChild( imagecancel );
			
			theform.appendChild( theinput );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );
			masterdiv.insertBefore( theform , firstlink );
			
			theinput.focus();
			theinput.select();
			
			masterdiv.removeChild( firstlink );
			masterdiv.removeChild( secondlink );
			//masterdiv.removeChild( thirdlink );
			masterdiv.removeChild( deletelink );
			
			thirdlink.style.display = 'none';
			
		}
		else if ( typeid == 1 ) {
			var masterdivchildspan = masterdiv.getElementsByTagName( 'span' );
			var masterdivchilda = masterdiv.getElementsByTagName( 'a' );
			
			var editlink = masterdivchilda[ masterdivchilda.length - 1 ];
			var descriptionspan = masterdivchildspan[ masterdivchildspan.length - 4 ];
			var photodescription = descriptionspan.innerHTML;
			
			var spandescshow = masterdivchildspan[ masterdivchildspan.length - 3 ];
			
			var masterdivchilddiv = masterdiv.getElementsByTagName( 'div' );
			
			var infodiv = masterdivchilddiv[ 0 ];
			var infodivchildspan = infodiv.getElementsByTagName( 'span' );
			var photonumber = infodivchildspan[ 0 ];
			var photopageviews = infodivchildspan[ 1 ];
			
			var theform = document.createElement( 'form' );
			theform.method = '';
			theform.action = '';
			theform.onsubmit = ( function( photo , photoid , text , typeid ) {
				return function() {
					Photos.SaveEditingListPhoto( photo , photoid , text , typeid );
					return false;
				};
			})( masterdiv , photoid , photodescription , 1 );
			var theinput = document.createElement( 'input' );
			theinput.type = 'text';
			theinput.value = photodescription;
			
			var imageaccept = document.createElement( 'img' );
			imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
			
			var imagecancel = document.createElement( 'img' );
			imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
			
			var acceptlink = document.createElement( 'a' );
			acceptlink.href = '';
			acceptlink.onclick = ( function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( theform );
			acceptlink.alt = 'Ενημέρωση';
			acceptlink.title = 'Ενημέρωση';
			acceptlink.appendChild( imageaccept );
			
			var cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.onclick = (function( photo ,  photoid , text , typeid ) {
				return function() {
					Photos.CancelEditingListPhoto( photo , photoid , text , typeid );
					return false;
				};
			})( masterdiv , photoid , photodescription , 1 );
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.appendChild( imagecancel );
			
			theform.appendChild( theinput );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );

			masterdiv.insertBefore( theform , infodiv );

			theinput.focus();
			theinput.select();

			//photonumber.style.display = 'none';
			//photopageviews.style.display = 'none';
			infodiv.style.display = 'none';
			masterdiv.removeChild( descriptionspan );
			masterdiv.removeChild( editlink );
			spandescshow.parentNode.removeChild( spandescshow );
		}
	}
	,
	CancelEditingListPhoto : function( photo , photoid , text , typeid ) {
		var masterdiv = photo;
		if ( typeid === 0 ) {
			var masterdivchildinput = masterdiv.getElementsByTagName( 'form' );
			var firstinput = masterdivchildinput[ 0 ];
			
			var masterdivchilda = masterdiv.getElementsByTagName( 'a' );
			var thirdlink = masterdivchilda[ 2 ];
			
			var namespaninv = document.createElement( 'span' );
			namespaninv.appendChild( document.createTextNode( text ) );
			namespaninv.style.display = 'none';
			
			var namespan = document.createElement( 'span' );
			if ( text.length > 20 ) {
				text = text.substr( 0 , 20 );
				text += '...';
			}
			namespan.appendChild( document.createTextNode( text ) );
			namespan.className = 'albumname';
			

			
			var namelink = document.createElement( 'a' );
			namelink.href = 'index.php?p=photo&id=' + photoid;
			namelink.className = 'enterphoto';
			namelink.appendChild( namespaninv );
			namelink.appendChild( namespan );
			
			var imageedit = document.createElement( 'img' );
			imageedit.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = (function( photo , photoid, typeid ) { 
				return function() { 
					Photos.EditListPhoto( photo , photoid, typeid ); 
					return false;
				};
			})( masterdiv ,  photoid, 0 );
			editlink.alt = 'Επεξεργασία ονόματος';
			editlink.title = 'Επεξεργασία περιγραφής';
			editlink.className = 'editinfos';
			editlink.appendChild( imageedit );
			
			var deleteimage = document.createElement( 'img' );
			deleteimage.src = 'http://static.chit-chat.gr/images/icons/delete.png';
			
			var deletelink = document.createElement( 'a' );
			deletelink.className = 'editinfos';
			deletelink.alt = 'Διαγραφή φωτογραφίας';
			deletelink.title = 'Διαγραφή φωτογραφίας';
			deletelink.href = '';
			deletelink.onclick = (function( photo , photoid ) { 
				return function() { 
					Photos.DeletePhoto( photo , photoid ); 
					return false;
				};
			})( masterdiv ,  photoid );
			deletelink.appendChild( deleteimage );
			
			masterdiv.insertBefore( namelink , firstinput );
			masterdiv.insertBefore( editlink , firstinput );

			thirdlink.style.display = 'inline';
			masterdiv.insertBefore( deletelink , thirdlink.nextSibling );
			masterdiv.removeChild( firstinput );
		}
		else if ( typeid == 1 ) {
			var masterchildinput = masterdiv.getElementsByTagName( 'form' );
			var theform = masterchildinput[ masterchildinput.length - 1 ];
			var masterdivchildspan = masterdiv.getElementsByTagName( 'span' );
			
			var masterdivchilddiv = masterdiv.getElementsByTagName( 'div' );
			
			var infodiv = masterdivchilddiv[ 0 ];
			var infodivchildspan = infodiv.getElementsByTagName( 'span' );
			var photonumber = infodivchildspan[ 0 ];
			var photopageviews = infodivchildspan[ 1 ];
			
			var masterchilda = masterdiv.getElementsByTagName( 'a' );
			var acceptlink = masterchilda[ masterchilda.length - 2 ];
			var cancellink = masterchilda[ masterchilda.length - 1 ];
			
			var spandescriptioninv = document.createElement( 'span' );
			spandescriptioninv.appendChild( document.createTextNode( text ) );
			spandescriptioninv.style.display = 'none';
			
			var spandescription = document.createElement( 'span' );
			if ( text.length > 65 ) {
				text = text.substr( 0 , 65 );
				text += '...';
			}
			spandescription.appendChild( document.createTextNode( text ) );
			spandescription.className = 'photodescription';
			

			
			var imageedit = document.createElement( 'img' );
			imageedit.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = (function( photo , photoid , typeid ) {
				return function() {
					Photos.EditListPhoto( photo , photoid , typeid );
					return false;
				};
			})( masterdiv , photoid , 1 );
			editlink.alt = 'Επεξεργασία περιγραφής';
			editlink.title = 'Επεξεργασία περιγραφής';
			editlink.className = 'editinfos';
			editlink.appendChild( imageedit );
			
			masterdiv.insertBefore( spandescriptioninv , infodiv );
			masterdiv.insertBefore( spandescription , infodiv );
			masterdiv.insertBefore( editlink , infodiv );
			//photonumber.style.display = 'inline';
			//photopageviews.style.display = 'inline';
			infodiv.style.display = 'block';
			masterdiv.removeChild( theform );
		}
	}
	,
	SaveEditingListPhoto : function( photo , photoid , text , typeid ) {
		var thephoto = photo;
		var thephotochildform = thephoto.getElementsByTagName( 'form' );
		if ( typeid === 0 ) {
			var thefirstform = thephotochildform[ 0 ];
			var thephotochildinput = thefirstform.getElementsByTagName( 'input' );
			var theinput = thephotochildinput[ 0 ];
			var newphotoname = theinput.value;
			if ( newphotoname === '' ) {
				alert( 'Πρέπει να ορίσεις ένα όνομα για την φωτογραφία' );
				theinput.value = text;
				theinput.focus();
				theinput.select();
			}
			else {
				Photos.CancelEditingListPhoto( photo , photoid , newphotoname , 0 );
				if ( newphotoname != text )  {
					Coala.Warm( 'albums/photos/edit' , { 'photoid' : photoid , 'newtext' : newphotoname , 'typeid' :  0 } );
				}
			}
		}
		else if ( typeid == 1 ) {
			var thefirstform = thephotochildform[ thephotochildform.length - 1 ];
			var thephotochildinput = thefirstform.getElementsByTagName( 'input' );
			var theinput = thephotochildinput[ 0 ];
			var newdescription = theinput.value;
			var newphotodescriptionshow = newdescription;
			if ( newdescription === '' ) {
				newphotodescriptionshow = '-Δεν έχεις ορίσει περιγραφή-';
			}
			Photos.CancelEditingListPhoto( photo , photoid , newphotodescriptionshow , 1 );
			if ( newdescription != text ) {
				Coala.Warm( 'albums/photos/edit' , { 'photoid' : photoid , 'newtext' : newdescription , 'typeid' : 1 } );
			}
		}
	}
	,
	EditSmallPhoto : function ( photoid , typeid , node ) {
		var thediv = node;
		if ( typeid === 0 ) {
			var thedivchildh2 = thediv.getElementsByTagName( 'h2' );
			var theh2 = thedivchildh2[ 0 ];
			var photoname = theh2.innerHTML;
			
			var thedivchilda = thediv.getElementsByTagName( 'a' );
			var editlink = thedivchilda[ 0 ];
			
			var theform = document.createElement( 'form' );
			theform.action = '';
			theform.method = '';
			theform.onsubmit = (function( photoid, text , typeid , node ) { 
				return function() { 
					Photos.SaveEditingSmallPhoto( photoid, text , typeid , node); 
					return false;
				};
			})( photoid , photoname , 0 , thediv );
			var theinput = document.createElement( 'input' );
			theinput.type = 'text';
			theinput.value = photoname;
			
			var imageaccept = document.createElement( 'img' );
			imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
			
			var imagecancel = document.createElement( 'img' );
			imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
			
			var acceptlink = document.createElement( 'a' );
			acceptlink.href = '';
			acceptlink.onclick = (function( photoid, text , typeid , node ) { 
				return function() { 
					Photos.SaveEditingSmallPhoto( photoid, text , typeid , node); 
					return false;
				};
			})( photoid , photoname , 0 , thediv );
			acceptlink.alt = 'Ενημέρωση';
			acceptlink.title = 'Ενημέρωση';
			acceptlink.className = 'editinfos';
			acceptlink.appendChild( imageaccept );
			
			var cancellink = document.createElement( 'a' );
			cancellink.href = '';
			cancellink.onclick = (function( photoid, text , typeid , node ) { 
				return function() { 
					Photos.CancelEditingSmallPhoto( photoid, text , typeid , node ); 
					return false;
				};
			})( photoid , photoname , 0 , thediv );
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.appendChild( imagecancel );
			
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
			var parent = thediv.parentNode;
			var thediv = parent.getElementsByTagName( 'small' )[ 0 ];
			var thedivchildspan = thediv.getElementsByTagName( 'span' );
			var thespan = thedivchildspan[ 0 ];
			var photodescription = thespan.innerHTML;
			
			var thedivchilda = thediv.getElementsByTagName( 'a' );
			var editlink = thedivchilda[ 0 ];
			
			var theform = document.createElement( 'form' );
			theform.action = '';
			theform.method = '';
			theform.onsubmit = (function( photoid, text , typeid , node ) { 
				return function() { 
					Photos.SaveEditingSmallPhoto( photoid, text , typeid , node ); 
					return false;
				};
			})( photoid , photodescription , 1 , node );
			var theinput = document.createElement( 'input' );
			theinput.type = 'text';
			theinput.value = photodescription;
			
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
			cancellink.onclick = (function( photoid, text , typeid , node ) { 
				return function() { 
					Photos.CancelEditingSmallPhoto( photoid, text , typeid , node ); 
					return false;
				};
			})( photoid , photodescription , 1 , thediv );
			cancellink.alt = 'Ακύρωση';
			cancellink.title = 'Ακύρωση';
			cancellink.className = 'editinfos';
			cancellink.appendChild( imagecancel );
			
			theform.appendChild( theinput );
			theform.appendChild( acceptlink );
			theform.appendChild( cancellink );
			thediv.insertBefore( theform , thespan );
			thediv.removeChild( thespan );
			thediv.removeChild( editlink );
			
			theinput.focus();
			theinput.select();
		
		}
	}
	,
	CancelEditingSmallPhoto : function ( photoid , text , typeid , node ) {
		var thediv = node;
		if ( typeid === 0 ) {
			var thedivchildform = thediv.getElementsByTagName( 'form' );
			var theform = thedivchildform[ 0 ];
			
			var theh2 = document.createElement( 'h2' );
			theh2.appendChild( document.createTextNode( text ) );
			
			var editimage = document.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = (function( photoid, typeid , node ) { 
				return function() { 
					Photos.EditSmallPhoto( photoid , typeid , node ); 
					return false;
				};
			})( photoid , 0 , node );
			editlink.alt = 'Επεξεργασία ονόματος';
			editlink.title = 'Επεξεργασία περιγραφής';
			editlink.className = 'editinfos';
			editlink.appendChild( editimage );
			
			thediv.insertBefore( theh2 , theform );
			thediv.insertBefore( editlink , theform );
			thediv.removeChild( theform );
		}
		else if ( typeid == 1 ) {
			var parent = thediv.parentNode;
			thediv = parent.getElementsByTagName( 'small' )[ 0 ];
			
			var thedivchildform = thediv.getElementsByTagName( 'form' );
			var theform = thedivchildform[ 0 ];
			
			var thespan = document.createElement( 'span' );
			thespan.appendChild( document.createTextNode( text ) );
			thespan.className = 'details';
			thespan.style.fontSize = '9pt';
			var editimage = document.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = (function( photoid, typeid , node ) { 
				return function() { 
					Photos.EditSmallPhoto( photoid , typeid , node ); 
					return false;
				};
			})( photoid , 1 , thediv );
			editlink.alt = 'Επεξεργασία ονόματος';
			editlink.title = 'Επεξεργασία ονόματος';
			editlink.className = 'editinfos';
			editlink.appendChild( editimage );
			
			thediv.insertBefore( thespan , theform );
			thediv.insertBefore( editlink , theform );
			thediv.removeChild( theform );
		}
	}
	,
	SaveEditingSmallPhoto : function ( photoid , text , typeid , node ) {
		var thediv = node;
		if ( typeid === 0 ) {
			var thedivchildinput = thediv.getElementsByTagName( 'input' );		
			var theinput = thedivchildinput[ 0 ];
			var newphotoname = theinput.value;
			if ( newphotoname === '' ) {
				alert( 'Πρέπει να ορίσεις ένα όνομα για την φωτογραφία' );
				theinput.value = text;
				theinput.focus();
				theinput.select();
			}
			else {
				if ( newphotoname != text ) {
					Photos.CancelEditingSmallPhoto( photoid , newphotoname , 0 , thediv );
					document.title = newphotoname + ' / Chit-Chat';
					Coala.Warm( 'albums/photos/edit' , { 'photoid' : photoid , 'newtext' : newphotoname , 'typeid' :  0 } );
				}
				else {
					Photos.CancelEditingSmallPhoto( photoid , text , 0 , thediv );
				}
			}
		}
		else if ( typeid == 1 ) {
			var thedivchildinput = thediv.getElementsByTagName( 'input' );		
			var theinput = thedivchildinput[ 0 ];
			var newphotodescription = theinput.value;
			if ( newphotodescription != text ) {
				if ( newphotodescription == '' ) {
					newphotodescription = '-Δεν έχεις ορίσει περιγραφή-';
				}
				Photos.CancelEditingSmallPhoto( photoid , newphotodescription , 1 , thediv );
				Coala.Warm( 'albums/photos/edit' , { 'photoid' : photoid , 'newtext' : newphotodescription , 'typeid' : 1 } );
			}
			else {
				Photos.CancelEditingSmallPhoto( photoid , text , 1 , thediv );
			}
		
		}
	}
	,
	AddPhoto : function ( imageinfo ) {		
		//imageinfo is an array
		var outerdiv = parent.document.getElementById( 'content' );
		//alert( 'outerdiv: ' + outerdiv );
		var pid = imageinfo[ 'id' ];
		var puserid = imageinfo[ 'userid' ];
		var pname = imageinfo[ 'name' ];
		var pmainimage = imageinfo[ 'mainimage' ];
		var pwidth = imageinfo[ 'width' ];
		var pheight = imageinfo[ 'height' ];
		var albumid = imageinfo[ 'albumid' ];
		var pnum = imageinfo[ 'imagesnum' ]; 
		//alert( ' pid :' + pid + ' pname: ' + pname + ' pmainimage: ' + pmainimage + ' pwidth: ' + pwidth + ' pheight: ' + pheight + ' albumid: ' + albumid + ' pnum: ' + pnum );
		var photoviewdiv = document.createElement( 'div' );
		photoviewdiv.className = 'photoview';
		photoviewdiv.id = 'photo' + pid;
		//alert( 'photoviewdiv: ' + photoviewdiv );
		var enterphotolink = document.createElement( 'a' );
		enterphotolink.href = 'index.php?p=photo&id=' + pid;
		enterphotolink.className = 'enterphoto';
		
		var photonamespaninv = document.createElement( 'span' );
		photonamespaninv.style.display = 'none';
	
		var photoname = document.createTextNode( pname );
		photonamespaninv.appendChild( photoname );

		var photonamespan = document.createElement( 'span' );
		photonamespan.className = 'albumname';

		if ( pname.length > 18 ) {
			photonamespan.appendChild( document.createTextNode( pname.substr( 0 , 18 ) + '...' ) );
		}
		else {
			photonamespan.appendChild( document.createTextNode( pname ) );
		}
		
		enterphotolink.appendChild( photonamespaninv );
		enterphotolink.appendChild( photonamespan );
		
		photoviewdiv.appendChild( enterphotolink );

		var editimage = document.createElement( 'img' );
		editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
		
		var editlink = document.createElement( 'a' );
		editlink.href = '';
		editlink.onclick = (function( photo , photoid, typeid ) { 
				return function() { 
					Photos.EditListPhoto( photo , photoid , typeid ); 
					return false;
				}
		})( photoviewdiv , pid, 0, this );
		editlink.alt = 'Επεξεργασία ονόματος';
		editlink.title = 'Επεξεργασία ονόματος';
		editlink.className = 'editinfos';
		editlink.appendChild( editimage );
		photoviewdiv.appendChild( editlink );

		var mainimageimage = document.createElement( 'img' );
		mainimageimage.src = 'http://static.chit-chat.gr/images/icons/vcard.png';

		var mainimagelink = document.createElement( 'a' );
		mainimagelink.href = '';
		mainimagelink.onclick = (function( albumid, photoid , node ) { 
			return function() { 
				Albums.MainImage( albumid , photoid , node ); 
				return false;
			}
		})( albumid , pid , photoviewdiv );
		mainimagelink.alt = 'Ορισμός προεπιλεγμένης φωτογραφίας album';
		mainimagelink.title = 'Ορισμός προεπιλεγμένης φωτογραφίας album';
		mainimagelink.className = 'editinfosmainimg';
		mainimagelink.appendChild( mainimageimage );
		
		var deleteimage = document.createElement( 'img' );
		deleteimage.src= 'http://static.chit-chat.gr/images/icons/delete.png';
		
		var deletelink = document.createElement( 'a' );
		deletelink.href = '';
		deletelink.onclick = (function( photo , photoid , albumid ) { 
			return function() { 
				Photos.DeletePhoto( photo , photoid , albumid  ); 
				return false;
			}
		})( photoviewdiv ,  pid , albumid );
		deletelink.alt = 'Διαγραφή φωτογραφίας';
		deletelink.title = 'Διαγραφή φωτογραφίας';
		deletelink.className = 'editinfos';
		deletelink.appendChild( deleteimage );
		if ( pnum == 1 ) {
			Coala.Warm( 'albums/mainimage' , { 'albumid' : albumid , 'photoid' : pid , 'node' : photoviewdiv } ); 
		}
		photoviewdiv.appendChild( mainimagelink );
		photoviewdiv.appendChild( deletelink );
		photoviewdiv.appendChild( document.createElement( 'br' ) );
		
		var imagesrc = 'http://images.chit-chat.gr/' + puserid + '/' + pid + '?resolution=' + pwidth + 'x' + pheight;
		//imagesrc = 'image.php?id=' + pid + '&width=' + pwidth + '&height=' + pheight;
		var preloadimage = new Image( pwidth , pheight );
		preloadimage.src = imagesrc;
		var enterphotoimg = document.createElement( 'img' );
		enterphotoimg.style.width = pwidth + 'px';
		enterphotoimg.style.height = pheight + 'px';
		
		var enterphotolink2 = document.createElement( 'a' );
		enterphotolink2.href = 'index.php?p=photo&id=' + pid;
		enterphotolink2.className = 'enterphoto';
		enterphotolink2.alt = pname;
		enterphotolink2.title = pname;
		enterphotolink2.appendChild( enterphotoimg );
		
		photoviewdiv.appendChild( enterphotolink2 );
		photoviewdiv.appendChild( document.createElement( 'br' ) );
		
		var spaninv = document.createElement( 'span' );
		spaninv.style.display = 'none';
		spaninv.appendChild( document.createTextNode( '-Δεν έχεις ορίσει περιγραφή-' ) );
		
		var photodescriptionspan = document.createElement( 'span' );
		photodescriptionspan.className = 'photodescription';

		photodescriptionspan.appendChild( document.createTextNode( '-Δεν έχεις ορίσει περιγραφή-' ) );
		
		photoviewdiv.appendChild( spaninv );
		photoviewdiv.appendChild( photodescriptionspan );

		var editlinkimg = document.createElement( 'img' );
		editlinkimg.src = 'http://static.chit-chat.gr/images/icons/edit.png';
		
		var editlink2 = document.createElement( 'a' );
		editlink2.href = '';
		editlink2.onclick = (function( photo, photoid, typeid ) { 
				return function() { 
					Photos.EditListPhoto( photo, photoid , typeid ); 
					return false;
				}
		})( photoviewdiv , pid, 1 );
		
		editlink2.className = 'editinfos';
		editlink2.alt = 'Επεξεργασία περιγραφής';
		editlink2.title = 'Επεξεργασία περιγραφής';
		editlink2.appendChild( editlinkimg );
		photoviewdiv.appendChild( editlink2 );
		var infodiv = document.createElement( 'div' );
		
		//alert( 'ola ok' );
		var anotherspan = document.createElement( 'span' );
		//alert( 'photonumber: ' + anotherspan.innerHTML );
		var anotherspan2 = document.createElement( 'span' );
		//alert( 'photopageviews: ' + photopageviews );
		infodiv.appendChild( anotherspan );
		infodiv.appendChild( anotherspan2 );
		//infodiv.innerHTML += '<span></span><span></span>';
		//the line above is the lamest piece of code I have ever written
		
		photoviewdiv.appendChild( infodiv );
		outerdiv.appendChild( photoviewdiv );
		enterphotoimg.src = imagesrc;
		
		var element = g( 'album_photosnum' );
		if ( element.firstChild ) {
			var photosnumber = element.childNodes[ 0 ].nodeValue.split( " " )[ 0 ];
		}
		else {
			var photosnumber = 0;
		}
		++photosnumber;
		Photos.UpdatePhotoNumberSmall( photosnumber );
		if ( pnum == 1 ) {
			var nophotosspan = document.getElementById( 'nophotos' );
			nophotosspan.parentNode.removeChild( nophotosspan );
		}
	}
	,
	DeletePhoto : function ( nodetodelete , photoid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις την φωτογραφία;' ) ) {
			Coala.Warm( 'albums/photos/delete' , { 'photoid' : photoid } );
			var element = g( 'album_photosnum' );
			if ( element.firstChild ) {
				var photosnumber = element.childNodes[ 0 ].nodeValue.split( " " )[ 0 ];
			}
			else {
				var photosnumber = 0;
			}
			--photosnumber;
			Photos.UpdatePhotoNumberSmall( photosnumber );
			if ( photoid == AlbumMainImage ) {
				var albumid = g('myalbumid').firstChild.nodeValue;
				Coala.Warm( 'albums/mainimage' , { 'albumid' : albumid , 'photoid' : '0' , 'node' : nodetodelete } );
			}
			Animations.Create( nodetodelete , 'opacity' , 1500 , 1 , 0 , Interpolators.Sin );
			Animations.Create( nodetodelete , 'width' , 900 , nodetodelete.offsetWidth , 0 , function () { nodetodelete.parentNode( nodetodelete ) } , Interpolators.Sin );
		}
	}
	,
	Newphoto : function ( thelink ) {
		var newdiv = document.getElementById( 'newphoto' );
		if ( newdiv.style.display == 'none' ) {
			newdiv.style.display = 'block';
			thelink.innerHTML = '&#171;Ακύρωση';
		}
		else {
			newdiv.style.display = 'none';
			thelink.innerHTML = 'Νέα φωτογραφία&#187;';
		}
	}
	,
	UpdatePhotoNumberSmall : function ( newnumber ) {
		if ( newnumber == 1 ) {
			var newnumbertext = ' φωτογραφία';
		}
		else {
			var newnumbertext = ' φωτογραφίες';
		}
		if ( document.getElementById( 'photonumber' ) ) {	
			var photospan = document.getElementById( 'photonumber' );
			if ( photospan.nextSibling ) {
				newnumbertext += ',';
			}
			photospan.innerHTML = newnumber + newnumbertext;
		}
		else { 
			var photospan = document.createElement( 'span' );
			photospan.id = 'photonumber';
			photospan.style.fontSize = '9pt';
			var newtext = document.createTextNode( newnumber + newnumbertext );
			var outerdiv = document.getElementById( 'smallheader' );
			var outerdivchilddiv = outerdiv.getElementsByTagName( 'div' );
			var thediv = outerdiv[ 1 ];
			var thedivchildsmall = thediv.getElementsByTagName( 'small' );
			var thesmall = thedivchildsmall[ 0 ];
			var thesmallchildspan = thesmall.getElementsByTagName( 'span' );
			var thespan = thesmallchildspan[ 3 ];
			if ( thespan.firstChild ) {
				thespan.insertBefore( photospan , thespan.firstChild );
				newtext += ',';
				photospan.insertBefore( newtext , thespan.firstChild );
			}
			else {
				thespan.appendChild( photospan );
				photospan.appendChild( newtext );
			}
		}
	}
	,
	UploadPhoto : function ( node ) {
		//parent = node.parentNode;
		//parent = document.getElementById( 'iesucks' );
		//alert( parent );
		//parent.submit();
		document.getElementById( 'iesucks' ).submit();
		//nodeineed = parent.parentNode.parentNode;
		var ajaxgif = document.createElement( 'img' );
		ajaxgif.src = 'http://static.chit-chat.gr/images/ajax-loader.gif';
		ajaxgif.alt = 'Παρακαλώ περιμένετε...';
		ajaxgif.title = 'Παρακαλώ περιμένετε...';
		var spantext = document.createElement( 'span' );
		spantext.appendChild( document.createTextNode( ' Παρακαλώ περιμένετε...' ) );
		//spantext.style.fontSize = '9pt;';
		var thediv = document.createElement( 'div' );
		thediv.appendChild( ajaxgif );
		thediv.appendChild( spantext );
		var nodeineed = document.getElementById( 'iesucks2' );
		nodeineed2 = nodeineed.parentNode;
		//alert( 'nodeineed2: ' + nodeineed2 );
		//nodeineed2 = top.document.getElementById( 'iesucks3' );
		//nodeineed2 is the parent of nodeineed
		//alert( 'ok till now' );
		nodeineed2.appendChild( thediv );
		//alert( 'child appended' );
		//alert( 'nodeineed2: ' + nodeineed2 + ' nodeineed: ' + nodeineed );
		nodeineed2.removeChild( nodeineed );
		//alert( 'all ok' );
	}
	,
	AddPhotoArticle : function ( imageid , userid ) {
		var abovenode = parent.document.getElementById( 'filmstrip' );
		
		var outerdiv = document.createElement( 'div' );
		var link = document.createElement( 'a' );
		link.onclick = (function( merlincode ) { 
				return function() { 
					NewArticle.Stag( merlincode ); 
					return false;
				}
		})( '[merlin:img ' + imageid + ']');
		link.alt = '';
		link.title = '';
		var theimage = document.createElement( 'img' );
		theimage.src = 'http://images.chit-chat.gr/' + userid + '/' + imageid + '?resolution=100x100';
		//theimage.src = 'image.php?id=' + imageid + '&thumb=yes';
		var thespan = document.createElement( 'span' );
		thespan.appendChild( document.createTextNode( imageid ) );
		link.appendChild( theimage );
		link.appendChild( thespan );
		outerdiv.appendChild( link );
		
		var frstchild = abovenode.firstChild;
		frstchild.insertBefore( outerdiv , frstchild.firstChild );
	}
}
