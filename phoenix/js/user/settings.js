var Settings = {
	saver : 0,
	queue : {},
	showsaved : $( 'div.settings div.sidebar div span.saved' ),
	showsaving : $( 'div.settings div.sidebar div span.saving' ),
	invaliddob : false,
	aboutmetext : $( '#aboutme textarea' )[ 0 ].value,
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
	$( 'div.settings div.sidebar ol li' ).click( function() {
		Settings.SwitchSettings();
	});
	Settings.SwitchSettings();
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
					$( 'div.settings div.tabs form#personal div span.invaliddob' )
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
					$( 'div.settings div.tabs form#personal div span.invaliddob' )
						.css( "display" , "inline" )
						.animate( { opacity: "1" } , 200 );	
					Settings.invaliddob = true;
				}
			}
		}
	});
	/*
	this doesn't work correctly
	var inputids = [ "place" , "education" , "sex" , "religion" , "politics" , "haircolor" , "eyecolor" , "height" , "weight" , "smoker" , "drinker" ];
	for ( i = 0; i < inputids.length; ++i ) {
		$( '#' + inputids[ i ] + ' select' ).change( function() {
			Settings.Enqueue( inputids[ i ] , this.value , 3000 );
		});
	}*/
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
	});
	$( '#aboutme textarea' ).keyup( function() {
		if ( Settings.aboutmetext != this.value ) {
			var text = this.value;
			if ( this.value == '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'aboutme' , text , 3000 );
			Settings.aboutmetext = this.value;
		}
	});
});
//setInterval( Settings.SwitchSettings , 500 );