var Settings = {
	saver : 0,
	queue : {},
	showsaved : $( 'div.settings div.sidebar div span.saved' ),
	showsaving : $( 'div.settings div.sidebar div span.saving' ),
	SwitchSettings : function() {
		var hash = window.location.hash.substr( 1 );
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = $( 'div.settings div.sidebar ol li' );
		
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( hash == validtabs[ i ] ) {
				document.getElementById( validtabs[ i ] ).style.display = '';
				Settings.FocusSettingLink( settingslis[ i ], true );
				found = true;
			}
			else {
				document.getElementById( validtabs[ i ] ).style.display = 'none';
				Settings.FocusSettingLink( settingslis[ i ], false );
			}
		}
		
		if ( !found ) {
			document.getElementById( validtabs[ 0 ] ).style.display = '';
			Settings.FocusSettingLink( settingslis[ 0 ] , true );
		}
	},
	FocusSettingLink : function( li, focus ) {
		if ( focus ) {
			$( li ).addClass( 'selected' );
			li.getElementsByTagName( 'a' )[ 0 ].style.color = 'white';
		}
		else {
			$( li ).removeClass( 'selected' );
			li.getElementsByTagName( 'a' )[ 0 ].style.color = '#105cb6';
		}
	},
	DoSwitchSettings : function() {
		setTimeout( Settings.SwitchSettings, 20 );
	},
	Enqueue : function( key , value ) {
		if ( Settings.saver != 0 ) {
			clearTimeout( Settings.saver );
		}
		Settings.saver = setTimeout( Settings.Save , 2000 );
		Settings.queue[ key ] = value;
	},
	Dequeue : function() {
		Settings.queue = {};
	},
	Save : function() {
		$( Settings.showsaving )
			.css( "display" , "inline" )
			.animate( { opacity : "1" } , 200 );
		Coala.Warm( 'user/settings/save' , Settings.queue );
		Settings.Dequeue();
	}
};
$( document ).ready( function() {
	$( 'div.settings div.sidebar ol li' ).click( function() {
		Settings.DoSwitchSettings();
	});
	var inputids = [ "age" ];
	for ( i = 0; i < inputids.length; ++i ) {
		$( '#' + inputids[ i ] ).change( function() {
			Settings.Enqueue( inputids[ i ] , this[ 0 ].value );
		});
	}
	
	$( '#gender select' ).change( function() {
		var sexselected = $( '#sex select' )[ 0 ].value;
		var relselected = $( '#religion select' )[ 0 ].value;
		var polselected = $( '#politics select' )[ 0 ].value;
		Coala.Cold( 'user/settings/genderupdate' , { 
			gender : this.value,
			sex : sexselected,
			religion : relselected,
			politics : polselected
		} );
		Settings.Enqueue( 'gender' , this.value );
	});
	$( '#dateofbirth select' ).change( function() {
	
	});
	$( '#place select' ).change( function() {
		Settings.Enqueue( 'place' , this.value );
	});
	$( '#education select' ).change( function() {
		Settings.Enqueue( 'education' , this.value );
	});
	$( '#sex select' ).change( function() {
		Settings.Enqueue( 'sex' , this.value );
	});
	$( '#religion select' ).change( function() {
		Settings.Enqueue( 'religion' , this.value );
	});
	$( '#politics select' ).change( function() {
		Settings.Enqueue( 'politics' , this.value );
	});
	$( '#aboutme textarea' ).change( function() {
		Settings.Enqueue( 'aboutme' , this.value );
	});
	
	$( Settings.showsaved ).css( "opacity" , "0" );
	$( Settings.showsaving ).css( "opacity" , "0" );
});
setInterval( Settings.SwitchSettings , 500 );