var Join = {
	timervar : 0,
	hadcorrect : false,
	Focusinput : function ( node ) {
		node.style.border = '1px solid #bdbdff';
	},
	Unfocusinput : function ( node ) {
		node.style.border = '1px solid #999';
	},
	Checkpwd : function( node ) {
		var parent = node.parentNode.parentNode;
		var divlist = parent.getElementsByTagName( 'div' );
		var div = divlist[ 0 ];
		var inputlist = parent.getElementsByTagName( 'input' );
		var pwd = inputlist[ 0 ];
		if ( Join.timervar !== 0 ) {
			clearTimeout( Join.timervar );
		}
		
		Join.timervar = setTimeout( function() {
			if ( node.value == pwd.value && node.value !== '' && !Join.hadcorrect ) {
				Join.hadcorrect = true;
				node.style.display = 'inline';
				var okpwd = document.createElement( 'img' );
				okpwd.src = 'images/button_ok_16.png';
				okpwd.alt = 'Σωστή επαλήθευση';
				okpwd.title = 'Σωστή επαλήθευση';
				okpwd.style.paddingLeft = '5px';
				if ( typeof okpwd.style.opacity != 'undefined' ) {
					Animations.SetAttribute( okpwd, 'opacity', 0 );
					div.appendChild( okpwd );
					Animations.Create( okpwd, 'opacity', 2000, 0, 1 );
				}
				else {
					div.appendChild( okpwd );
				}
			}
			else {
				var imglist = parent.getElementsByTagName( 'img' );
				var okpwd = imglist[ 0 ];
				//alert( okpwd );
				if ( node.value != pwd.value && okpwd ) {
					div.removeChild( okpwd );
					Join.hadcorrect = false;
				}
			}
		}, 200 );
	},
	ShowTos : function () {
		var area = document.getElementById( 'join_tos' ).cloneNode( true );
		area.style.display = 'block';
		Modals.Create( area, 500, 300 );
	}
};
setTimeout( function () {
		document.getElementById( 'join_name' ).focus();
}, 20 );