$.ajaxSetup( {
    dataType: 'xml'
} );
$( 'div.time' ).each( function () {
    this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, Now ) );
    $( this ).addClass( 'processedtime' );
} );

if ( User !== '' ) {
    var favourites = $( 'div.love .username' );
    var faved = false;
    for ( i = 0; i < favourites.length; ++i ) {
        if ( favourites[ i ].innerHTML == User ) {
            // I have already fav'ed this
            faved = true;
            break;
        }
    }

    if ( !faved ) {
        $( 'a.love' ).show();
        if ( $( 'a.love' ).length ) {
            $( 'a.love' )[ 0 ].onclick = function () {
                $.post( 'favourite/create', { typeid: 2, itemid: Which } );
                this.href = '';
                this.style.cursor = 'default';
                this.onclick = function () { return false; };
                this.innerHTML = '&#9829; ' + User;
                var div = document.createElement( 'div' );
                div.style.position = 'absolute';
                div.style.fontSize = '400%';
                div.innerHTML = '&#9829;';
                div.style.top = this.offsetTop - 40 + 'px';
                div.style.left = this.offsetLeft - 10 + 'px'; 
                div.style.color = 'red';
                document.body.appendChild( div );
                $( div ).animate( {
                    top: this.offsetTop - 100,
                    opacity: 0
                }, 'slow' );
                this.blur();
                return false;
            };
        }
    }
}

$( function() {
    $( '.message .author' ).click( function( event ) {
        event.stopPropagation();
    } );
    $( 'a.talk, .message' ).click( function() {
        var parentid;
        if ( $( this ).hasClass( 'talk' ) ) {
            parentid = 0;
        }
        else {
            parentid = $( this ).closest( '.thread' ).attr( 'id' ).split( '_' )[ 1 ];
        }
        $( this ).siblings( '.thread.new' ).remove();
        $( '.discussion .note .thread.new' ).clone().insertAfter( this ).fadeIn( 200 );
        $( '.thread .new textarea' ).focus().keydown( function ( event ) {
            if ( event.shiftKey ) {
                return;
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    $( this ).closest( '.thread.new' ).animate(  { 'opacity': 0, 'height': 0 }, 150, 'linear', function() { $( this ).remove() } );
                    break;
                case 13: // Enter
                    document.body.style.cursor = 'wait';
                    // TODO
                    var wysiwyg = $.post( 'comment/create', {
                        text: this.value,
                        typeid: {
                            'poll': 1,
                            'photo': 2,
                            'user': 3,
                            'journal': 4,
                            'school': 7
                        }[ $( '.contentitem' )[ 0 ].id.split( '_' )[ 0 ] ],
                        'itemid': $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
                        'parentid': parentid } );
                        
                    var callback = ( function( thread ) {
                         return function() {
                            $( thread ).replaceWith( this ).fadeIn( 750 );
                        }
                    } )( $( this ).closest( '.thread.new' ) )
                    
                    wysiwyg.transform( callback, '/social/comment' );
                    
                    var message = $( '<div class="message mine"><div class="text" /></div>' );
                    message.find( '.text' ).append( $( this ).val() );
                    $( this ).closest( '.thread.new' ).css( 'opacity', 0.8 )
                        .find( 'ul.tips' ).hide();
                    $( this ).replaceWith( message );
                    
                    break;
            }
        } );
        return false;
    } );

    $( 'ul.options li input' ).click( function () {
        $.post( 'pollvote/create', {
            pollid: $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
            optionid: this.value
        } );
    } );
} );