var Join = {
	timervar : 0,
	hadcorrect : false,
	usernameerror : false, //used to check if a username has been given
	pwderror : false, //used to check if a password has been given
	repwderror : false, //used to check if password is equal with the retyped password
	shortpwd : false, //used to check if the password is short
	username : $( 'form.joinform div input' )[ 0 ],
	password : $( 'form.joinform div input' )[ 1 ],
	repassword : $( 'form.joinform div input' )[ 2 ],
	email : $( 'form.joinform div input' ) [ 3 ],
	Focusinput : function ( node ) {
		$( node ).css( "border" , "1px solid #bdbdff" );
	},
	Unfocusinput : function ( node ) {
		$( node ).css( "border" , "1px solid #999" );
	},
	Checkpwd : function() {
		var node = $( 'form.joinform div div input' )[ 0 ];
		var pwd = $( 'form.joinform div input' )[ 1 ];
		var div = $( 'form.joinform div div' )[ 0 ];
		if ( Join.timervar !== 0 ) {
			clearTimeout( Join.timervar );
		}
		
		Join.timervar = setTimeout( function() {
			if ( node.value == pwd.value && node.value !== '' && !Join.hadcorrect ) {
				Join.hadcorrect = true;
				$( node ).css( "display" , "inline" );
				var okpwd = document.createElement( 'img' );
				okpwd.src = 'images/button_ok_16.png';
				okpwd.alt = 'Σωστή επαλήθευση';
				okpwd.title = 'Σωστή επαλήθευση';
				$( okpwd ).addClass( 'okpwd' );
				if ( typeof okpwd.style.opacity != 'undefined' ) {
					$( okpwd ).css( "opacity" , "0" );
					$( div ).append( okpwd );
					$( okpwd ).animate( { opacity: "1" } , 2000 ); 
				}
				else {
					$( div ).append( okpwd );
				}
			}
			else {
				var okpwd = $( 'form.joinform div div img.okpwd' )[ 0 ];
				if ( node.value != pwd.value && okpwd ) {
					$( okpwd ).remove();
					Join.hadcorrect = false;
				}
			}
		}, 200 );
	},
	ShowTos : function () {
		var area = $( 'div#join_tos' )[ 0 ].cloneNode( true );
		$( area ).css( "display" , "block" );
		Modals.Create( area, 620, 520 );
	}
};
$( document ).ready( function(){
	$( 'form.joinform div input' ).focus( function() {
		Join.Focusinput( this );
	});
	
	$( $( 'form.joinform div input' )[ 0 ] ).keyup( function() {
		if ( Join.usernameerror ) {
			if ( Join.username.value != '' ) {
				Join.usernameerror = false;
				$( $( 'form.joinform div > span' )[ 0 ] ).animate( { opacity: "0" } , 700 );
			}
		}
	});
	
	$( $( 'form.joinform div input' )[ 1 ] ).keyup( function() {
		if ( Join.pwderror ) {
			if ( Join.password.value != '' ) {
				Join.pwderror = false;
				$( $( 'form.joinform div > span' )[ 1 ] ).animate( { opacity: "0" } , 700 );
			}
		}
	});
	
	$( $( 'form.joinform div input' )[ 2 ] ).keyup( function() {
		if ( Join.repwderror ) {
			if ( Join.repassword.value == Join.password.value ) {
				Join.repwderror = false;
				$( $( 'form.joinform div > span' )[ 2 ] ).animate( { opacity: "0" } , 200 , function() {
					$( $( 'form.joinform div > span' )[ 2 ] ).css( "display" , "none" );
				});
			}
		}
	});
	
	$( 'form.joinform div input' ).blur( function() {
		Join.Unfocusinput( this );
	});
	$( 'form.joinform div input:first' )[ 0 ].focus();
	$( 'form.joinform div div input' ).keyup( function() {
		Join.Checkpwd();
	});
	$( 'form.joinform p a' ).click( function () {
		Join.ShowTos();
		return false;
	});
	$( 'div a.button' ).click( function() {
		if ( Join.username.value == '' ) {
			if ( !Join.usernameerror ) {
				Join.usernameerror = true;
				$( $( 'form.joinform div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
			}
			Join.username.focus();
		}
		if ( Join.password.value == '' ) {
			if ( !Join.pwderror ) {
				Join.pwderror = true;
				$( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 700 );
			}
			Join.password.focus();
		}
		if ( Join.password.value != Join.repassword.value && Join.password.value != '' ) {
			if ( !Join.repwderror ) {
				Join.repwderror = true;
				$( $( 'form.joinform div div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 700 );
			}
			Join.repassword.focus();
		}
		return false;
	});
});