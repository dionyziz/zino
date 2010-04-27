var Comment = {
    StillMouse: false,
    New: function() {
        if ( !Comment.StillMouse ) {
            return false;
        }
        
        var newthread;
        var rootparent = $( this ).hasClass( 'talk' );
        var newcomment = $( '.discussion .note .thread.new' );
        
        if ( $( '.discussion .note .thread.new > .author > .avatar' ).length == 0 ) {
            //Comment.LoadAvatar();
        }
        
        if ( rootparent ) {
            newthread = $( '.discussion > .thread.new' );
            if ( newthread.length == 0 ) {
                newthread = newcomment.clone().insertAfter( '.discussion .note' );
                Comment.TextEvents( newthread );
            }
        }
        else {
            newthread = $( this ).siblings( '.thread.new' );
            if( newthread.length == 0 ) {
                newthread = newcomment.clone().insertAfter( this );
                Comment.TextEvents( newthread );
            }
        }
        
        if ( newthread.css( 'display' ) == 'none' || newthread.css( 'height' ) != 'auto' ) {
            Comment.FadeOut( $( '.discussion .thread .thread.new:visible' ) );
            Comment.FadeIn( newthread );
        }
        else {
            Comment.FadeOut( newthread );
        }
        return false;
    },
    TextEvents: function( jQnode ) {
        jQnode.find( 'textarea' ).keydown( function ( event ) {
            if ( event.shiftKey ) {
                return;
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    Comment.FadeOut(  $( this ).closest( '.thread.new' ) );
                    break;
                case 13: // Enter
                    // TODO
                    var parentid;
                    if ( $( this ).closest( '.thread.new' ).parent().hasClass( 'discussion' ) ) {
                        parentid = 0;
                    }
                    else {
                        parentid = $( this ).closest( '.thread.new' ).parent().attr( 'id' ).split( '_' )[ 1 ];
                    }
                    document.body.style.cursor = 'wait';
                    //alert( parentid );
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
                    axslt( wysiwyg, '/social/comment', callback );
                    
                    var message = $( '<div class="message mine"><div class="text" /></div>' );
                    message.find( '.text' ).append( $( this ).val() );
                    $( this ).closest( '.thread.new' ).animate( { 'opacity': 0.7 }, 500 )
                        .find( 'ul.tips' ).hide();
                    $( this ).replaceWith( message );
                    break;
            }
        } );
    },
    FadeOut: function( jQnode ) {
        jQnode.stop().animate(  { 'opacity': 0, 'height': 0 }, 100, 'linear', function() { $( this ).hide(); } );
    },
    FadeIn: function( jQnode ) {
        jQnode.stop().css( { 'opacity': 1, 'height': 'auto' } ).show().fadeIn( 200 )
            .find( 'textarea' ).focus();
    },
    Prepare: function( collection ) {
        $( collection )
            .mousedown( function() { Comment.StillMouse = true; } )
            .mousemove( function() { Comment.StillMouse = false; } )
            .mouseup( function() {
                return Comment.New.call( this );
            } )
            .click( function() { return false; } )
            .find( '.author' ).click( function( event ) {
                event.stopPropagation();
            } );
    },
    LoadAvatar: function() {
        $.get( 'users/view', { 'name': User, 'details': 'false' } );
        $( '<img />' ).appendTo( '.discussion .note .thread.new > .author' );
    }
}