// instant tab switching script
var UserTabs = {
	activated : 0,
	Activate: function( tabindex ) {
		parentdiv = document.getElementById( 'userprofile_tabs' );
		children_divs = parentdiv.getElementsByTagName( 'div' );
		firstelem = children_divs.length - tabindex * 3 - 3;
		children_divs[ firstelem ].className = "rightism activeright";
		children_divs[ firstelem + 1 ].className = "tab active";
		children_divs[ firstelem + 2 ].className = "leftism activeleft";
		for ( i in children_divs ) {
			if ( i < firstelem || i > firstelem + 2 ) {
				child_div = children_divs[ i ];
				switch ( child_div.className ) {
					case 'rightism activeright':
						child_div.className = "rightism";
						break;
					case 'tab active':
						child_div.className = "tab";
						break;
					case 'leftism activeleft':
						child_div.className = "leftism";
				}
			}
		}
		document.getElementById( 'tab' + UserTabs.activated ).style.display = "none";
		document.getElementById( 'tab' + tabindex ).style.display = "";	
		UserTabs.activated = tabindex;
	}
};

parentdiv = document.getElementById( 'userprofile_tabs' );
children_divs = parentdiv.getElementsByTagName( 'div' );
for ( i in children_divs ) {
	child_div = children_divs[ i ];
	j = Math.floor( ( children_divs.length - 1 - i ) / 3 );
	child_div.onclick = ( function ( index ) {
		return function () {
			UserTabs.Activate( index );
		};
	} )( j );
}

var viewingTabs = g( 'userprofile_viewingtabs' ).childNodes[ 0 ].nodeValue;
var friendsTab 	= g( 'userprofile_friendstab' ).childNodes[ 0 ].nodeValue;

if ( g( 'userprofile_viewalbums' ).childNodes[ 0 ].nodeValue == 'yes' ) {
	UserTabs.Activate( viewingTabs );
}
if ( g( 'userprofile_viewfriends' ).childNodes[ 0 ].nodeValue == 'yes' ) {
	UserTabs.Activate( friendsTab );
}