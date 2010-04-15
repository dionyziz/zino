var Comment = {
    FadeOut: function( jQnode ) {
        jQnode.animate(  { 'opacity': 0, 'height': 0 }, 150, 'linear', function() { $( this ).remove() } );
    }
    ,
    New: function() {
        var parentid;
        var newthread;
        newthread = $( '.discussion .note .thread.new' ).clone();
        if ( $( '.discussion .note .thread.new > .author > .avatar' ).length == 0 ) {
            Comment.LoadAvatar();
        }
        if ( $( this ).hasClass( 'talk' ) ) {
            if ( $( '.discussion > .thread.new' ).length != 0 ) {
                Comment.FadeOut( $( '.discussion > .thread.new' ) );
                return false;
            }
            newthread.insertAfter( '.discussion .note' );
            parentid = 0;
        }
        else {
            if ( $( this ).siblings( '.thread.new' ).length != 0 ) {
                Comment.FadeOut( $( this ).siblings( '.thread.new' ) );
                return false;
            }
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
                    Comment.FadeOut(  $( this ).closest( '.thread.new' ) );
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
                            Comment.Prepare( $( this ).find( '.message' ) );
                            newthread = $( thread ).replaceWith( this ).fadeIn( 750 )
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
    Prepare: function( collection ) {
        $( collection ).click( function() {
            return Comment.New.call( this );
        } )
        .find( '.author' ).click( function( event ) {
            event.stopPropagation();
        } );
    },
    LoadAvatar: function() {
        $.get( 'user/view', { 'user': User } );
    }
}