var Comments = {
	onedit : [],
	Reply : function( nodeid, indent ) {
		var sibling = g( 'comment_' + nodeid ).nextSibling; 
		while( sibling ) { // handle DOM whitespace
			if ( sibling.nodeName == "#text" ) {
				sibling = sibling.nextSibling;
			}
			else {
				break;
			}
		}
		if ( !sibling || sibling.id.substring( 0, "comment_reply_".length ) != "comment_reply_" ) { // show reply-comment only once for every comment.
			var comment = document.getElementById( 'comment_new' ).cloneNode( true );
			comment.style.marginLeft = (indent + 1) * 10 + 'px';
			comment.id = "comment_reply_" + nodeid;
			comment.onsubmit = function () {
					Comments.Wait( false, nodeid );
					Coala.Warm( 'comments/new', { 'text' : comment.getElementsByTagName( 'textarea' )[0].value, 'parent' : nodeid, 'compage' : g( 'compage' ).value, 'type' : g( 'type' ).value, 'indent' : indent+1, 'callback' : Comments.NewCommentCallback } );
					return false;
				};
			
			comment.getElementsByTagName( 'li' )[ 0 ].style.display = 'block';
			
			comment = document.getElementById( 'comments' ).insertBefore( comment, document.getElementById( 'comment_' + nodeid).nextSibling );
			comment.getElementsByTagName( 'textarea' )[ 0 ].focus();
		}
		Comments.hideDeleteButton( nodeid, false );
	},
	Edit : function( id ) {
		if ( Comments.onedit[ id ] === true ) {
			return;
		}
		else if ( Comments.onedit[ id ] === false ) {
			Comments.EditCallback( id, "" );
			return;
		}
		
		document.body.style.cursor = "wait";
		
		element = g( 'comment_' + id + '_toolbar' );
		for( i in element.childNodes ) {
			child = element.childNodes[ i ];
			if( child.nodeType == 1 ) {
				child.style.display = 'none';
			}
		}
		
		loading = document.createElement( 'span' );
		loading.id = 'loading';
		loading.appendChild( document.createTextNode( 'Προετοιμασία επεξεργασίας...' ) );
		
		element.appendChild( loading );
		
		Coala.Cold( 'comments/text', {
            "commentid": id,
            "callback": Comments.EditCallback
        } );
	},
	EditCallback : function( id, text ) {
		document.body.style.cursor = "default";
		if( Comments.onedit[ id ] === null || Comments.onedit[ id ] === undefined ) {
			g( 'comment_text_' + id ).style.display = 'none';
			
			var editid = document.createElement( 'input' );
			editid.type = 'hidden';
			editid.name = 'eid';
			editid.value = id;
			
			var editarea = document.createElement( 'textarea' );
			editarea.name = 'text';
			editarea.appendChild( document.createTextNode( text ) );
			editarea.style.width = '95%';
			editarea.style.height = '100px';
			editarea.id = 'select_comment_' + id;
			
			var editform = document.createElement( 'form' );
			editform.id = 'comment_editform_' + id;
			editform.onsubmit = function () {
							Coala.Warm( 'comments/edit', { 'eid':editid.value, 'text':editarea.value, 'callback' : Comments.replaceText } );
						var freplace = g( 'comment_text_' + editid.value );
						while( freplace.childNodes.length != 4 ) {
							freplace.removeChild( freplace.firstChild );
						}
						
						var temp = document.createElement( 'div' );
						temp.style.opacity = '0.5';
						temp.appendChild(document.createTextNode( "Αποθήκευση..." ) );
						freplace.insertBefore( temp, freplace.firstChild );
						
						Comments.cancelEdit( id );
						return false;
						};
			editform.style.marginLeft = '10px';
			
			editform.appendChild( document.createElement( 'br' ) );
			editform.appendChild( editid );
			editform.appendChild( editarea );
			editform.appendChild( document.createElement( 'br' ) );
			editform.appendChild( document.createElement( 'br' ) );
			
			g( 'comment_' + id + '_toolbar' ).style.display = 'none';
			g( 'comment_edit_' + id + '_toolbar' ).style.display = '';
			editform = g( 'comment_text_' + id).parentNode.insertBefore( editform, g( 'comment_text_' + id ).nextSibling );
			
			Comments.onedit[ id ] = true;
			
			g( 'select_comment_' + id ).focus();
			g( 'select_comment_' + id ).select();
		}
		else if ( Comments.onedit[ id ] === false ) { // has previously canceled edit
			g( 'comment_text_' + id ).style.display = 'none';
			g( 'comment_editform_' + id ).style.display = 'block';
			
			g( 'comment_' + id + '_toolbar' ).style.display = 'none';
			g( 'comment_edit_' + id + '_toolbar' ).style.display = '';
			Comments.onedit[ id ] = 2;
		}		
	},
	replaceText : function( id, text, sig ) {
		var textn = g( 'comment_text_' + id );
		textn.innerHTML = text;
		for(var i=0;i<3;++i) {
			textn.appendChild( document.createElement( 'br' ) );
		}
		var divine = document.createElement( 'div' );
		divine.setAttribute( 'class', 'sig' ); // We are forced to use the setAttribute method,since 'class' is a JS keyword
		
		divine.appendChild( document.createTextNode( sig ) );
		divine.appendChild( document.createElement( 'br' ) );
		divine.appendChild( document.createElement( 'br' ) );
		textn.appendChild( divine );
	},
	checkEmpty : function( id ) {
		if( g( 'select_comment_' + id ).value === '' ) {
			alert( 'Δεν μπορείς να δημοσιεύσεις κενό σχόλιο' );
		}
		else {
			g( 'comment_editform_' + id).onsubmit();
		}
	},
	cancelEdit : function( id ) {
		g( 'comment_editform_' + id ).style.display = 'none';
		g( 'comment_text_' + id ).style.display = 'block';
			
		g( 'comment_edit_' + id + '_toolbar' ).style.display = 'none';
		
		g( 'comment_' + id + '_toolbar' ).style.display = 'block';
		
		element = g( 'comment_' + id + '_toolbar' );
		
		if ( Comments.onedit[ id ] === true ) {
			for ( i in element.childNodes ) {
				child = element.childNodes[ i ];
				if( child.nodeType == 1 ) {
					if ( child.id == 'loading' ) {
						element.removeChild( child );
					}
					else {
						child.style.display = '';
					}
				}
			}
		}
		Comments.onedit[ id ] = false;
	},
	cancelReply : function( comment ) {
		comment.parentNode.removeChild( comment );
	},
	DeleteModal : function( id ) {
        Modals.Confirm( 
            'Θέλεις σίγουρα να διαγράψεις το συγκεκριμένο σχόλιο;',
            function () { 
                Comments.DeleteReal( id );
            }
        );
	},
    Delete: function ( id ) {
        element = document.getElementById( 'comment_' + id );
        element.style.display = 'none';
        
        loading = document.createElement( 'div' );
		loading.style.width = '100%';
		loading.style.textAlign = 'center';
		loading.style.paddingBottom = '5px';
		loading.id = 'comment_loading_delete_' + id;
        loading.appendChild( document.createTextNode( 'Διαγραφή...' ) );
        
        element.parentNode.insertBefore( loading, element.nextSibling );
		
        Coala.Warm( 'comments/delete' , { 'commentid': id } );
        
        Comments.DisplayComments( false );
    },
	UndoDelete: function ( id ) {
		Coala.Warm( 'comments/undodelete', { 'commentid' : id } );
		Comments.DisplayComments( true );
	},
	MarkAsSpam : function( id ) {
		Modals.Confirm( 
            "Θέλεις σίγουρα να σημειώσεις το συγκεκριμένο σχόλιο ως spam;",
            function () { 
                Coala.Warm( 'comments/spam' , { 'commentid': id } );
            }
        );
	},
	NewCommentCallback : function ( newcomm, parent, type ) {
		var comments = g( 'comments' );
		var temp = d.createElement( 'div' );
		temp.innerHTML = newcomm;
		
		if ( parent === 0 ) { // New Comment
			g( 'comment_new' ).getElementsByTagName( 'textarea' )[0].value='';
			if ( type != 0 ) {
				comments.insertBefore( temp.firstChild, comments.childNodes[1] );
			}
			else {
				comments.insertBefore( temp.firstChild, comments.childNodes[ comments.childNodes.length-2 ] );
			}
		}
		else { // Reply
			g( 'comments' ).insertBefore( temp.firstChild, g( 'comment_' + parent ).nextSibling );
			g( 'comments' ).removeChild( g( 'comment_reply_' + parent ) );
			
			var buttons = g( 'comment_' + parent + '_toolbar' ); // Removing the Delete Button from the parent
			if ( buttons.childNodes.length == 3 ) { 
				buttons.removeChild( buttons.childNodes[2] );
			}
		}
		Comments.DisplayComments( true );
		Comments.Wait( true, parent );
	},
	DisplayComments : function ( rise ) { // rise determines whether the number of comments will increase or decrease
		if ( window.location.href.indexOf( "/user/" ) != -1 ) { 
			var numcomments = parseInt( g( 'user_statistics_profcomms' ).firstChild.nodeValue );
			numcomments += (rise)?1:-1;
			g( 'user_statistics_profcomms' ).firstChild.nodeValue = numcomments;
		}
		else if ( window.location.href.indexOf( "=photo" ) != -1 || window.location.href.indexOf( "=story" ) != -1 ) {
			if( g('numcomments').childNodes.length != 0 ) { // If the element displayed the comments thing
				var numcomments = g('numcomments').firstChild.nodeValue;
				var minus = ( g('numcomments').childNodes.length == 1 )?9:8; // if there is a "Show All" link,the comma is placed after it,therefore the string is shorter
				numcomments = numcomments.substring( 0, numcomments.length-minus );
			}
			else {
				var numcomments = '';
				var minus = 9;
				g('numcomments').appendChild( d.createTextNode( '' ) );
			}
			numcomments = (numcomments=='')?0:parseInt( numcomments );
			numcomments += (rise)?1:-1;
			g('numcomments').firstChild.nodeValue = numcomments;
			g('numcomments').firstChild.nodeValue += (numcomments==1)?" σχόλιο":" σχόλια";
			g('numcomments').firstChild.nodeValue += (minus==9)?", ":" ";
		}
	},
	Wait : function ( un, parent ) {
		document.body.style.cursor = (un)?"default":"wait";
		
		if( parent != 0 ) {
			if( un ) {
				return;
			}
			var element = g('comment_reply_' + parent );
		}
		else {
			var element = g('comment_new');
		}
		
		for ( var i in element.childNodes ) {
    		var child = element.childNodes[ i ];
			if ( child.nodeType == 1 ) {
				child.style.display = (un)?'':'none';
			}
		}
		
		if ( !un ) {
			var loading = d.createElement( 'span' );
			loading.id = "loading";
			loading.style.opacity = "0.5";
			loading.appendChild( d.createElement( 'br' ) );
			loading.appendChild( d.createElement( 'br' ) );
			loading.appendChild( d.createTextNode( "Αποθήκευση.." ) );
			loading.appendChild( d.createElement( 'br' ) );
			loading.appendChild( d.createElement( 'br' ) );
			
			element.appendChild( loading );
			return;
		}
		element.removeChild( g('loading' ) );
	},
	hideDeleteButton : function( daddy, dec ) {
		var numcom = g( daddy + "_children" ).firstChild;
		var num = parseInt( numcom.nodeValue );
		if( dec ) {
			++num;
			numcom.nodeValue = num;
		}

		if( num == 1 || num == 0 ) {
			var toolbar = g( 'comment_' + daddy + '_toolbar' );
			alert( toolbar.childNodes.length;
			for( var i in toolbar.childNodes ) {
				alert( i );
				if( toolbar.childNodes[i].firstChild.firstChild.nodeValue == "Διαγραφή" ) {
					toolbar.removeChild( toolbar.childNodes[i] );
					break;
				}
			}
		}
	},
	showDeleteButton : function( daddy ) {
		var numcom = g( daddy + "_children" ).firstChild;
		var num = parseInt( numcom.nodeValue );
		--num;
		numcom.nodeValue = num;
		
		if( num == 0 ) {
			var lili = d.createElement( 'li' );
			var link = d.createElement( 'a' );
			link.style.cursor = "pointer";
			link.onclick = function() {
					Comments.Delete( daddy );
					return false;
				};
			
			link.appendChild( d.createTextNode( "Διαγραφή" ) );
			lili.appendChild( link );
			
			var toolbar = g( 'comment_' + daddy + '_toolbar' );
			if( toolbar.childNodes.length == 3 ) {
				toolbar.insertBefore( lili, toolbar.childNodes[2] );
			}
			else {
				toolbar.appendChild( lili );
			}
		}
	}
};
