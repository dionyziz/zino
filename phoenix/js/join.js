var Join = {
	timervar : 0,
	hadcorrect : false,
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
				$( okpwd ).css( "padding-left" , "5px" );
				if ( typeof okpwd.style.opacity != 'undefined' ) {
					$( okpwd ).css( "opacity" , "0" );
					$( div ).append( okpwd );
					$( okpwd ).animate( {opacity: "1"} , 2000 ); 
				}
				else {
					$( div ).append( okpwd );
				}
			}
			else {
				var okpwd = $( 'form.joinform div img' )[ 0 ];
				if ( node.value != pwd.value && okpwd ) {
					div.removeChild( okpwd );
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
});