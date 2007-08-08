var Chat = {
	LastId: -1,
	GetNext: null,
	NumGots: 1,
	Arrange: function () {
		this.GetNext = setTimeout( 'Chat.Get()' , 5000 );
	},
	Get: function () {
		Coala.Cold( "chat/messages" , { 'lastid' : Chat.LastId } );
		if ( Chat.NumGots >= 0 ) {
			Chat.NumGots = -4;
			this.GetUsers();
		}
		this.Arrange();
	},
	GetUsers: function () {
		Coala.Cold( "chat/users" , {} );
	},
	Send: function ( message ) {
		Coala.Warm( "chat/send" , { 'message' : message } );
		this.Get();
	},
	Init: function ( lastid ) {
		this.LastId = lastid;
		Chat.Get();
	},
	AppendUsers: function ( users ) {
		ulist = g( 'userlist' );
		
		while ( ulist.firstChild ) {
			ulist.removeChild( ulist.firstChild );
		}
		headline = document.createElement( 'h3' );
		headline.appendChild( document.createTextNode( 'Συνομιλούν τώρα' ) );
		ulist.appendChild( headline );
		for ( i in users ) {
			thisjson = users[ i ];
			udiv = document.createElement( 'div' );
			ulink = document.createElement( 'a' );
			ulink.href = '?p=user&id=' + thisjson[ 'user_id' ];
			ulink.target = '_blank';
			ulink.className = Users.ClassName( thisjson[ 'user_rights' ] );
			/* if ( thisjson[ 'user_recentchanges' ] !== null ) {
				ulink.style.borderBottom = '1px dashed gray';
			} */
			avie = document.createElement( 'img' );
			avie.src = 'image.php?id=' + thisjson[ 'user_icon' ];
			avie.className = 'avatar';
			ulink.appendChild( avie );
			ulink.appendChild( document.createTextNode( thisjson[ 'user_name' ] ) );
			udiv.appendChild( ulink );
			ulist.appendChild( udiv );
		}
        g( "chatmessage" ).focus();
	},
	Append: function ( messages ) {
		++Chat.NumGots;
		messagesexist = false;
		for ( i in messages ) {
			thisjson = messages[ i ];
			if ( thisjson[ 'chat_id' ] > Chat.LastId ) {
				Chat.LastId = thisjson[ 'chat_id' ];
				message = document.createElement( 'div' );
				if ( thisjson[ 'user_id' ] !== null ) {
					messagesexist = true;
					userlink = document.createElement( 'a' );
					userlink.href = '?p=user&id=' + thisjson[ 'user_id' ];
					avatar = document.createElement( 'img' );
					avatar.src = 'image.php?id=' + thisjson[ 'user_icon' ];
					avatar.className = 'avatar';
					userlink.appendChild( avatar );
					userlink.appendChild( document.createTextNode( thisjson[ 'user_name' ] ) );
					prefix = document.createElement( 'span' );
					prefix.appendChild( userlink );
					prefix.appendChild( document.createTextNode( ' λέει:' ) );
					message.appendChild( prefix );
				}
				text = document.createElement( 'div' );
				text.style.display = 'inline';
				text.innerHTML = thisjson[ 'chat_message' ];
				message.appendChild( text );
				g( "chathistory" ).appendChild( message );
			}
		}
		if ( messagesexist ) {
			userlink.focus();
		}
        g( "chatmessage" ).focus();
	},
	Release: function() {
		this.Send( g( "chatmessage" ).value );
		g( "chatmessage" ).value = '';
	}
};

chatLastId = g( 'chat_last_id' ).childNodes[ 0 ].nodeValue - 30;
Chat.Init( chatLastId );