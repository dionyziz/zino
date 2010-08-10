var Comment = {
    StillMouse: false,
    CommentList: null,
    CurrentCommentPage: 1,
    EndOfComments: false,
    Init: function(){
        if ( window.User ) {
            $( 'a.talk, .message' )
            .live( 'mousedown', function(){ Comment.StillMouse = true; } )
            .live( 'mousemove',   function(){ Comment.StillMouse = false; } )
            .live( 'click', function( e ){
                if( $( e.originalTarget ).closest( 'a' ).length ){ //is or is included in an anchor
                    window.open( $( e.originalTarget ).children().andSelf().closest( 'a' ).attr( 'href' ) );
                    return false;
                }
                return Comment.New.call( this );
            });
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
        }[ $( '#content div:first' ).attr( 'id' ).split( '_' )[ 0 ] ];
    },
    GetCurrentItemId: function(){
        return $( '#content div:first' ).attr( 'id' ).split( '_' )[ 1 ];
    },
    New: function() {
        if ( !Comment.StillMouse ) {
            return false;
        }
        
        var $newthread;
        var rootparent = $( this ).hasClass( 'talk' );
        var $newcomment = $( '.discussion .note .thread.new' );
        
        if ( $( '.discussion .note .thread.new .author > img' ).length === 0 ) {
            Comment.LoadAvatar();
        }
        
        var parentid;

        if ( rootparent ) {
            $newthread = $( '.discussion > .thread.new' );
            parentid = 0;
            if ( $newthread.length === 0 ) {
                $newthread = $newcomment.clone().insertAfter( '.discussion .note' );
                Comment.TextEvents( $newthread, parentid );
            }
            // $( 'a.talk' ).fadeOut( 300 );
        }
        else {
            $newthread = $( this ).siblings( '.thread.new' );
            if ( $newthread.length === 0 ) {
                $newthread = $newcomment.clone().insertAfter( this );
                parentid = $newthread.parent().attr( 'id' ).split( '_' )[ 1 ];
                Comment.TextEvents( $newthread, parentid );
            }
            else {
                parentid = $newthread.parent().attr( 'id' ).split( '_' )[ 1 ];
            }
        }
        
        if ( $newthread.css( 'display' ) == 'none' || $newthread.css( 'height' ) != 'auto' ) {
            Comment.FadeOut( $( '.discussion .thread .thread.new:visible' ) );
            Comment.FadeIn( $newthread );
        }
        else {
            Comment.FadeOut( $newthread );
        }
        $newthread.find( 'a' ).eq( 0 ).click( function () {
            Comment.Post( $newthread, parentid );
            return false;
        } );
        $newthread.find( 'a' ).eq( 1 ).click( function () {
            Comment.Cancel( $newthread, parentid );
            return false;
        } );
        $newthread.find( 'a' ).eq( 2 ).click( function () {
            var textarea = $newthread.find( 'textarea' )[ 0 ];

            textarea.value += "\n";
            textarea.focus();
            return false;
        } );
        $newthread.find( 'a' ).eq( 3 ).click( function () {
            axslt( false, 'call:comment.modal.smileys', function() {
                $( this ).filter( 'div' ).prependTo( 'body' ).modal();
            } );
            return false;
        } );
        return false;
    },
    Cancel: function ( jQnode, parentid ) {
        Comment.FadeOut( jQnode );
        if ( parentid === 0 ) {
            $( 'a.talk' ).fadeIn( 300 );
        }
    },
    Post: function ( jQnode, parentid ) {
        document.body.style.cursor = 'wait';
        
        var textarea = jQnode.find( 'textarea' )[ 0 ];
        var checktxt = textarea.value.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' );
        var txt = textarea.value;
        if ( checktxt.length == 0 ) {
            document.body.style.cursor = 'default';
            return;
        }
        var wysiwyg = $.post( 'comment/create', {
            text: txt,
            typeid: Comment.GetCurrentTypeId(),
            'itemid': Comment.GetCurrentItemId(),
            'parentid': parentid
        } );
            
        var callback = ( function( thread ) {
             return function() {
                var newthread = $( this ).filter( '.thread' );
                $( thread ).replaceWith( newthread );
                newthread.css( { 'opacity': 0.6 } ).animate( { 'opacity': 1 }, 250 );
                document.body.style.cursor = 'default';
            };
        } )( jQnode );

        axslt( wysiwyg, '/social/comment', callback );
        
        jQnode.removeClass( 'new' )
            .find( '.message.new' )
            .removeClass( 'new' )
            .find( '.author .details' )
            .append( $( '<span />' ).addClass( 'username' ).text( User ) );
        jQnode.find( 'ul.tips' )
            .hide();
        
        jQnode.animate( { 'opacity': 0.6 }, 500 );
        var text =  $( textarea ).val();
        $( textarea ).parent().empty().append( text );
    },
    TextEvents: function( jQnode, parentid ) {
        jQnode.find( 'textarea' ).keydown( function ( event ) {
            if ( event.shiftKey ) {
                return;
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    Comment.Cancel( jQnode, parentid );
                    break;
                case 13: // Enter
                    Comment.Post( jQnode, parentid );
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
    LoadAvatar: function() {
        $( '.thread.new .author' ).each( function( i, e ) {
            var img = $( '<img />' ).addClass( 'avatar' ).prependTo( e );
        } );
        $.get( 'users/' + User, { 'verbose': 1 }, function( xml ) {
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
        axslt( data, '/social/discussion/comment', function() {
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
