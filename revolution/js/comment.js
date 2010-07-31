var Comment = {
    StillMouse: false,
    CommentList: null,
    CurrentCommentPage: 1,
    EndOfComments: false,
    Init: function(){
        if ( window.User ) {
            Comment.Prepare( $( '.discussion:first' ).find( 'a.talk, .message' ) );
        }
        this.CommentList = $( ".discussion" );
        Comment.AssignEvents();
    },
    GetCurrentTypeId: function(){ 
        return {
            'poll': 1,
            'photo': 2,
            'user': 3,
            'journal': 4,
            'school': 7
        }[ $( '.contentitem' )[ 0 ].id.split( '_' )[ 0 ] ];
    },
    GetCurrentItemId: function(){
        return $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ];
    },
    New: function() {
        if ( !Comment.StillMouse ) {
            return false;
        }
        
        var newthread;
        var rootparent = $( this ).hasClass( 'talk' );
        var newcomment = $( '.discussion .note .thread.new' );
        
        if ( $( '.discussion .note .thread.new .author > img' ).length === 0 ) {
            Comment.LoadAvatar();
        }
        
        if ( rootparent ) {
            newthread = $( '.discussion > .thread.new' );
            if ( newthread.length === 0 ) {
                newthread = newcomment.clone().insertAfter( '.discussion .note' );
                Comment.TextEvents( newthread );
            }
            $( 'a.talk' ).fadeOut( 300 );
        }
        else {
            newthread = $( this ).siblings( '.thread.new' );
            if( newthread.length === 0 ) {
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
            var parentid;
            if ( $( this ).closest( '.thread.new' ).parent().hasClass( 'discussion' ) ) {
                parentid = 0;
            }
            else {
                parentid = $( this ).closest( '.thread.new' ).parent().attr( 'id' ).split( '_' )[ 1 ];
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    Comment.FadeOut(  $( this ).closest( '.thread.new' ) );
                    if ( parentid === 0 ) {
                        $( 'a.talk' ).fadeIn( 300 );
                    }
                    break;
                case 13: // Enter
                    // TODO
                    document.body.style.cursor = 'wait';
                    
                    var wysiwyg = $.post( 'comment/create', {
                        text: this.value,
                        typeid: Comment.GetCurrentTypeId(),
                        'itemid': Comment.GetCurrentItemId(),
                        'parentid': parentid } );
                        
                    var callback = ( function( thread ) {
                         return function() {
                            newthread = $( this ).filter( '.thread' );
                            Comment.Prepare( $( newthread ).find( '.message' ) );
                            $( thread ).replaceWith( newthread );
                            newthread.css( { 'opacity': 0.6 } ).animate( { 'opacity': 1 }, 250 );
                            document.body.style.cursor = 'default';
                        };
                    } )( $( this ).closest( '.thread.new' ) );

                    axslt( wysiwyg, '/social/comment', callback );
                    
                    var thread = $( this ).closest( '.thread.new' );
                    
                    thread.removeClass( 'new' )
                        .find( '.message.new' )
                        .removeClass( 'new' )
                        .find( '.author .details' )
                        .append( $( '<span />' ).addClass( 'username' ).text( User ) );
                    thread.find( 'ul.tips' )
                        .hide();
                    
                    thread.animate( { 'opacity': 0.6 }, 500 );
                    var text =  $( this ).val();
                    $( this ).parent().empty().append( text );
                    break;
            }
        } );
    },
    FadeOut: function( jQnode ) {
        jQnode.stop().animate(  { 'opacity': 0, 'height': 0 }, 100, 'linear', function() { $( this ).hide(); } );
    },
    FadeIn: function( jQnode ) {
        jQnode.stop().css( { 'opacity': 1, 'height': 'auto' } ).show().fadeIn( 170 )
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
        $( '.thread.new .author' ).each( function( i, e ) {
            var img = $( '<img />' ).addClass( 'avatar' ).prependTo( e );
        } );
        $.get( 'users/view', { 'name': User, 'verbose': 1 }, function( xml ) {
            var src = $( 'avatar > media', xml ).attr( 'url' );
            $( '.thread.new .author > img' ).each( function( i, e ) {
                $( e ).attr( 'src', $( 'avatar > media', xml ).attr( 'url' ) );
            } );
        } );
    },
    /*Infinite Scroll Code*/
    ScrollHandler: function(){
        if( Comment.CommentList.height() - $( window ).scrollTop() - $( window ).height() < 500 ){
            Comment.RemoveEvents();
            Comment.FetchNewComments();
        }
    },
    AssignEvents: function(){
        $( window ).bind( 'scroll', Comment.ScrollHandler );
    },
    RemoveEvents: function(){
        $( window ).unbind( 'scroll', Comment.ScrollHandler );
    },
    FetchNewComments: function(){
        Comment.CurrentCommentPage++;
        var data = $.get( 'comments/' + Comment.GetCurrentTypeId() + '/' + Comment.GetCurrentItemId(), { 'page': Comment.CurrentCommentPage } );
        axslt( data, '/social/discussion/*', function() {
            if( this.length === 0 ) {
                Comment.EndOfComments = true;
            }
            if ( window.User ) {
                Comment.Prepare( $( '.message', this ) );
            }
            $( '.time', this ).each( function () {
                this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, Now ) );
                $( this ).addClass( 'processedtime' );
                
            });
            Comment.CommentList.append( $( this ) );
            if( !Comment.EndOfComments ){
                Comment.AssignEvents();
            }
        } );

    }
    /*END*/
};
