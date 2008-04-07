var Settings = {
	SwitchSettings : function() {
		var hash = window.location.hash.substr( 1 );
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = document.getElementById( 'settingslist' ).getElementsByTagName( 'li' );
		
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( hash == validtabs[ i ] ) {
				document.getElementById( validtabs[ i ] ).style.display = '';
				Settings.FocusSettingLink( settingslis[ i ], true );
				found = true;
			}/*
			else {
				document.getElementById( validtabs[ i ] ).style.display = 'none';
				Settings.FocusSettingLink( settingslis[ i ], true );
			}*/
		}
		
		if ( !found ) {
			document.getElementById( validtabs[ 0 ] ).style.display = '';
			Settings.FocusSettingLink( settingslis[ 0 ] , true );
		}
	},
	FocusSettingLink : function( li, focus ) {
		if ( focus ) {
			li.style.fontWeight = 'bold';
			li.style.backgroundColor = '#047cbb';
			li.style.backgroundImage = 'url("images/setting-selected.png")';
			li.getElementsByTagName( 'a' )[ 0 ].style.color = 'white';
		}
		else {
			li.style.fontWeight = '';
			li.style.backgroundColor = 'white';
			li.style.backgroundImage = 'none';
			li.getElementsByTagName( 'a' )[ 0 ].style.color = '#105cb6';
		}
	},
	DoSwitchSettings : function() {
		setTimeout( Settings.SwitchSettings, 20 );
	},
};
setInterval( Settings.SwitchSettings, 500 );