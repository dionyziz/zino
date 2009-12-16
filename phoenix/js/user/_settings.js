//TODO: remove these ugly newpassworderror etc
var Settings = {
    SectionsArray: [ 'personal', 'characteristics', 'interests', 'contact', 'account' ],
    InputArray: [],
    SectionsLoaded: [],
    CurrentTab: false,
    SavingQueue: {},
    OnLoad: function() {
        $.each( Settings.SectionsArray, function() {
            Settings.SectionsLoaded[ this ] = false; //Initiate the array
            $( "#settingslist li." + this + " a" ).click( function( section ) {
                return function() {
                    Settings.SectionSwitch( section );
                    return false;
                }
            } ( this.toString() ) );
        } );
    },
    SectionSwitch: function( section ) {
        //TODO: sidebar effects
        Settings.CurrentTab = section;
        if ( !Settings.SectionsLoaded[ section ] ) {
            //alert( "preloading " + section );
            Settings.SectionLoad( section );
            return;
        }
        //alert( "loading " + section );
        $( ".settings .tabs form#" + section + "info" ).fadeIn().siblings().fadeOut();
    },
    SectionLoad: function( section ) {
        $( ".settings .tabs form" ).fadeOut();
        $( "#settingsloader" ).center().fadeIn();
        Coala.Cold( 'user/settings/tab', { tab: section } );
    },
    OnTabLoad: function( section ) {
        Settings.SectionsLoaded[ section ] = true;
        Settings.SectionSwitch( section );
        $( '#settingsloader' ).fadeOut();
        switch( section ) {
                                                //---------PERSONAL SETTINGS---------
            case 'personal':
                $( '#dateofbirth select' ).change( function() {
                    var day = $( '#dateofbirth select' )[ 0 ].value;
                    var month = $( '#dateofbirth select' )[ 1 ].value;
                    var year = $( '#dateofbirth select' )[ 2 ].value;

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
                } );
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
                $( '#place select' ).change( function() {
                    Settings.Enqueue( 'place' , this.value );
                });
                $( '#education select' ).change( function() {
                    Settings.Enqueue( 'education' , this.value );
                    //TODO: Callback with a list with schools
                });
                $( '#school select' ).change( function() {
                    Settings.Enqueue( 'school' , this.value );
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
                Settings.CheckInput( '#slogan input', 'slogan' ); 
                Settings.CheckInput( '#aboutme textarea', 'aboutme' ); 
                Settings.CheckInput( '#favquote input', 'favquote' ); 
                                                //---------CHARACTERISTICS SETTINGS---------
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
                                                //---------INTEREST SETTINGS---------
            case 'interests':
                Suggest.OnLoad();
                                                //---------CONTACT SETTINGS---------
            case 'contact':
                Settings.CheckInput( '#msn input', 'msn' ); 
                Settings.CheckInput( '#gtalk input', 'gtalk' ); 
                Settings.CheckInput( '#skype input', 'skype' ); 
                Settings.CheckInput( '#yahoo input', 'yahoo' ); 
                Settings.CheckInput( '#web input', 'web' ); 
                                                //---------ACCOUNT SETTINGS---------
            case 'account':
                Settings.CheckInput( '#email input', 'email', function(x) {
                    if ( Kamibu.ValidEmail( x.val() ) ) {
                        $( '#email span.s1_0007' ).stop().fadeIn();
                        return true;
                    }
                    else {
                        $( '#email span.s1_0007' ).stop().fadeOut();
                        Settings.DisableSave();
                        return false;
                    }
                } );
                $( '#passwordchange' ).modal( $( '#changepwd a' ) );
                $( '#accdelete' ).modal( $( '#delaccount a' ) );
                $( '#passwordchange div.save a' ).click( function() {
                    Settings.ChangePassword();
                    return false;
                } );
                $( '#accdelete .save a' ).click( function () {
                    document.body.style.cursor = 'wait';
                    Coala.Warm( 'user/delete', { password: $( '#accdelete input' )[ 0 ].value } );
                    return false;
                } );
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
        }
    }
    ,
    Enqueue: function( key , value ) {
        Settings.EnableSave();
		Settings.SavingQueue[ key ] = value;
        alert( "setting '" + key + "' to '" + value );
	}
    ,
    Dequeue: function() {
        Settings.SavingQueue = {};
    }
    ,
    EnableSave: function() {
        //$( 'div.savebutton a' ).removeClass( 'disabled' );
    }
    ,
    CheckInput: function( inputElement, inputName, checkValidity ) {
        inputElement = $( inputElement );
        inputElement.keyup( function( inputElement, inputName ) {
            return function() {
                if ( checkValidity !== undefined && checkValidity( inputElement ) == false ) {
                    if ( Settings.InputArray[ inputName ] ) {
                        delete Settings.InputArray[ inputName ];
                        alert( 'did not pass the validation check, removing' );
                    }
                }
                else {
                    if ( !Settings.InputArray[ inputName ] ) {
                        Settings.InputArray[ inputName ] = true;
                        Settings.EnableSave();
                        alert( 'something got changed on ' + inputName );
                    }
                }
            };
        } ( inputElement, inputName ) );
    }
    ,
    AddInterest : function( type , typeid ) {
        //type can be either: hobbies, movies, books, songs, artists, games, quotes, shows
        var intervalue = $( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value;
        if ( $.trim( intervalue ) !== '' ) {
			if ( intervalue.length <= 32 ) {
				var newli = document.createElement( 'li' );
				var newspan = $( 'div.settings div.tabs form#interestsinfo div.creation' )[ 0 ].cloneNode( true );
				$( newspan ).removeClass( 'creation' ).find( 'span.bbblmiddle' ).append( document.createTextNode( intervalue ) );
				var link = newspan.getElementsByTagName( 'a' )[ 0 ];
				$( newli ).append( newspan );
				$( 'div.settings div.tabs form#interestsinfo div.option div.setting ul.' + type ).prepend( newli );
				Suggest.added[ type ].push( intervalue );
				Coala.Warm( 'user/settings/tags/new' , { text : intervalue , typeid : typeid , node : link } );
			}
			else {
				alert( 'Το κείμενό σου μπορεί να έχει 32 χαρακτήρες το πολύ' );
			}
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
		else {
			alert( 'Δε μπορείς να προσθέσεις κενό ενδιαφέρον' );
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
	}
    ,
	RemoveInterest : function( tagid , node ) {
		var parent = node.parentNode.parentNode;
		$( node ).remove();
		$( parent ).hide( 'slow' );
		Coala.Warm( 'user/settings/tags/delete' , { tagid : tagid } );
		return false;
	}
    ,
	ChangePassword : function() {
         oldpassword = $( '#passwordchange .oldpassword input' );
         newpassword = $( '#passwordchange .newpassword input' );
         renewpassword = $( '#passwordchange .renewpassword input' );
		if ( oldpassword.val().length < 4 ) {
			oldpassword.siblings( 'div' ).find( 'span' ).fadeIn( 300 );
			oldpassword.focus();
            return;
		} else {
            oldpassword.siblings( 'div' ).find( 'span' ).fadeOut( 300 );
        }
		if ( newpassword.val().length < 4 ) {
			newpassword.siblings( 'div' ).find( 'span' ).fadeIn( 300 );
			newpassword.focus();
            return;
		} else {
            newpassword.siblings( 'div' ).find( 'span' ).fadeOut( 300 );
        }
		if ( newpassword.val() != renewpassword.val() ) {
			renewpassword.siblings( 'div' ).find( 'span' ).fadeIn( 300 );
			renewpassword.focus();
            return;
		} else {
            renewpassword.siblings( 'div' ).find( 'span' ).fadeOut( 300 );
        }
        Settings.Enqueue( 'oldpassword' , oldpassword.val() );
        Settings.Enqueue( 'newpassword' , newpassword.val() );
        Settings.Save( false );
	}
    ,
    Save: function() {
    }
};
