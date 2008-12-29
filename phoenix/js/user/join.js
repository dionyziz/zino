var Join = {
	ShowTos : function () {
		var area = $( 'div#join_tos' )[ 0 ].cloneNode( true );
		$( area ).css( "display" , "block" );
		Modals.Create( area, 620, 520 );
	},
    JoinOnLoad : function() {
        Join.timervar = 0;
        Join.hadcorrect = false;
        Join.usernameerror = false; //used to check if a username has been given
        Join.invalidusername = false;
        Join.pwderror = false; //used to check if a password has been given
        Join.repwderror = false; //used to check if password is equal with the retyped password
        Join.usernameexists = false;
        Join.emailerror = false;
        Join.username = $( 'form.joinform div input' )[ 0 ];
        Join.password = $( 'form.joinform div input' )[ 1 ];
        Join.repassword = $( 'form.joinform div input' )[ 2 ];
        Join.enabled = true;
        Join.email = $( 'form.joinform div input' )[ 3 ];
        $( 'form.joinform' ).submit( function() {
            return false;
        } );
        $( 'form.joinform div input' ).focus( function() {
            $( this ).css( "border" , "1px solid #bdbdff" );
        }).blur( function() {
            $( this ).css( "border" , "1px solid #999" );
        });
        $( Join.username ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.usernameerror && !Join.usernameexists && !Join.invalidusername ) {
                Join.password.focus();
            }
        } );
        $( Join.password ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.pwderror ) {
                Join.repassword.focus();
            }
        } );
        $( Join.repassword ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.repwderror ) {
                Join.email.focus();
            }
        } );
        $( Join.email ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.emailerror ) {
                $( 'div a.button' )[ 0 ].focus();
            }
        } );
        $( Join.username ).keydown( function( event ) {
            if ( Join.usernameerror ) {
                if ( Join.username.value.length >= 4 && Join.username.value.length <= 20 ) {
                    Join.usernameerror = false;
                    $( $( 'form.joinform div > span' )[ 0 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css ( "display" , "none");
                    });
                }
            }
            if ( Join.usernameexists ) {
                if ( event.keyCode != 13 ) {
                    Join.usernameexists = false;
                    $( $( 'form.joinform div > span' )[ 1 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                    $( 'div a.button' ).removeClass( 'button_disabled' );
                    Join.enabled = true;
                }
            }
            if ( Join.invalidusername ) {
                Join.invalidusername = false;
                $( $( 'form.joinform div > span' )[ 2 ] ).animate( { opacity: "0" } , 700 , function() {
                    $( this ).css( "display" , "none" );
                });
            }
        });	
        
        $( Join.password ).keyup( function() {
            if ( Join.pwderror ) {
                if ( Join.password.value.length >= 4 ) {
                    Join.pwderror = false;
                    $( $( 'form.joinform div > span' )[ 3 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        $( Join.repassword ).keyup( function() {
            if ( Join.repwderror ) {
                if ( Join.repassword.value == Join.password.value ) {
                    Join.repwderror = false;
                    $( $( 'form.joinform div > span' )[ 4 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        $( Join.email ).keyup( function() {
            if ( Join.emailerror ) {
                if ( Join.email.value === '' || /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( Join.email.value ) ) {
                    Join.emailerror = false;
                    $( $( 'form.joinform div > span' )[ 5 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        if ( Join.username ) {
            Join.username.focus();
        }
        
        $( 'form.joinform p a' ).click( function () {
            Join.ShowTos();
            return false;
        });
        
        $( 'div a.button' ).click( function() {
            var create = true;
            if ( Join.username.value.length < 4 || Join.username.value.length > 20 ) {
                if ( !Join.usernameerror ) {
                    Join.usernameerror = true;
                    $( $( 'form.joinform div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 400 );
                }
                Join.username.focus();
                create = false;
            }
            if ( Join.username.value.length >= 4 && !/^[a-zA-Z][a-zA-Z\-_0-9]{3,49}$/.test( Join.username.value ) ) {
                if ( !Join.invalidusername ) {
                    Join.invalidusername = true;
                    $( $( 'form.joinform div > span' )[ 2 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 400 );
                }
                Join.username.focus();
                create = false;
            }
            if ( Join.password.value.length < 4 ) {
                if ( !Join.pwderror ) {
                    Join.pwderror = true;
                    $( $( 'form.joinform div > span' )[ 3 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
                }
                if ( !Join.usernamerror && !Join.invalidusername && !Join.usernameexists ) {
                    //if the username and password are empty then focus the username inputbox
                    Join.password.focus();
                }
                create = false;
            }
            if ( Join.password.value != Join.repassword.value && !Join.pwderror ) {
                if ( !Join.repwderror ) {
                    Join.repwderror = true;
                    $( $( 'form.joinform div div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
                }
                if ( !Join.usernameerror && !Join.invalidusername && !Join.usernameexists ) {
                    Join.repassword.focus();
                }
                create = false;
            }
            if ( Join.email.value !== '' && !/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( Join.email.value ) ) {
                if ( !Join.emailerror ) {
                    Join.emailerror = true;
                    $( $( 'form.joinform div > span' )[ 5 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
                }
                if ( !Join.usernameerror && !Join.invalidusername && !Join.usernameexists && !Join.pwderror && !Join.repwderror ) {
                    Join.email.focus();
                }
                create = false;
            }
            if ( create ) {
                if ( Join.enabled ) {
                    document.body.style.cursor = 'wait';
                    $( this ).addClass( 'button_disabled' );
                    Coala.Warm( 'user/join' , { username : Join.username.value , password : Join.password.value , email : Join.email.value } );
                }
            }
            return false;
        } );
    }
};
