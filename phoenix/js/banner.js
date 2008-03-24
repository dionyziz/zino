var Banner = {
	isanimating : false,
	Login : function () {
		/*
		var banner = document.getElementById( 'banner' );
		var menu = banner.getElementsByTagName( 'ul' )[ 0 ];
		var options = menu.getElementsByTagName( 'li' );
		*/
		var menu = $( 'div#banner ul' )[ 0 ];
		var options = $( 'div#banner ul li' );
		if ( Banner.isanimating ) {
			return;
		}
		Banner.isanimating = true;
		if ( options[ 0 ].style.display == '' ) {
			//Animations.Create( menu, 'opacity', 500, 1, 0, function () {
			$( menu ).animate( { opacity: "0" } , function() {
				options[ 0 ].style.display = 'none';
				options[ 1 ].style.display = 'none';
				//$( options[ 0 ] ).hide();
				//$( options[ 1 ] ).hide();
				$( options[ 3 ] ).show();
				$( options[ 4 ] ).show();
				$( options[ 5 ] ).show();
				/*
				options[ 3 ].style.display = '';
				options[ 4 ].style.display = '';
				options[ 5 ].style.display = '';
				*/
				$( menu ).animate( { opacity: "1" } , 500 , function() {
					Banner.isanimating = false;
				} );/*(
				Animations.Create( menu, 'opacity', 500, 0, 1, function() {
					Banner.isanimating = false;
				} );
				*/
				menu.getElementsByTagName( 'input' )[ 0 ].value = '';
				menu.getElementsByTagName( 'input' )[ 0 ].focus();
			} );
		}
		else {
			$( menu ).animate( { opacity: "1" } , 500 , function() {
			//Animations.Create( menu, 'opacity', 500, 1, 0, function () {
				/*
				$( options[ 0 ] ).show();
				$( options[ 1 ] ).show();
				$( options[ 3 ] ).hide();
				$( options[ 4 ] ).hide();
				$( options[ 5 ] ).hide();
				*/
				options[ 0 ].style.display = '';
				options[ 1 ].style.display = '';
				//options[ 2 ].style.display = 'none';
				options[ 3 ].style.display = 'none';
				options[ 4 ].style.display = 'none';
				options[ 5 ].style.display = 'none';
				
				//$( menu ).animate( { opacity: "1" } , 500 , function() {
				Animations.Create( menu, 'opacity', 500, 0, 1, function() {
					Banner.isanimating = false;
				} );
			} );
		}
	}
};