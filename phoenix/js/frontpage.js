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
	$( 'div.inuser div.right div.latest' ).mousemove( function() {
		if ( typeof( timer ) != 'undefined'  && timer ) {
			clearTimeout( timer );
		}
		timer = setTimeout( "Frontpage.Comment.MouseOver=false;Frontpage.Comment.NextComment();" , 1000 );
	
	} );
        $( 'div.inuser div.right div.latest' ).mouseenter( function() {
            Frontpage.Comment.MouseOver = true;
        } ).mouseleave( function() {
            Frontpage.Comment.MouseOver = false;
            Frontpage.Comment.NextComment();
        } );
        Frontpage.Shoutbox.OnLoad();
	},
    Shoutbox: {
        Animating: 0,
        Changed: false,
        Typing: [], // people who are currently typing (not including yourself)
        TypingUpdated: false, // whether "I am typing" has been sent recently (we don't want to send it for every keystroke!)
        TypingCancelTimeout: 0, // this timeout is used to send a "I have stopped typing" request
        OnLoad: function () {
            var textarea = $( 'div#shoutbox div.comments div.newcomment div.text input#shoutbox_text' );
            
            $( 'div#shoutbox div.comments div.newcomment div.bottom input' ).click( function() {
                var list = $( 'div.frontpage div.inuser div#shoutbox div.comments' );
                var text = $( list ).find( 'div.newcomment div.text input' )[ 0 ].value;
                if ( $.trim( text ) === '' || !Frontpage.Shoutbox.Changed ) {
                    alert( 'Δε μπορείς να δημοσιεύσεις κενό μήνυμα' );
                    textarea[ 0 ].value = '';
                    textarea[ 0 ].focus();
                }
                else {
                    var newshout = $( list ).find( 'div.empty' )[ 0 ].cloneNode( true );
                    $( newshout ).removeClass( 'empty' ).insertAfter( $( list ).find( 'div.newcomment' )[ 0 ] ).show().css( "opacity" , "0" ).find( 'div.text' );
                    var copytext = text;
                    $( newshout ).find( 'div.text' ).append( document.createTextNode( copytext ) ); 
                    Coala.Warm( 'shoutbox/new' , { text : text , node : newshout } );
                    Frontpage.Shoutbox.ShowShout( newshout );
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
                var submit = $( '#shoutbox_submit' )[ 0 ];
                if ( $.trim( textarea[ 0 ].value ).length === 0 ) {
                    if ( !submit.disabled ) {
                        submit.disabled = true;
                    }
                }
                else {
                    if ( submit.disabled ) {
                        submit.disabled = false;
                    }
                }
            };
            
            textarea.keyup( function ( e ) {
                if ( e.keyCode == 13 ) { // enter
                    textarea.blur();
                    $( 'div#shoutbox div.comments div.newcomment div.bottom input' ).click();
                }
                else {
                    q();
                }
            } ).keydown( function ( e ) { // send an "I'm typing" request
                if ( Frontpage.Shoutbox.TypingCancelTimeout != 0 ) { // if we were about to send a "I've stopped typing" request...
                    clearTimeout( Frontpage.Shoutbox.TypingCancelTimeout ); // delay it for a while
                }
                Frontpage.Shoutbox.TypingCancelTimeout = setTimeout( function () {
                    Coala.Warm( 'shoutbox/typing', { 'typing': false } ); // OK send the actual "I've stopped typing" request
                }, 10000 ); // send an "I've stopped typing" request if I haven't touched the keyboard for 10 seconds
                if ( Frontpage.Shoutbox.TypingUpdated ) { // We've already sent an "I'm typing" request recently; don't do it again for every keystroke!
                    return;
                }
                Frontpage.Shoutbox.TypingUpdated = true; // OK we're about to send an "I'm typing" request now; make sure we don't send one again very soon
                setTimeout( function () { // After we've sent an "I'm typing" request, we don't want to send more. But only for 10 seconds; we'll send another "I'm typing" request if I'm still typing by then.
                    Frontpage.Shoutbox.TypingUpdated = false;
                }, 10000 );
                Coala.Warm( 'shoutbox/typing', { 'typing': true } ); // OK send the actual request
            } ).change( q ).focus( function() {
                if ( !Frontpage.Shoutbox.Changed ) {
                    textarea[ 0 ].value = '';
                    textarea[ 0 ].style.color = 'black';
                }
            } ).blur( function () {
                q();
                if ( textarea[ 0 ].value === '' ) {
                    textarea[ 0 ].value = 'Πρόσθεσε ένα σχόλιο στη συζήτηση...';
                    textarea[ 0 ].style.color = '#666';
                    Frontpage.Shoutbox.Changed = false;
                }
                else {
                    Frontpage.Shoutbox.Changed = true;
                }
            } ).blur();
            
            textarea[ 0 ].disabled = false;
        },
        OnStartTyping: function ( who ) { // received when someone starts typing
            if ( who.name == GetUsername() ) { // don't show it when you're typing
                return;
            }
            for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
                var typist = Frontpage.Shoutbox.Typing[ i ];
                if ( typist.name == who.name ) {
                    clearTimeout( typist.timeout );
                    // in case the typing user gets disconnected and is unable to send us a 
                    // "stopped typing" comet request, time it out after 20,000 milliseconds
                    // of no "started typing" comet requests
                    // (also in case we receive the asynchronous "I'm typing" and "I've stopped typing"
                    // requests in the wrong order -- very improbable but possible)
                    Frontpage.Shoutbox.Typing[ i ].timeout = setTimeout( function () {
                        Frontpage.Shoutbox.OnStopTyping( who );
                    }, 20000 );
                    return;
                }
            }
            who.timeout = setTimeout( function () {
                Frontpage.Shoutbox.OnStopTyping( who );
            }, 20000 ); // in case the remote party gets disconnected
            Frontpage.Shoutbox.Typing.push( who );
            Frontpage.Shoutbox.UpdateTyping();
        },
        OnStopTyping: function ( who ) { // received when someone stops typing
            var found = false;
            
            for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
                var typist = Frontpage.Shoutbox.Typing[ i ];
                if ( typist.name == who.name ) {
                    Frontpage.Shoutbox.Typing.splice( i, 1 );
                    found = true;
                    break;
                }
            }
            if ( !found ) {
                return;
            }
            Frontpage.Shoutbox.UpdateTyping();
        },
        UpdateTyping: function () { // show who's typing
            var typetext = '';
            
            function ucfirst( str ) {
                str += '';
                var f = str.charAt( 0 ).toUpperCase();
                return f + str.substr( 1 );
            }
            
            if ( Frontpage.Shoutbox.Typing.length ) {
                var typists = [];
                for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
                    var typist = Frontpage.Shoutbox.Typing[ i ];
                    var text;
                    
                    if ( typist.gender == 'f' ) {
                        text = 'η ';
                    }
                    else {
                        text = 'ο ';
                    }
                    text += typist.name;
                    typists.push( text );
                }
                
                if ( typists.length == 1 ) {
                    typetext = ucfirst( typists.pop() ) + ' πληκτρολογεί...';
                }
                else {
                    typists.push( 'και ' + typists.pop() );
                    typetext = ucfirst( typists.join( ', ' ) ) + ' πληκτρολογούν...';
                }
            }
            
            var typingdiv = $( 'div#shoutbox div.comments div.newcomment div.bottom div.typing' );
            
            if ( typetext === '' ) {
                typingdiv.css( 'opacity', 1 ).animate( { 'opacity': 0 } );
            }
            else {
                typingdiv.css( 'opacity', 0 ).animate( { 'opacity': 1 } );
                typingdiv[ 0 ].innerHTML = typetext;
            }
        },
        OnMessageArrival: function ( shoutid, shouttext, who ) {
            if ( who.name == GetUsername() ) {
                return;
            }
            
            Frontpage.Shoutbox.OnStopTyping( { 'name': who.name } );
            
            var avatar;
            var whodiv = document.createElement( 'div' );
            var text = document.createElement( 'div' );
            
            if ( who.avatar !== 0 ) {
                avatar = 'http://images.zino.gr/media/'
                                + who.id + '/' + who.avatar + '/' + who.avatar 
                                + '_100.jpg';
            }
            else {
                avatar = 'http://static.zino.gr/phoenix/anonymous100.jpg';
            }
            
            whodiv.className = 'who';
            whodiv.innerHTML = '<a href="http://' + who.subdomain + '.zino.gr/">'
                            + '<img src="' + avatar + '" width="50" height="50" alt="' 
                            + who.name + '" class="avatar" />'
                            + who.name + '</a>' + ' είπε:';
            text.className = 'text';
            text.innerHTML = shouttext;
            
            var div = document.createElement( 'div' );
            div.id = 's_' + shoutid;
            div.className = 'comment';
            div.appendChild( whodiv );
            div.appendChild( text );
            
            var comments = $( 'div#shoutbox div.comments' );
            comments[ 0 ].insertBefore( div, comments.find( 'div.comment' )[ 1 ] );
            
            Frontpage.Shoutbox.ShowShout( div );
        },
        ShowShout: function( node ) {
            var targetHeight = node.offsetHeight;
            var comments = $( 'div#shoutbox div.comments div.comment' );
            var i = 0;
            
            for ( i = comments.length - 2; i >= 1; --i ) { // messages can be posted fast; multiple ones within 500ms :)
                if ( typeof comments[ i ].beingRemoved == 'undefined' ) {
                    comments[ i ].style.marginTop = 0;
                    comments[ i ].style.marginBottom = 0;
                    comments[ i ].beingRemoved = true;
                    break;
                }
            }
            
            $( comments[ i ] ).animate( {
                height: 0,
                opacity: 0
            }, 500, 'linear' );
            node.style.height = '0';
            $( node ).css( 'opacity', 0 );
            $( node ).animate( {
                height: targetHeight,
                opacity: 1
            }, 500, 'linear', function () {
                $( comments[ i ] ).remove();
                --Frontpage.Shoutbox.Animating;
            } );
            
            ++Frontpage.Shoutbox.Animating;
        }        
    },
    Comment : {
        Animating : false,
        Queue : new Array(),
        MouseOver : false,
        ShowComment : function( node , timerint ) {
            Frontpage.Comment.Animating = true;
            setTimeout( "Frontpage.Comment.Animating = false;Frontpage.Comment.NextComment()" , timerint );
            $( 'div.latest div.comments div.list' ).prepend( node );
            var targetheight = $( 'div.latest div.comments div.list div.event' )[ 0 ].offsetHeight;
            node.style.height = '0';
            $( node ).css( 'opacity' , '0' ).animate( {
                height: targetheight,
                opacity: "1"
            } , 350 , 'linear' );
            $( 'div.latest div.comments div.list>div:last-child' ).animate( {
                height: "0",
                opacity: "0"
            } , 350 , 'linear' , function() {
                $( this ).remove();
            } );
        },
        NextComment : function() {
            if ( Frontpage.Comment.Queue.length == 0 ) {
                return;
            }
            /*
            if ( Frontpage.Comment.Queue.length > 20 ) {
                Frontpage.Comment.Queue.slice( 10 , Frontpage.Comment.Queue.length );
            }
            */
            if ( Frontpage.Comment.MouseOver ) {
	    	timerval = 7000;	
            }
            else {
                if ( Frontpage.Comment.Queue.length <= 7 ) {
                    timerval = 5000;
                }
                else if ( Frontpage.Comment.Queue.length <= 15 ) {
                    timerval = 3000;
                }
                else {
                    timerval = 2000;
                }
            }
            Frontpage.Comment.ShowComment( Frontpage.Comment.Queue.pop() , timerval );
        }
    }
};


