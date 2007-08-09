var Friends = {
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
		Friends.correctList();
		var selected = g( 'frel_' + friendtype );
        selected.className = "relselected";
        selected.title = "Επιλεγμένη Σχέση";
        selected.style.backgroundColor = "#99FF99";
        selected.style.opacity = 1;
        selected.style.display = "list-item";
	
		if ( avatar != '' || rank != '' || hobbies != '' ) { // If the user didn't have a previous relation with you
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
	        if ( hobbies != '' ) {
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
    	Friends.processing = true;
    	Friends.correctList();
    	var selected = g( 'frel_-1' );
    	selected.className = "relselected";
    	selected.title = "Επιλεγμένη Σχέση";
    	selected.style.display = "list-item";
    	selected.style.opacity = 1;
    
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
		
		var friendslink = g('friendsshowlink');
		friendslink.className = (show)?"arrowup":"arrow";
		friendslink.onclick = function() {
						Friends.ShowAll( (show)?false:true );
						return false;
					};
		friendslink.title = (show)?"Απόκρυψη":"Προβολή";
		friendslink.title += " Σχέσεων";
		
		var daddy = g( 'frel_type' );
		for ( var i in daddy.childNodes ) {
			var child = daddy.childNodes[i];
			if ( child.nodeType == 1 ) {
				var chosen = ( child.title == '' )?false:true;
				
				if ( show ) {
					child.style.display = "block";
					if( !chosen ) {
						Animations.Create( child, "opacity", 1500, 0, 1, new Function(), Interpolators.Pulse );
					}
				}
				else if ( !show ) {
					Animations.Create( child, "opacity", 1000, 1, 0, (function(child,show,chosen) {
									return function() {
										if( !chosen ) {
											child.style.display = "none";
										}
										else {
											Animations.Create( child, "opacity", 400, 0, 1, new Function(), Interpolators.Pulse );
										}
									}
								})(child,show,chosen), Interpolators.Pulse );
				}
			}
		}
	},
	correctList : function() {
		var relations = g( 'frel_type' );
        for ( var i in relations.childNodes ) {
        	if ( relations.childNodes[i].nodeType == 1 && relations.childNodes[i].title != '' ) { // Find the previous relation
        		relations.childNodes[i].title = '';
        		relations.childNodes[i].className = "relation";
        		relations.childNodes[i].style.color = "";
        		if ( i == 0 ) {
        			relations.childNodes[i].style.backgroundColor = "";
        		}
        		else if ( relations.childNodes[i-1].style.backgroundColor == "" ) {
        			relations.childNodes[i].style.backgroundColor = "#ececec";
        		}
        		else {
        			relations.childNodes[i].style.backgroundColor = "";
        		}
        		break;
        	}
        }
	}
}
