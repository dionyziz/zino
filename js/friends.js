var Friends = {
	AddFriend: function( friendid ) {
		Coala.Warm( 'users/addfriend' , { 'friendid' : friendid } );
	},
    FriendAdded: function ( userid, friendid, avatar, rank, hobbies ) {
        addlink = document.getElementById( 'friendadd' );
        while ( addlink.firstChild ) {
            addlink.removeChild( addlink.firstChild );
        }
        thelink = document.createElement( 'a' );
        thelink.href = '';
        thelink.onclick = function () {
            Friends.DeleteFriend( friendid );
            return false;
        };
        delicon = document.createElement( 'img' );
        delicon.src = 'http://static.chit-chat.gr/images/icons/user_delete.png';
        delicon.alt = delicon.title = 'Διαγραφή από τους φίλους μου';
        thelink.appendChild( delicon );
        addlink.appendChild( thelink );
        newfan = document.getElementById( 'newfan' );
        newfan.id = "fan_" + userid;
        content = document.getElementById( 'newfancontent' );
        while ( content.firstChild ) {
            content.removeChild( content.firstChild );
        }
        content.appendChild( document.createElement( 'br' ) );
        avie = document.createElement( 'span' );
        avie.innerHTML = avatar; // duh
        content.appendChild( avie );
        content.appendChild( document.createElement( 'br' ) );
        role = document.createElement( 'b' );
        role.appendChild( document.createTextNode( 'Ρόλος: ' ) );
        content.appendChild( role );
        content.appendChild( document.createTextNode( rank ) );
        content.appendChild( document.createElement( 'br' ) );
        if ( hobbies != '' ) {
            interests = document.createElement( 'b' );
            interests.appendChild( document.createTextNode( 'Ενδιαφέροντα: ' ) );
            content.appendChild( interests );
            content.appendChild( document.createTextNode( hobbies ) );
            content.appendChild( document.createElement( 'br' ) );
        }
        newfan.style.display = 'block';
    }
	,
	DeleteFriend: function( friendid ) {
		Coala.Warm( 'users/deletefriend' , { 'friendid' : friendid } );
	}
	,
	ProfileDeleteFriendCallback: function( friendid , userid ) {
		addlink = document.getElementById( 'friendadd' );
		while ( addlink.firstChild ) {
			addlink.removeChild( addlink.firstChild );
		}
		thelink = document.createElement( 'a' );
		thelink.href = '';
		thelink.onclick = function () {
			Friends.AddFriend( friendid );
			return false;
		};
		addicon = document.createElement( 'img' );
		addicon.src = 'http://static.chit-chat.gr/images/icons/user_add.png';
		addicon.alt = addicon.title = "Προσθήκη στους φίλους μου";
		thelink.appendChild( addicon );
		addlink.appendChild( thelink );
		div = document.getElementById( 'newfancontent' );
		div.innerHTML = "";
		
		outerdiv = document.getElementById( 'fan_' + userid );
		outerdiv.id = 'newfan';
		outerdiv.style.display = "none";
	}
	,
	DeleteFriendCallback: function( friendid, userid ) {
		alert( 'deletefriendcallback' );
	}    
}
