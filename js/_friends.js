document.getElementsByTagName( 'body' )[0].ondblclick= function() { 
						Friends.ShowAll(false);
						return false;
					};
var Friends = {
	onappear : false,
	processing : false,
	AddFriend : function ( friendid, type ) {
		Friends.processing = true;
		if ( type != -1 ) {
			Coala.Warm( 'users/addfriend' , { 'friendid' : friendid, 'friendtype' : type } );
		}
		else if ( type == -1 ) {
			Coala.Warm( 'users/deletefriend' , { 'friendid' : friendid } );
		}
	},
	FriendAdded : function ( userid, friendid, avatar, rank, hobbies, friendtype ) {
		if ( avatar !== '' || rank !== '' || hobbies !== '' ) { // If the user didn't have a previous relation with you
			var newfan = document.getElementById( 'newfan' );
	        newfan.id = "fan_" + userid;
	        var content = document.getElementById( 'newfancontent' );
	        while ( content.firstChild ) {
	            content.removeChild( content.firstChild );
	        }
	        content.appendChild( document.createElement( 'br' ) );
	        var avie = document.createElement( 'span' );
	        avie.innerHTML = avatar; // duh
	        content.appendChild( avie );
	        content.appendChild( document.createElement( 'br' ) );
	        var role = document.createElement( 'b' );
	        role.appendChild( document.createTextNode( 'Ρόλος: ' ) );
	        content.appendChild( role );
	        content.appendChild( document.createTextNode( rank ) );
	        content.appendChild( document.createElement( 'br' ) );
	        if ( hobbies !== '' ) {
	            var interests = document.createElement( 'b' );
	            interests.appendChild( document.createTextNode( 'Ενδιαφέροντα: ' ) );
	            content.appendChild( interests );
	            content.appendChild( document.createTextNode( hobbies ) );
	            content.appendChild( document.createElement( 'br' ) );
	        }
	        newfan.style.display = 'block';
		}
        Friends.processing = false;
        Friends.ShowAll( false );
    },
    FriendDeleted : function( userid ) {
    	var div = document.getElementById( 'newfancontent' );
		div.innerHTML = "";
		
		var outerdiv = document.getElementById( 'fan_' + userid );
		outerdiv.id = 'newfan';
		outerdiv.style.display = "none";
		
		var alltabs = document.getElementById( 'alltabs' );
		for ( var i=0;i<alltabs.childNodes.length-1;++i ) {
			var temp = document.getElementById( 'tab' + i );
			if ( temp.childNodes.length == 8 && temp.childNodes[4].nodeValue == "Με έχουν φίλo " ) { // If I was his only friend
				temp.innerHTML = "";
			}
		}
		Friends.processing = false;
		Friends.ShowAll( false );
	},
	ShowAll : function(show) {
		if ( Friends.processing ) {
			return;
		}
		if ( show == false && Friends.onappear == false ) {
			return;
		}
		
		var friend_relations = g('friend_relations');
		if( show ) {
			friend_relations.style.display="block";
			Animations.Create( friend_relations, "opacity", 1500, 0, 1, new Function(), Interpolators.Pulse );
		}
		else {
			Animations.Create( friend_relations, "opacity", 1500, 1, 0, function() { g('friend_relations').style.display="none"; }, Interpolators.Pulse );
		}
		
		Friends.onappear = show;
	}
};
