var pms = {
	unreadpms : 0,
	activefolder : 0,
	node : 0,
	activepm : 0,
	pmsinfolder : 0,
	messagescontainer : document.getElementById( 'messages' ),
	writingnewpm : false,
	ShowFolder : function( folder , folderid ) {
		if ( pms.activefolder === 0 ) {
			pms.node = document.getElementById( 'firstfolder' );
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'folder top';
		}
		else {
			pms.activefolder.className = 'folder';
		}
		if ( folder != pms.node ) {
			folder.className = 'activefolder top';
		}
		else {
			folder.className = 'activefolder';
		}
		pms.activefolder = folder;
		Coala.Cold( 'pm/showfolder' , { folderid : folderid } , function( errcode ) {
			alert( 'Coala error: ' + errcode );
		} );	
	}
	,
	ShowFolderPm : function( folder , folderid ) {
		//this function uses the ShowFolder function to show the contents of a folder using a little animation
		pms.activepm = 0;
		pms.writingnewpm = false;
		pms.ShowAnimation( 'Παρακαλώ περιμένετε...' );
		pms.ShowFolder( folder , folderid );
	}
	,
	ExpandPm : function( pmdiv , notread , pmid ) {
		//the function is responsible for expanding and minimizing pms, allowing only one expanded pm
		//notread is true when the pm hasn't been read else it is true
		var messagesdivdivs = pmdiv.parentNode.parentNode.getElementsByTagName( 'div' );
		var textpm = messagesdivdivs[ 4 ];
		var lowerlinepm = messagesdivdivs[ 6 ];
		if ( pms.activepm !== 0 ) {
			//minimizing previous pm
			var activepmdivdivs = pms.activepm.parentNode.parentNode.getElementsByTagName( 'div' );
			var acttextpm = activepmdivdivs[ 4 ];
			var actlowerlinepm = activepmdivdivs[ 6 ];
			acttextpm.style.display = 'none';
			actlowerlinepm.style.display = 'none';
		}	
		if ( textpm.style.display == 'none' ) {
			textpm.style.display = 'block';
			lowerlinepm.style.display = 'block';
		}
		else {
			textpm.style.display = 'none';
			lowerlinepm.style.display = 'none';
		}
		pms.activepm = pmdiv;
		if ( notread ) {
			//remove the unread icon
			var infobaricons = messagesdivdivs[ 0 ].getElementsByTagName( 'img' );
			var unreadicon = infobaricons[ 1 ];
			if ( unreadicon ) {
				unreadicon.style.opacity = '1';
				unreadicon.style.padding = '0px';
				pms.UpdateUnreadPms( - 1 );
				Animations.Create( unreadicon , 'opacity' , 2000 , 1 , 0 , function() {
						unreadicon.parentNode.removeChild( unreadicon );
					} );
				Animations.Create( unreadicon , 'width' , 1500 , unreadicon.offsetWidth , 0 );
				//mark the pm as read in the database through a coala call
				Coala.Warm( 'pm/expandpm' , { pmid : pmid } );
			}
		}
	}
	,
	NewFolder : function() {
		//showing modal dialog for new folder name
		var newfolderdiv = document.getElementById( 'newfolderlink' );
		var newfoldermodal = document.getElementById( 'newfoldermodal' ).cloneNode( true );
		newfoldermodal.style.display = '';
		newfoldermodalinput = newfoldermodal.getElementsByTagName( 'input' );
		textbox = newfoldermodalinput[ 0 ];
		Modals.Create( newfoldermodal , 250 , 80 );
		textbox.focus();
		textbox.select();
		newfolderdiv.style.backgroundColor = '#e1e9f2';
		var newfolderdivlinks = newfolderdiv.getElementsByTagName( 'a' );
		var newfolderlink = newfolderdivlinks[ 0 ];
		newfolderlink.style.color = '#aaa8a8';
		newfolderlink.style.fontWeight = 'bold';
		if ( pms.activefolder === 0 ) {
			pms.node = document.getElementById( 'firstfolder' );
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'folder top';
		}
		else {
			pms.activefolder.className = 'folder';
		}
	}
	,
	CancelNewFolder : function () {
		if ( pms.activefolder === 0 ) {
			pms.node = document.getElementById( 'firstfolder' );
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'activefolder top';
		}
		else {
			pms.activefolder.className = 'activefolder';
		}
		var newfolderdiv = document.getElementById( 'newfolderlink' );
		newfolderdiv.style.backgroundColor = '#ffffff';
		var newfolderdivlinks = newfolderdiv.getElementsByTagName( 'a' );
		var newfolderlink = newfolderdivlinks[ 0 ];
		newfolderlink.style.color = '#d0cfcf';
		newfolderlink.style.fontWeight = '';
		Modals.Destroy();
	}
	,
    ValidFolderName : function ( name ) {
		var name = name.replace(/(\s+$)|(^\s+)/g, '');
		if ( name == 'Εισερχόμενα' || name == 'Απεσταλμένα' ) {
            return false;
		}
		else if ( name.length <= 2 ) {
            return false;
		}
		else if ( name === '' ) {
            return false;
		}
        return true;
    }
    ,
	CreateNewFolder : function ( formnode ) {
		//creating a new folder and showing it (using a coala call)
		var formnodeinput = formnode.getElementsByTagName( 'input' );
		inputbox = formnodeinput[ 0 ];
		var foldername = inputbox.value;
		if ( !pms.ValidFolderName( foldername ) ) {
			alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
			inputbox.select();
            return;
		}
        pms.ShowAnimation( 'Δημιουργία φακέλου...' );
        Coala.Warm( 'pm/makefolder' , { foldername : foldername } );
	}
	,
	DeleteFolder : function( folderid ) {
		//the function for deleting a pm folder
		Modals.Confirm( 'Θέλεις σίγουρα να σβήσεις τον φάκελο;' , function () {
			Coala.Warm( 'pm/deletefolder' , { folderid : folderid } );
		} );
	}
	,
    RenameFolder : function ( folderid ) {
        var name = prompt( 'Πληκτρολόγησε ένα νέο όνομα για τον φάκελό σου' );
        
        if ( name === null ) {
            return;
        }
        if ( !pms.ValidFolderName( name ) ) {
            alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
            return;
        }
        Coala.Warm( 'pm/folder/rename', {
            'folderid': folderid,
            'newname': name
        } );
        document.getElementById( 'folder_' + folderid ).getElementsByTagName( 'a' )[ 0 ].firstChild.nodeValue = name;
    }
    ,
	NewMessage : function( touser , answertext ) {
		pms.ClearMessages();
		var receiversdiv = document.createElement( 'div' );
		
		var receiversinput = document.createElement( 'input' );
		receiversinput.type = 'text';
		receiversinput.style.width = '250px';
		receiversinput.style.color = '#9d9d9d';
		if ( touser !== '' ) {
			receiversinput.value = touser;
		}
		pms.messagescontainer.appendChild( receiversdiv );
		
		if ( answertext !== '' ) {
			var textmargin = document.createElement( 'div' );
			textmargin.style.border = '1px dotted #b9b8b8';
			textmargin.style.padding = '4px';
			textmargin.style.color = '#767676';
			textmargin.style.width = '550px';
			textmargin.appendChild( document.createTextNode( answertext ) );
			pms.messagescontainer.appendChild( textmargin );
			pms.messagescontainer.appendChild( document.createElement( 'br' ) );
			pms.messagescontainer.appendChild( document.createElement( 'br' ) );
		}
		
		var receiverstext = document.createElement( 'span' );
		receiverstext.style.paddingRight = '30px';
		receiverstext.appendChild( document.createTextNode( 'Παραλήπτες' ) );
		receiverstext.style.fontWeight = 'bold';
		receiversdiv.appendChild( receiverstext );
		receiversdiv.appendChild( receiversinput );
		receiversdiv.appendChild( document.createElement( 'br' ) );
		receiversdiv.appendChild( document.createElement( 'br' ) );
		
		var pmtext = document.createElement( 'textarea' );
		pmtext.style.width = '550px';
		pmtext.style.height = '300px';
		
		var sendbutton = document.createElement( 'input' );
		sendbutton.type = 'button';
		sendbutton.value = 'Αποστολή';
		sendbutton.onclick = ( function() {
			return function() {
				pms.SendPm();
			};
		})();
		
		var cancelbutton = document.createElement( 'input' );
		cancelbutton.type = 'button';
		cancelbutton.value = 'Επαναφορά';
		cancelbutton.onclick = ( function() {
			return function() {
				receiversinput.value = '';
				pmtext.value = '';
			};
		})();
		var actions = document.createElement( 'div' );
		actions.appendChild( sendbutton );
		actions.appendChild( cancelbutton );
		
		pms.messagescontainer.appendChild( pmtext );
		pms.messagescontainer.appendChild( document.createElement( 'br' ) );
		pms.messagescontainer.appendChild( document.createElement( 'br' ) );
		pms.messagescontainer.appendChild( actions );
		pms.ShowFolderNameTop( 'Νέο μήνυμα' );
		receiversinput.focus();
		receiversinput.select();
		pms.writingnewpm = true;
	}
	,
	SendPm : function() {
		//responsible for sending the pm to the specified user or users
		var messagesdivinputlist = pms.messagescontainer.getElementsByTagName( 'input' );
		var receiverslist = messagesdivinputlist[ 0 ];
		var messagesdivtextarealist = pms.messagescontainer.getElementsByTagName( 'textarea' );
		var pmtext = messagesdivtextarealist[ 0 ];
		pms.ShowAnimation( 'Αποστολή μηνύματος...' );
		Coala.Warm( 'pm/sendpm' , { usernames : receiverslist.value , pmtext : pmtext.value } );
	}
	,
	DeletePm : function( msgnode , msgid , read ) {
		Modals.Confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' , function() {
			pms.activepms = 0;
			var msgnodedivs = msgnode.getElementsByTagName( 'div' );
			var msgnodeimgs = msgnode.getElementsByTagName( 'img' );
			var delimg = msgnodeimgs[ 0 ];
			var delimg2 = msgnodeimgs[ 1 ];
			var lowerdiv = msgnodedivs[ 6 ];
			lowerdiv.style.display = 'none';
			delimg.style.display = 'none';
			if ( delimg2 ) {
				//if the message is already read there is no such image
				delimg2.style.display = 'none';
			}
			msgnode.style.margin = '0px';
			Animations.Create( msgnode , 'opacity' , 2000 , 1 , 0 );
			Animations.Create( msgnode , 'height' , 3000 , msgnode.offsetHeight , 0 , function() {
					msgnode.parentNode.removeChild( msgnode );
			} );
			//check whether the msg is read or not, if it in unread only then execute the next function : TODO
			if ( !read ) {
				pms.UpdateUnreadPms( -1 );
			}
			pms.pmsinfolder--;
			pms.WriteNoPms();
			Coala.Warm( 'pm/deletepm' , { pmid : msgid } );
		} );
		
	},
	DragPm : function( pmid ) {
		var pmdiv = document.getElementById( pmid );
		var dd1 = new YAHOO.util.DDProxy( pmdiv );
		var Dom = YAHOO.util.Dom;
		
		var startPos = Dom.getXY( pmdiv );
		YAHOO.util.DragDropMgr.clickTimeThresh = 5000; 
		YAHOO.util.DragDropMgr.clickPixelThresh = 10;
		YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
		dd1.onDragEnter = function( e , id ) {
			var targetdiv = document.getElementById( id );
			targetdiv.className = 'hoverfolder top';
		};
		dd1.onDragOut = function( e , id ) {
			var targetdiv = document.getElementById( id );
			targetdiv.className = 'folder top';
		};
		dd1.onDragDrop = function( e, id ) {
			alert( 'onDragDrop called' );
			var p = this.getDragEl();
			var realpm = document.getElementById( pmid );
			
			p.style.display = 'none'
			var msgnodedivs = realpm.getElementsByTagName( 'div' );
			var msgnodeimgs = realpm.getElementsByTagName( 'img' );
			var delimg = msgnodeimgs[ 0 ];
			var delimg2 = msgnodeimgs[ 1 ];
			var lowerdiv = msgnodedivs[ 6 ];
			var targetdiv = document.getElementById( id );
			targetdiv.className = 'folder top';
			lowerdiv.style.display = 'none';
			delimg.style.display = 'none';
			if ( delimg2 ) {
				//if the message is already read there is no such image
				delimg2.style.display = 'none';
			}
			realpm.style.margin = '0px';
			Animations.Create( realpm , 'opacity' , 2000 , 1 , 0 , function() {
				p.style.display = '';
				pms.WriteNoPms();
			} );
			pms.pmsinfolder--;
			Animations.Create( realpm , 'height' , 3000 , realpm.offsetHeight , 0 , function() {
					realpm.parentNode.removeChild( realpm );
			} );
			Coala.Warm( 'pm/transfer' , { pmid : pmid.substring( 3 ) , folderid : id.substring( 7 ) } );
			//take the last part of the string "folder_id"
        };
		dd1.startDrag = function() {
			var p = this.getDragEl();
			var q = this.getEl();
			p.style.border = '1px dotted #92bcc1;'
		};
		dd1.endDrag = function( e ) {
			alert( 'end of drag' );
			var p = this.getDragEl();
            var q = this.getEl();
            
            proxypos = Dom.getXY( p );
            srcpos = Dom.getXY( q );

            p.style.visibility = '';
            
            Animations.Create( p, 'left', 1000, proxypos[ 0 ], srcpos[ 0 ] );
            Animations.Create( p, 'top', 1000, proxypos[ 1 ], srcpos[ 1 ], function () {
                p.style.visibility = 'hidden';
            } );
		};
	},
	DragPm2 : function() {
		var divlist = pms.messagescontainer.getElementsByTagName( 'div' );
		for ( var i = 0; i < divlist.length; i++ ) {
			if ( divlist[ i ].className == 'message' ) {
				var pmdiv = divlist[ i ];
				var pmid = pmdiv.id.substring( 3 );
				var dd1 = new YAHOO.util.DDProxy( pmdiv );
				var Dom = YAHOO.util.Dom;
				
				var startPos = Dom.getXY( pmdiv );
				YAHOO.util.DragDropMgr.clickTimeThresh = 5000; 
				YAHOO.util.DragDropMgr.clickPixelThresh = 10;
				YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
				dd1.onDragEnter = function( e , id ) {
					var targetdiv = document.getElementById( id );
					targetdiv.className = 'hoverfolder top';
				};
				dd1.onDragOut = function( e , id ) {
					var targetdiv = document.getElementById( id );
					targetdiv.className = 'folder top';
				};
				dd1.onDragDrop = function( e, id ) {
					//alert( 'onDragDrop called' );
					var p = this.getDragEl();
					var q = this.getEl();
					//var realpm = document.getElementById( pmid );
					
					p.style.display = 'none'
					var msgnodedivs = q.getElementsByTagName( 'div' );
					var msgnodeimgs = q.getElementsByTagName( 'img' );
					var delimg = msgnodeimgs[ 0 ];
					var delimg2 = msgnodeimgs[ 1 ];
					var lowerdiv = msgnodedivs[ 6 ];
					var targetdiv = document.getElementById( id );
					targetdiv.className = 'folder top';
					lowerdiv.style.display = 'none';
					delimg.style.display = 'none';
					if ( delimg2 ) {
						//if the message is already read there is no such image
						delimg2.style.display = 'none';
					}
					q.style.margin = '0px';
					Animations.Create( q , 'opacity' , 2000 , 1 , 0 , function() {
						p.style.display = '';
						pms.WriteNoPms();
					} );
					pms.pmsinfolder--;
					Animations.Create( q , 'height' , 3000 , q.offsetHeight , 0 , function() {
							q.parentNode.removeChild( q );
					} );
					//alert( 'pmid coala call is: ' + pmid + ' folderid is ' + id.substring( 7 ) );
					Coala.Warm( 'pm/transfer' , { pmid : q.id.substring( 3 ) , folderid : id.substring( 7 ) } );
					//take the last part of the string "folder_id"
		        };
				dd1.startDrag = function() {
					var p = this.getDragEl();
					var q = this.getEl();
					alert( p );
					//p.style.border = '1px dotted #92bcc1;'
				};
				dd1.endDrag = function( e ) {
					//alert( 'end of drag' );
					var p = this.getDragEl();
		            var q = this.getEl();
		            
		            proxypos = Dom.getXY( p );
		            srcpos = Dom.getXY( q );

		            p.style.visibility = '';
		            
		            Animations.Create( p, 'left', 1000, proxypos[ 0 ], srcpos[ 0 ] );
		            Animations.Create( p, 'top', 1000, proxypos[ 1 ], srcpos[ 1 ], function () {
		                p.style.visibility = 'hidden';
		            } );
				};
			}	
		}
	},
	UpdateUnreadPms : function( specnumber ) {
		//reduces the number of unread messages by one
		//if specnumber is - 1 the unread pms number is reduced by one, else the specnumber is used as the number for the unread msgs
		var unreadmsgbanner = document.getElementById( 'messagesunread' );
		var incomingdiv = document.getElementById( 'firstfolder' );
		var incominglink = incomingdiv.firstChild;
		var newtext;
		var newtext2;
		incominglink.removeChild( incominglink.firstChild );
		unreadmsgbanner.removeChild( unreadmsgbanner.firstChild );
		if ( unreadpms > 1 ) {
			if ( specnumber == -1 ) {
				--unreadpms;
				newtext = document.createTextNode( 'Εισερχόμενα (' + unreadpms + ')' );
				if ( unreadpms == 1 ) {
					newtext2 = document.createTextNode( '1 Νέο Μήνυμα' );
				}
				else {
					newtext2 = document.createTextNode( unreadpms + ' Νέα Μηνύματα' );
				}
			}
			else {
				newtext = document.createTextNode( 'Εισερχόμενα (' + specnumber + ')' );
				newtext2 = document.createTextNode( specnumber + ' Νέα Μηνύματα' );
			}
		}
		else {
			newtext2 = document.createElement( 'img' );
			newtext2.src = 'http://static.chit-chat.gr/images/icons/email.png';
			newtext2.alt = 'Μηνύματα';
			newtext2.style.width = '16px';
			newtext2.style.height = '16px';
			newtext2.style.verticalAlign = 'bottom';
			newtext = document.createTextNode( 'Εισερχόμενα' );
		}
		unreadmsgbanner.appendChild( newtext2 );
		incominglink.appendChild( newtext );
	}
	,
	ShowFolderNameTop : function( texttoshow ) {
		//showing the name of the folder in the right upper corner
		var messagesdivparent = pms.messagescontainer.parentNode.parentNode;
		var messagesdivdiv = messagesdivparent.getElementsByTagName( 'div' );
		var foldertext = messagesdivdiv[ 1 ];
		foldertext.removeChild( foldertext.firstChild );
		foldertext.appendChild( document.createTextNode( texttoshow ) );
	}
	,
	ShowAnimation : function( texttoshow ) {
		pms.ClearMessages();
		var loadinggif = document.createElement( 'img' );
		loadinggif.src = 'http://static.chit-chat.gr/images/ajax-loader.gif';
		loadinggif.alt = texttoshow;
		loadinggif.title = texttoshow;
		var loadingtext = document.createTextNode( ' ' + texttoshow );
		pms.messagescontainer.appendChild( loadinggif );
		pms.messagescontainer.appendChild( loadingtext );
	}
	,
	ClearMessages : function() {
		//clears the area where pms appear
		while ( pms.messagescontainer.firstChild ) {
			pms.messagescontainer.removeChild( pms.messagescontainer.firstChild );
		}
	},
	WriteNoPms : function() {
		var messagescontainerdivlist = pms.messagescontainer.getElementsByTagName( 'div' );
		if ( messagescontainerdivlist.length / 12 == 1 ) {
			//var messagescontainer = document.getElementById( 'messages' );
			nopmsspan = document.createElement( 'span' );
			nopmsspan.appendChild( document.createTextNode( 'Δεν υπάρχουν μηνύματα σε αυτόν τον φάκελο' ) );
			nopmsspan.style.opacity = '0';
			pms.messagescontainer.appendChild( nopmsspan );
			Animations.Create( nopmsspan , 'opacity' , 3000 , 0 , 1 );
		}
	}
};