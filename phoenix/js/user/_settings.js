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
                //Date Of Birth
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
                } );
                //Gender
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
                                                //---------INTEREST SETTINGS---------
            case 'interests':
                Suggest.OnLoad();
        }
    }
    ,
    Enqueue: function( key , value ) {
        Settings.SavingOn();
		Settings.SavingQueue[ key ] = value;
        alert( "setting '" + key + "' to '" + value );
	}
    ,
    Dequeue: function() {
        Settings.SavingQueue = {};
    }
    ,
    SavingOn: function() {
        //$( 'div.savebutton a' ).removeClass( 'disabled' );
    }
    ,
    CheckInput: function( inputElement, inputName ) {
        $( inputElement ).change( function() {
            return function( inElem, inName ) {
                Settings.SavingOn();
                Settings.InputArray[ inName ] =  $( inputElement );
            } ( inputElement, inputName );
        }
    }
};
