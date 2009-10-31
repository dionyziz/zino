var Settings = {
	SwitchSettings : function( divtoshow ) {
		//hack so that it is executed only when it is loaded
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = $( 'div.settings div.sidebar ol li' );
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( divtoshow == validtabs[ i ] ) {
				$( '#' + divtoshow + 'info' ).show();
				Settings.FocusSettingLink( settingslis[ i ], true , validtabs[ i ] );
                alert( window.location.hash.substr( 0, 1 ) );
				window.location.hash = window.location.hash.substr( 0, 1 ) + validtabs[ i ];
				found = true;
			}
			else {
				$( '#' + validtabs[ i ] + 'info' ).hide();
				Settings.FocusSettingLink( settingslis[ i ], false , validtabs[ i ] );
				
			}
		}
		if ( !found ) {
			$( '#' + validtabs[ 0 ] + 'info' ).show();
			window.location.hash = window.location.hash.substr( 0, 1 ) + 'personal';
			Settings.FocusSettingLink( settingslis[ 0 ] , true , validtabs[ 0 ] );
		}
	}
};
