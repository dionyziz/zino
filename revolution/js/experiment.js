$( 'div.time' ).each( function () {
    this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, Now ) );
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
$( 'a.talk' ).click( function() {
    var thread = document.createElement( 'div' );
    thread.className = 'thread';
    var msg = document.createElement( 'div' );
    msg.className = 'message mine';
    var nnew = document.createElement( 'div' );
    nnew.className = 'new';
    var tx = document.createElement( 'textarea' );
    nnew.appendChild( tx );
    msg.appendChild( nnew );
    thread.appendChild( msg );
    $( $( '.discussion .note' )[ 0 ] ).after( thread );
    tx.focus();
    return false;
} );
