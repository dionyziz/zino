var pms = {
	unreadpms : 0,
	activefolder : 0,
	node : 0,
	activepm : 0,
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
		Coala.Cold( 'pm/showfolder' , { folderid : folderid } );
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
			Coala.Warm( 'pm/expandpm' , { pmdid : pmid } );
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
	CreateNewFolder : function ( formnode ) {
		//creating a new folder and showing it (using a coala call)
		var formnodeinput = formnode.getElementsByTagName( 'input' );
		inputbox = formnodeinput[ 0 ];
		var foldername = inputbox.value;
		var foo = foldername.replace(/(\s+$)|(^\s+)/g, '');
		if ( foldername == 'Εισερχόμενα' || foldername == 'Απεσταλμένα' ) {
			alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
			inputbox.select();
		}
		else if ( foldername.length <= 2 ) {
			alert( 'Το όνομα του  φακέλου πρέπει να έχει πάνω από 2 γράμματα' );
			inputbox.select();
		}
		else if ( foo === '' ) {
			alert( 'Το όνομα που επέλεξες δεν είναι έγκυρο' );
		}
		else {
			pms.ShowAnimation( 'Δημιουργία φακέλου...' );
			Coala.Warm( 'pm/makefolder' , { foldername : foldername } );
		}
	}
	,
	DeleteFolder : function( folderid ) {
		//the function for deleting a pm folder
		Modals.Confirm( 'Θέλεις σίγουρα να σβήσεις τον φάκελο;' , function () {
			Coala.Warm( 'pm/deletefolder' , { folderid : folderid } );
		} );
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
	DeletePm : function( msgnode , msgid ) {
		Modals.Confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' , function() {
			pms.activepms = 0;
			var msgnodedivs = msgnode.getElementsByTagName( 'div' );
			var msgnodeimgs = msgnode.getElementsByTagName( 'img' );
			var delimg = msgnodeimgs[ 0 ];
			var delimg2 = msgnodeimgs[ 1 ];
			var lowerdiv = msgnodedivs[ 6 ];
			lowerdiv.style.display = 'none';
			delimg.style.display = 'none';
			delimg2.style.display = 'none';
			msgnode.style.margin = '0px';
			Animations.Create( msgnode , 'opacity' , 2000 , 1 , 0 );
			Animations.Create( msgnode , 'height' , 3000 , msgnode.offsetHeight , 0 , function() {
					msgnode.parentNode.removeChild( msgnode );
			} );
			//check whether the msg is read or not, if it in unread only then execute the next function : TODO
			pms.UpdateUnreadPms( -1 );
			Coala.Warm( 'pm/deletepm' , { pmid : msgid } );
		} );
		
	},
	UpdateUnreadPms : function( specnumber ) {
		//reduces the number of unread messages by one
		//if specnumber is - 1 the unread pms number is reduced by one, else the specnumber is used as the number for the unread msgs
		
		var incomingdiv = document.getElementById( 'firstfolder' );
		var incominglink = incomingdiv.firstChild;
		var newtext;
		incominglink.removeChild( incominglink.firstChild );
		if ( unreadpms > 1 ) {
			if ( specnumber == -1 ) {
				--unreadpms;
				newtext = document.createTextNode( 'Εισερχόμενα (' + unreadpms + ')' );
			}
			else {
				newtext = document.createTextNode( 'Εισερχόμενα (' + specnumber + ')' );
			}
		}
		else {
			newtext = document.createTextNode( 'Εισερχόμενα' );
		}
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
	}
};