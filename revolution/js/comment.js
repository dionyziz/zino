var Comment = {
    New: function() {
        var parentid;
        var newthread;
        newthread = $( '.discussion .note .thread.new' ).clone()
        if ( $( this ).hasClass( 'talk' ) ) {
            parentid = 0;
            newthread.insertAfter( '.discussion .note' );
        }
        else {
            $( this ).siblings( '.thread.new' ).remove();
            newthread.insertAfter( this );
            parentid = $( this ).closest( '.thread' ).attr( 'id' ).split( '_' )[ 1 ];
        }
        newthread.fadeIn( 200 );
        newthread.find( 'textarea' ).focus().keydown( function ( event ) {
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
                            $( thread ).replaceWith( this ).click( function() { return Comment.New.call( this ); } ).fadeIn( 750 );
                            document.body.style.cursor = 'default';
                        }
                    } )( $( this ).closest( '.thread.new' ) )
                    wysiwyg.transform( callback, '/social/comment' );
                    
                    var message = $( '<div class="message mine"><div class="text" /></div>' );
                    message.find( '.text' ).append( $( this ).val() );
                    $( this ).closest( '.thread.new' ).animate( { 'opacity': 0.7 }, 500 )
                        .find( 'ul.tips' ).hide();
                    $( this ).replaceWith( message );
                    break;
            }
        } );
        return false;
    },
    Init: function() {
        $( '.message .author' ).click( function( event ) {
            event.stopPropagation();
        } );
        $( 'a.talk, .message' ).click( function() { return Comment.New.call( this ); } );
    }
}