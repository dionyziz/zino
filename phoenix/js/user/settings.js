var Settings = {
	saver : 0,
	queue : {},
	showsaved : $( 'div.settings div.sidebar div span.saved' ),
	showsaving : $( 'div.settings div.sidebar div span.saving' ),
	invaliddob : false,
	aboutmetext : $( '#aboutme textarea' )[ 0 ].value,
	email : $( '#email input' )[ 0 ].value,
	msn : $( '#msn input' )[ 0 ].value,
	gtalk : $( '#gtalk input' )[ 0 ].value,
	skype : $( '#skype input' )[ 0 ].value,
	yahoo : $( '#yahoo input' )[ 0 ].value,
	web : $( '#web input' )[ 0 ].value,
	invalidemail : false,
	invalidmsn : false,
	SwitchSettings : function( divtoshow ) {
		//var hash = window.location.hash.substr( 1 );
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = $( 'div.settings div.sidebar ol li' );
		
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( divtoshow == validtabs[ i ] ) {
				$( '#' + divtoshow + 'info' ).show( 'fast' );
				Settings.FocusSettingLink( settingslis[ i ], true );
				window.location.hash = window.location.hash.substr( 0, 1 ) + validtabs[ i ];
				/*if ( divtoshow == 'interests' ) {
					window.scrollTo( 0 , 0 );
				}
				*/
				found = true;
			}
			else {
				$( '#' + validtabs[ i ] + 'info' ).hide( 'fast' );
				Settings.FocusSettingLink( settingslis[ i ], false );
				
			}
		}
		if ( !found ) {
			$( '#' + validtabs[ 0 ] + 'info' ).show( 'fast' );
			window.location.hash = window.location.hash.substr( 0, 1 ) + 'personal';
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
	Enqueue : function( key , value , timerinterval ) {
		if ( Settings.saver != 0 ) {
			clearTimeout( Settings.saver );
		}
		Settings.saver = setTimeout( Settings.Save , timerinterval );
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
	Settings.SwitchSettings( window.location.hash.substr( 1 ) );
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
		Settings.Enqueue( 'gender' , this.value , 3000 );
	});
	$( '#dateofbirth select' ).change( function() {
		var day = $( '#dateofbirth select' )[ 0 ].value;
		var month = $( '#dateofbirth select' )[ 1 ].value;
		var year = $( '#dateofbirth select' )[ 2 ].value;
		//check for validdate
		if ( day != -1 && month != -1 && year != -1 ) {
			if ( Dates.ValidDate( day , month , year ) ) {
				if ( Settings.invaliddob ) {
					$( 'div.settings div.tabs form#personalinfo div span.invaliddob' )
						.animate( { opacity: "0" } , 1000 , function() {
							$( this ).css( "display" , "none" );
						});
					Settings.invaliddob = false;
				}
				Settings.Enqueue( 'dobd' , day , 4000 );
				Settings.Enqueue( 'dobm' , month , 4000 );
				Settings.Enqueue( 'doby' , year , 3000 );
			}
			else {
				if ( !Settings.invaliddob ) {
					$( 'div.settings div.tabs form#personalinfo div span.invaliddob' )
						.css( "display" , "inline" )
						.animate( { opacity: "1" } , 200 );	
					Settings.invaliddob = true;
				}
			}
		}
	});
	$( '#place select' ).change( function() {
		Settings.Enqueue( 'place' , this.value , 3000 );
	});
	$( '#education select' ).change( function() {
		Settings.Enqueue( 'education' , this.value , 3000 );
	});
	$( '#sex select' ).change( function() {
		Settings.Enqueue( 'sex' , this.value , 3000 );
	});
	$( '#religion select' ).change( function() {
		Settings.Enqueue( 'religion' , this.value , 3000 );
	});
	$( '#politics select' ).change( function() {
		Settings.Enqueue( 'politics' , this.value , 3000 );
	});
	$( '#haircolor select' ).change( function() {
		Settings.Enqueue( 'haircolor' , this.value , 3000 );
	});
	$( '#eyecolor select' ).change( function() {
		Settings.Enqueue( 'eyecolor' , this.value , 3000 );
	});
	$( '#height select' ).change( function() {
		Settings.Enqueue( 'height' , this.value , 3000 );
	});
	$( '#weight select' ).change( function() {
		Settings.Enqueue( 'weight' , this.value , 3000 );
	});
	$( '#smoker select' ).change( function() {
		Settings.Enqueue( 'smoker' , this.value , 3000 );
	});
	$( '#drinker select' ).change( function() {
		Settings.Enqueue( 'drinker' , this.value , 3000 );
	});
	
	$( '#aboutme textarea' ).change( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'aboutme' , text , 3000 );
	}).keyup( function() {
		if ( Settings.aboutmetext != this.value ) {
			var text = this.value;
			if ( this.value == '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'aboutme' , text , 3000 );
			Settings.aboutmetext = this.value;
		}
	});
	
	$( '#email input' ).change( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'email' , text , 500 );
	}).keyup( function() {
		var text = this.value;
		if ( Settings.invalidemail ) {
			if ( /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( text ) ) {
				$( 'div#email span' ).animate( { opacity: "0" } , 1000 , function() {
					$( 'div#email span' ).css( "display" , "none" );
				});
				Settings.invalidemail = false;
				Settings.Enqueue( 'email' , text , 3000 );
			}
		}
		else {
			if ( this.value == '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'email' , text , 3000 );
		}
		Settings.email = this.value;
	});
	
	$( '#msn input' ).change( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'msn' , text , 500 );
	}).keyup( function() {
		var text = this.value;
		if ( Settings.invalidmsn ) {
			if ( /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( text ) ) {
				$( 'div#msn span' ).animate( { opacity: "0" } , 1000 , function() {
					$( 'div#msn span' ).css( "display" , "none" );
				});
				Settings.invalidmsn = false;
				Settings.Enqueue( 'msn' , text , 3000 );
			}
		}
		else {
			if ( this.value == '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'msn' , text , 3000 );
		}
		Settings.msn = this.value;
	});
	
	$( '#gtalk input' ).change( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'gtalk' , text , 500 );
	}).keyup( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'gtalk' , text , 3000 );
		Settings.gtalk = this.value;
	});
	
	$( '#skype input' ).change( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'skype' , text , 500 );
	}).keyup( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'skype' , text , 3000 );
		Settings.skype = this.value;
	});
	
	$( '#yahoo input' ).change( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'yahoo' , text , 500 );
	}).keyup( function() {
		var text = this.value;
		if ( this.value == '' ) {
			text = '-1';
		}
		Settings.Enqueue( 'yahoo' , text , 3000 );
		Settings.yahoo = this.value;
	});
});
//setInterval( Settings.SwitchSettings , 500 );