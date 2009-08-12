/*
	Masked by: Rhapsody
	Reason: new ajax loading tabs for settings testing
	
	STOP! Was masked
*/
var Settings = {
	SwitchSettings : function( divtoshow ) {
		$( 'div.settings div.tabs' ).empty();
		$( '#settingsloader' ).fadeIn( 'fast' );
		//hack so that it is executed only when it is loaded
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = $( 'div.settings div.sidebar ol li' );
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( divtoshow == validtabs[ i ] ) {
				//TODO: add some loader
				Coala.Cold( 'user/settings/tab', { 
					tab : divtoshow
				} );
				//$( '#' + divtoshow + 'info' ).show();
				Settings.FocusSettingLink( settingslis[ i ], true , validtabs[ i ] );
				window.location.hash = window.location.hash.substr( 0, 1 ) + validtabs[ i ];
				found = true;
			}
			/*
			else {
				$( '#' + validtabs[ i ] + 'info' ).hide();
				Settings.FocusSettingLink( settingslis[ i ], false , validtabs[ i ] );		
			}
			*/
		}
		if ( !found ) {
			Coala.Cold( 'user/settings/tab',  { 
				tab : validtabs[ 0 ]
			} );
			//$( '#' + validtabs[ 0 ] + 'info' ).show();
			window.location.hash = window.location.hash.substr( 0, 1 ) + 'personal';
			Settings.FocusSettingLink( settingslis[ 0 ] , true , validtabs[ 0 ] );
		}
	},
	FocusSettingLink : function( li, focus , tabname ) {
		if ( li ) {
			if ( focus ) {
				$( li ).addClass( 'selected' );
				li.getElementsByTagName( 'a' )[ 0 ].style.color = 'white';
			}
			else {
				$( li ).removeClass( 'selected' );
				li.getElementsByTagName( 'a' )[ 0 ].style.color = '#105cb6';
			}
		}
	},
	DoSwitchSettings : function() {
		setTimeout( Settings.SwitchSettings, 20 );
	},
	Enqueue : function( key , value ) {
		Settings.queue[ key ] = value;
        $( 'div.savebutton a' ).removeClass( 'disabled' );
	},
	Dequeue : function() {
		Settings.queue = {};
	},
	Save : function( visual ) {
        if ( visual ) {
			$( 'div.savebutton a' ).html( $( Settings.showsaving ).html() );
		}
		Coala.Warm( 'user/settings/save' , Settings.queue );
		Settings.Dequeue();
	},
	AddInterest : function( type , typeid ) {
		//type can be either: hobbies, movies, books, songs, artists, games, quotes, shows
		var intervalue = $( 'form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value;
		if ( $.trim( intervalue ) !== '' ) {
			if ( intervalue.length <= 32 ) {
				var newli = document.createElement( 'li' );
				var newspan = $( 'form#interestsinfo div.creation' )[ 0 ].cloneNode( true );
				$( newspan ).removeClass( 'creation' ).find( 'span.bbblmiddle' ).append( document.createTextNode( intervalue ) );
				var link = newspan.getElementsByTagName( 'a' )[ 0 ];
				$( newli ).append( newspan );
				$( 'form#interestsinfo div.option div.setting ul.' + type ).prepend( newli );
				Suggest.added[ type ].push( intervalue );
				Coala.Warm( 'user/settings/tags/new' , { text : intervalue , typeid : typeid , node : link } );
			}
			else {
				alert( 'Το κείμενό σου μπορεί να έχει 32 χαρακτήρες το πολύ' );
			}
			$( 'form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
		else {
			alert( 'Δε μπορείς να προσθέσεις κενό ενδιαφέρον' );
			$( 'form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
	},
	RemoveInterest : function( tagid , node ) {
		var parent = node.parentNode.parentNode;
		$( node ).remove();
		$( parent ).hide( 'slow' );
		Coala.Warm( 'user/settings/tags/delete' , { tagid : tagid } );
		return false;
	},
	SelectAvatar : function( imageid ) {
        $( '#avatarlist' ).jqmHide();
		Coala.Warm( 'user/settings/avatar' , { imageid : imageid } );
	},
	AddAvatar : function( imageid ) {
        var li = document.createElement( 'li' );
		$( li ).hide();
		$( 'div#avatarlist ul' ).prepend( li );
		Coala.Warm( 'user/settings/upload' , { imageid : imageid } );
		var li2 = document.createElement( 'li' );
		$( 'div#avatarlist ul' ).prepend( li2 );
	},
	ChangePassword : function( oldpassword , newpassword , renewpassword ) {
		if ( oldpassword.length < 4 ) {
			Settings.oldpassworderror = true;
			$( Settings.oldpassworddiv ).find( 'div div span' ).fadeIn( 300 );
			Settings.oldpassword.focus();
		}
		if ( newpassword.length < 4 && !Settings.oldpassworderror ) {
			Settings.newpassworderror = true;
			$( Settings.newpassworddiv ).find( 'div div span' ).fadeIn( 300 );
			Settings.newpassword.focus();
		}
		if ( newpassword != renewpassword && !Settings.oldpassworderror && !Settings.newpassworderror ) {
			Settings.renewpassworderror = true;
			$( Settings.renewpassworddiv ).find( 'div div span' ).fadeIn( 300 );
			Settings.renewpassword.focus();
		}
		if ( !Settings.oldpassworderror && !Settings.newpassworderror && !Settings.renewpassworderror ) {
			Settings.Enqueue( 'oldpassword' , oldpassword );
			Settings.Enqueue( 'newpassword' , newpassword );
            Settings.Save( false );
		}
	},
    ControlInput : function( id ) {
        $('#' +  id + ' input' ).change( function() {
            var text = this.value; if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( id , text );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( id , text );
            if ( Settings[ id ] ) {
                Settings[ id ] = this.value;
            }
        });
    },
	LoadProperties : function ( tab ) {
		// TODO: sidebar should not be clickable once selected
		//( will currently reset settings fields ? )
		
		switch( tab ) {
			case 'personal':
				Settings.invaliddob = false;
				Settings.slogan =  $( '#slogan input' )[ 0 ].value;
				Settings.favquote = $( '#favquote input' )[ 0 ].value;
				Settings.aboutmetext = $( '#aboutme textarea' )[ 0 ].value;
				
				$( '#gender select' ).change( function() {
					var sexselected = $( '#sex select' )[ 0 ].value;
					var relselected = $( '#religion select' )[ 0 ].value;
					var polselected = $( '#politics select' )[ 0 ].value;
					var relationshipselected = $( '#relationship select' )[ 0 ].value;
					Coala.Cold( 'user/settings/genderupdate' , { 
						gender : this.value,
						sex : sexselected,
						relationship: relationshipselected,
						religion : relselected,
						politics : polselected
					} );
					Settings.Enqueue( 'gender' , this.value );
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
							Settings.Enqueue( 'dobd' , day );
							Settings.Enqueue( 'dobm' , month );
							Settings.Enqueue( 'doby' , year );
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
					Settings.Enqueue( 'place' , this.value );
					Settings.Save( false );
				});
				$( '#education select' ).change( function() {
					Settings.Enqueue( 'education' , this.value );
					Settings.Save( false );
				});
				$( '#school select' ).change( function() {
					Settings.Enqueue( 'school' , this.value );
				});
				$( '#sex select' ).change( function() {
					Settings.Enqueue( 'sex' , this.value );
				});
				$( '#relationship select' ).change( function() {
					Settings.Enqueue( 'relationship' , this.value );
				});
				$( '#religion select' ).change( function() {
					Settings.Enqueue( 'religion' , this.value );
				});
				$( '#politics select' ).change( function() {
					Settings.Enqueue( 'politics' , this.value );
				});			
				Settings.ControlInput( 'slogan' );
				$( '#aboutme textarea' ).change( function() {
					var text = this.value;
					if ( this.value === '' ) {
						text = '-1';
					}
					Settings.Enqueue( 'aboutme' , text );
				}).keyup( function() {
					if ( Settings.aboutmetext != this.value ) {
						var text = this.value;
						if ( this.value === '' ) {
							text = '-1';
						}
						Settings.Enqueue( 'aboutme' , text );
						if ( Settings.aboutmetext ) {
							Settings.aboutmetext = this.value;
						}
					}
				} );
				Settings.ControlInput( 'favquote' );		
				break;
			case 'characteristics':
				$( '#haircolor select' ).change( function() {
					Settings.Enqueue( 'haircolor' , this.value );
				});
				$( '#eyecolor select' ).change( function() {
					Settings.Enqueue( 'eyecolor' , this.value );
				});
				$( '#height select' ).change( function() {
					Settings.Enqueue( 'height' , this.value );
				});
				$( '#weight select' ).change( function() {
					Settings.Enqueue( 'weight' , this.value );
				});
				$( '#smoker select' ).change( function() {
					Settings.Enqueue( 'smoker' , this.value );
				});
				$( '#drinker select' ).change( function() {
					Settings.Enqueue( 'drinker' , this.value );
				});		
				break;	
			case 'interests':
				//interesttags
				// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
				var interesttagtypes = [ 'hobbies', 'movies', 'books', 'songs', 'artists', 'games', 'shows' ];
				for( var i in interesttagtypes ) {
					$( 'form#interestsinfo div.option div.setting div.' + interesttagtypes[ i ] + ' a' ).click( function( i ) {
						return function() {
							Settings.AddInterest( interesttagtypes[ i ] , Suggest.type2int( interesttagtypes[ i ] ) );
							$( 'div.' + interesttagtypes[ i ] + ' ul' ).hide();
							return false;
						};
					}( i ) );
				}		
				break;	
			case 'contact':
				Settings.msn = $( '#msn input' )[ 0 ].value;
				Settings.gtalk = $( '#gtalk input' )[ 0 ].value;
				Settings.skype = $( '#skype input' )[ 0 ].value;
				Settings.yahoo = $( '#yahoo input' )[ 0 ].value;
				Settings.web = $( '#web input' )[ 0 ].value;	
				$( '#msn input' ).change( function() {
					var text = this.value;
					if ( this.value === '' ) {
						text = '-1';
					}
					Settings.Enqueue( 'msn' , text , 500 );
				}).keyup( function() {
					var text = this.value;
					if ( Settings.invalidmsn ) {
						if ( Kamibu.ValidEmail( text ) ) {
							$( 'div#msn span' ).animate( { opacity: "0" } , 1000 , function() {
								$( 'div#msn span' ).css( "display" , "none" );
							});
							Settings.invalidmsn = false;
							Settings.Enqueue( 'msn' , text );
						}
					}
					else {
						if ( this.value === '' ) {
							text = '-1';
						}
						Settings.Enqueue( 'msn' , text );
					}
					if ( Settings.msn ) {
						Settings.msn = this.value;
					}
				});
				Settings.ControlInput( 'gtalk' );
				Settings.ControlInput( 'skype' );
				Settings.ControlInput( 'yahoo' );
				Settings.ControlInput( 'web' );		
				break;
			case 'settings':
				Settings.oldpassworderror = false;
				Settings.newpassworderror = false;
				Settings.renewpassworderror = false;
				Settings.oldpassworddiv = $( 'div#pwdmodal div.oldpassword' );
				Settings.newpassworddiv = $( 'div#pwdmodal div.newpassword' );
				Settings.renewpassworddiv = $( 'div#pwdmodal div.renewpassword' );
				Settings.oldpassword = $( 'div#pwdmodal div.oldpassword div input' )[ 0 ];
				Settings.newpassword = $( 'div#pwdmodal div.newpassword div input' )[ 0 ];
				Settings.renewpassword = $( 'div#pwdmodal div.renewpassword div input' )[ 0 ];
				Settings.email = $( '#email input' )[ 0 ].value;
				Settings.invalidemail = false;
				Settings.invalidmsn = false;	
				$( '#email input' ).change( function() {
					var text = this.value;
					if ( this.value === '' ) {
						text = '-1';
					}
					Settings.Enqueue( 'email' , text );
				}).keyup( function() {
					var text = this.value;
					if ( Settings.invalidemail ) {
						if ( Kamibu.ValidEmail( text ) ) {
							$( 'div#email span' ).animate( { opacity: "0" } , 1000 , function() {
								$( 'div#email span' ).css( "display" , "none" );
							});
							Settings.invalidemail = false;
							Settings.Enqueue( 'email' , text );
						}
					}
					else {
						if ( this.value === '' ) {
							text = '-1';
						}
						Settings.Enqueue( 'email' , text );
					}
					if ( Settings.email ) {
						Settings.email = this.value;
					}
				});
				
				//settingsinfo
				$( 'form#settingsinfo div.setting table tbody tr td input' ).click( function() {
					var value = $( this )[ 0 ].checked;
					if ( value ) {
						value = 'yes';
					}
					else {
						value = 'no';
					}
					Settings.Enqueue( $( this )[ 0 ].id , value );
				} );
				$( 'div.savebutton a' ).click( function() {
					if ( !$( this ).hasClass( 'disabled' ) ) {
						Settings.Save( true );
					}
					return false;
				} );
				$( '#avatarlist' ).jqm( {
					trigger : 'div.changeavatar a',
					overlayClass : 'mdloverlay1'
				} );
				$( '#pwdmodal' ).jqm( {
					trigger : 'div.changepwdl a.changepwdlink',
					overlayClass : 'mdloverlay1'
				} );
				$( Settings.oldpassword ).keyup( function( event ) {
					if ( event.keyCode == 13 && !Settings.oldpassworderror ) {
						Settings.newpassword.focus();
					}
					if ( event.keyCode != 13 && Settings.oldpassworderror && Settings.oldpassword.value.length >= 4 ) {
						Settings.oldpassworderror = false;
						$( Settings.oldpassworddiv ).find( 'div div span' ).fadeOut( 300 );
					}

				} );
				
				$( Settings.newpassword ).keyup( function( event ) {
					if ( event.keyCode == 13 && !Settings.newpassworderror ) {
						Settings.renewpassword.focus();
					}
					if ( Settings.newpassworderror && Settings.newpassword.value.length >= 4 ) {
						Settings.newpassworderror = false;
						$( Settings.newpassworddiv ).find( 'div div span' ).fadeOut( 300 );
					}
				} );

				$( Settings.renewpassword ).keyup( function( event ) {
					if ( event.keyCode == 13 && !Settings.renewpassworderror ) {
						$( 'div#pwdmodal div.save a.save' )[ 0 ].focus();
					}
					if ( Settings.renewpassworderror && Settings.renewpassword.value == Settings.newpassword.value ) {
						Settings.renewpassworderror = false;
						$( Settings.renewpassworddiv ).find( 'div div span' ).fadeOut( 300 );
					}
				} );
				$( 'div#pwdmodal div.save a' ).click( function() {
					Settings.ChangePassword( Settings.oldpassword.value , Settings.newpassword.value , Settings.renewpassword.value );
					return false;
				} );
				Settings.oldpassword.focus();
				break;
		}
	},
    SettingsOnLoad : function() {
        Settings.saver = 0;
        Settings.queue = {};
        Settings.showsaving = $( 'div.settings div.sidebar div.saving' );        
        Settings.SwitchSettings( window.location.hash.substr( 1 ) );
    }
};
