var Banner = {
	isanimating : false,
	Login : function () {
		var banner = document.getElementById( 'banner' );
		var menu = banner.getElementsByTagName( 'ul' )[ 0 ];
		var options = menu.getElementsByTagName( 'li' );
		if ( Banner.isanimating ) {
			return;
		}
		Banner.isanimating = true;
		if ( options[ 0 ].style.display == '' ) {
			Animations.Create( menu, 'opacity', 500, 1, 0, function () {
				options[ 0 ].style.display = 'none';
				options[ 1 ].style.display = 'none';
				options[ 3 ].style.display = '';
				options[ 4 ].style.display = '';
				options[ 5 ].style.display = '';
				Animations.Create( menu, 'opacity', 500, 0, 1, function() {
					Banner.isanimating = false;
				} );
				menu.getElementsByTagName( 'input' )[ 0 ].value = '';
				menu.getElementsByTagName( 'input' )[ 0 ].focus();
			} );
		}
		else {
			Animations.Create( menu, 'opacity', 500, 1, 0, function () {
				options[ 0 ].style.display = '';
				options[ 1 ].style.display = '';
				//options[ 2 ].style.display = 'none';
				options[ 3 ].style.display = 'none';
				options[ 4 ].style.display = 'none';
				options[ 5 ].style.display = 'none';
				Animations.Create( menu, 'opacity', 500, 0, 1, function() {
					Banner.isanimating = false;
				} );
			} );
		}
	}
};