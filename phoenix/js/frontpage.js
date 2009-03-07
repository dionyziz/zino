var Frontpage = {
	Closenewuser : function ( node ) {
		$( 'div.frontpage div.ybubble' ).animate( { height : '0'} , 800 , function() {
			$( this ).remove();
		} );
	},
	DeleteShout : function( shoutid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' ) ) {
			$( 'div#s_' + shoutid ).animate( { height : "0" , opacity : "0" } , 300 , function() {
				$( this ).remove();
			} );
			Coala.Warm( 'shoutbox/delete' , { shoutid : shoutid } );
		    return false;
		}
	},
    FrontpageOnLoad : function() {
        if ( $( 'div.members div.join' )[ 0 ] ) {
            $( 'div.members div.join input' )[ 1 ].focus();
        }
		if ( $( 'div.frontpage div.ybubble' )[ 0 ] ) {
			$( '#selectplace select' ).change( function() {
				var place = $( '#selectplace select' )[ 0 ].value;
				$( 'div.ybubble div.body div.saving' ).removeClass( 'invisible' );
				Coala.Warm( 'frontpage/welcomeoptions' , { place : place } );
			} );
			$( '#selecteducation select' ).change( function() {
				var edu = $( '#selecteducation select' )[ 0 ].value;
				$( 'div.ybubble div.body div.saving' ).removeClass( 'invisible' );
				Coala.Warm( 'frontpage/welcomeoptions' , { education : edu } );
			} );
			$( '#selectuni select' ).change( function() {
				var uni = $( '#selectuni select' )[ 0 ].value;
                $( 'div.ybubble div.body div.saving' ).removeClass( 'invisible' );
				Coala.Warm( 'frontpage/welcomeoptions' , { university : uni } );
			} );
		}
		if ( $( 'div.frontpage div.notifications div.list' )[ 0 ] ) {
			var notiflist = $( 'div.notifications div.list' )[ 0 ];
			var notiflistheight = $( notiflist )[ 0 ].offsetHeight;
			
			$( 'div.notifications div.list div.event' ).mouseover( function() {
				$( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
			} )
			.mouseout( function() {
				$( this ).css( "border" , "0" ).css( "padding" , "5px" );
			} );
            
			$( 'div.notifications div.expand a' ).click( function() {
				if ( $( notiflist ).css( 'display' ) == "none" ) {
					$( 'div.notifications div.expand a' )
					.css( "background-position" , "4px -1440px" )
					.attr( {
						title : 'Απόκρυψη'
					} );
					$( notiflist ).show().animate( { height : notiflistheight } , 400 );
				}
				else {
					$( 'div.notifications div.expand a' )
					.css( "background-position" , "4px -1252px" )
					.attr( {
						title : 'Εμφάνιση'
					} );
					$( notiflist ).animate( { height : "0" } , 400 , function() {
						$( notiflist ).hide();
					} );
				}
				return false;
			} );   
        }
        Frontpage.Shoutbox.OnLoad();
	},
    Shoutbox: {
        Changed: false,
        OnLoad: function () {
            var textarea = $( 'div#shoutbox div.comments div.newcomment div.text textarea' );
            
            $( 'div#shoutbox div.comments div.newcomment div.bottom input' ).click( function() {
                var list = $( 'div.frontpage div.inuser div#shoutbox div.comments' );
                var text = $( list ).find( 'div.newcomment div.text textarea' )[ 0 ].value;
                if ( $.trim( text ) === '' || !Frontpage.Shoutbox.Changed ) {
                    alert( 'Δε μπορείς να δημοσιεύσεις κενό μήνυμα' );
                    textarea[ 0 ].value = '';
                    textarea[ 0 ].focus();
                }
                else {
                    var newshout = $( list ).find( 'div.empty' )[ 0 ].cloneNode( true );
                    $( newshout ).removeClass( 'empty' ).insertAfter( $( list ).find( 'div.newcomment' )[ 0 ] ).show().css( "opacity" , "0" ).animate( { opacity : "1" } , 400 ).find( 'div.text' );
                    var copytext = text;
                    $( newshout ).find( 'div.text' ).append( document.createTextNode( copytext ) ); 
                    Coala.Warm( 'shoutbox/new' , { text : text , node : newshout } );
                    Frontpage.Shoutbox.Changed = false;
                    textarea[ 0 ].value = '';
                    q();
                    setTimeout( function () {
                        textarea[ 0 ].focus();
                    }, 100 );
                }
            } );

            // insert deletion in shoutbox 
            // check if user is logged in
            var username = GetUsername();
            
            if ( username ) {
                $( "div#shoutbox div.comment[id^='s_']" ).each( function() { // match shouts that have an id (exclude the reply)
                    if ( username == $( this ).find( 'div.who a img.avatar' ).attr( 'alt' ) ) {
                        var shoutid = this.id.substr( 2 , this.id.length - 2 );
                        var toolbox = document.createElement( 'div' ); 
                        var deletelink = document.createElement( 'a' );
                        $( deletelink ).attr( 'href' , '' )
                        .css( 'padding-left' , '16px' )
                        .click( function() {
                            return Frontpage.DeleteShout( shoutid );
                        } );
                        $( toolbox ).addClass( 'toolbox' ).append( deletelink );
                        $( this ).prepend( toolbox );
                    }
                } );
            }       

            var q = function () {
                $( '#shoutbox_submit' )[ 0 ].disabled = $.trim( textarea[ 0 ].value ).length == 0;
            };
            
            textarea.keyup( function ( e ) {
                if ( e.keyCode == 13 ) { // enter
                    textarea.blur();
                    $( 'div#shoutbox div.comments div.newcomment div.bottom input' ).click();
                }
                else {
                    q();
                }
            } ).change( q ).focus( function() {
                if ( !Frontpage.Shoutbox.Changed ) {
                    textarea[ 0 ].value = '';
                    textarea[ 0 ].style.color = 'black';
                }
            } ).blur( function () {
                q();
                if ( textarea[ 0 ].value == '' ) {
                    textarea[ 0 ].value = 'Πρόσθεσε ένα σχόλιο στη συζήτηση...';
                    textarea[ 0 ].style.color = '#666';
                    Frontpage.Shoutbox.Changed = false;
                }
                else {
                    Frontpage.Shoutbox.Changed = true;
                }
            } ).blur();
            
            textarea[ 0 ].disabled = false;
        }
    }
};
