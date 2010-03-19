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
$( '.message .author' ).click( function( event ) {
    event.stopPropagation();
} );
$( '.message' ).click( function( event ) {
    alert( 'Replying' );
} );
$( 'a.talk' ).click( function() {
    $( '.thread .new' ).parent().remove();
    $( $( '.discussion .note' )[ 0 ] ).after(
        '<div class="thread new">' + 
        '<div class="message mine new">' + 
        '<div><textarea></textarea></div>' +
        '<ul class="tips"><li>Enter = <strong>Αποθήκευση</strong></li><li>Escape = <strong>Ακύρωση</strong></li><li>Shift + Enter = <strong>Νέα γραμμή</strong></li><li><a href="">Φάτσες</a></li></ul>' +
        '</div>' +
        '</div>'
    );
    $( '.thread .new textarea' ).focus().keydown( function ( event ) {
        if ( event.shiftKey ) {
            return;
        }
        switch ( event.keyCode ) {
            case 27: // ESC
                $( this.parentNode.parentNode.parentNode ).hide();
                break;
            case 13: // Enter
                document.body.style.cursor = 'wait';
                $( this.parentNode.parentNode.parentNode ).hide();
                // TODO
                $.post( 'comment/create', {
                    text: this.value,
                    typeid: {
                        'poll': 1,
                        'photo': 2,
                        'user': 3,
                        'journal': 4,
                        'school': 7
                    }[ $( '.contentitem' )[ 0 ].id.split( '_' )[ 0 ] ],
                    itemid: $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ],
                    parentid: 0
                } );
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
