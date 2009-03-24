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
		if ( options[ 0 ].style.display === '' ) {
			//Animations.Create( menu, 'opacity', 500, 1, 0, function () {
			$( menu ).animate( { opacity: "0" } , function() {
				options[ 0 ].style.display = 'none';
				options[ 1 ].style.display = 'none';
				$( options[ 3 ] ).show();
				$( options[ 4 ] ).show();
				$( options[ 5 ] ).show();
				$( menu ).animate( { opacity: "1" } , 500 , function() {
					Banner.isanimating = false;
				} );
				$( 'div#banner ul input' )[ 0 ].value = '';
				$( 'div#banner ul input' )[ 1 ].value = '';
				$( 'div#banner ul input' )[ 0 ].focus();
			} );
		}
		else {
			$( menu ).animate( { opacity: "1" } , 500 , function() {	
				$( options[ 0 ] ).show();
				$( options[ 1 ] ).show();
				$( options[ 3 ] ).hide();
				$( options[ 4 ] ).hide();
				$( options[ 5 ] ).hide();
				$( menu ).animate( { opacity: "1" } , 500 , function() {
					Banner.isanimating = false;
				} );
			} );
		}
	},
    OnLoad : function() {
        $( 'div.search form input.text' ).focus( function() {
            this.value = '';
        });
        $( 'div.search form input.text' ).blur( function() {
            this.value = 'αναζήτησε φίλους';
        });
    }
};
