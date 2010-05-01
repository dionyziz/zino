var Notifications = {
    TakenOver: false,
    TakeOver: function () {
        Notifications.TakenOver = true;
        $( '.col1, .col2' ).remove();
    },
    Done: function () {
        window.location.reload();
    },
    DoneWithCurrent: function () {
        var current = $( '#notifications .selected' )[ 0 ];
        var next;

        next = current.nextSibling;
        if ( !next ) {
            next = current.previousSibling;
            if ( !next ) {
                Notifications.Done();
            }
        }
        $( current ).remove();
        if ( next ) {
            $( next ).click();
        }
    },
    CreateCommentGUI: function ( entry ) {
        var isreply = entry.find( 'discussion comment comment' ).length > 0; 
        var commentpath;
        var parentid = 0;

        $( '#instantbox' ).remove();

        if ( isreply ) {
            commentpath = 'comment comment';
            parentid = entry.find( 'comment' ).attr( 'id' );
        }
        else {
            commentpath = 'comment';
        }
        var author = entry.find( commentpath + ' author name' ).text();
        var avatar = entry.find( commentpath + ' author avatar media' ).attr( 'url' );
        var comment = innerxml( entry.find( commentpath + ' text' )[ 0 ] );
        var published = entry.find( commentpath + ' published' ).text(); 
        var type = entry.attr( 'type' );
        var commentid = entry.find( commentpath ).attr( 'id' );
        var id = entry.attr( 'id' );

        var notificationcomment = ''
            + '<div class="thread">'
                + '<div class="message">'
                    + '<div class="author">'
                        + '<img class="avatar" src="' + avatar + '" alt="' + author + '" />'
                        + '<div class="details">'
                            + '<span class="username">' + author + '</span>'
                            + '<div class="time">' + published + '</div>'
                        + '</div>'
                    + '</div>'
                    + '<div class="text">' + comment + '</div>'
                    + '<div class="eof"></div>'
                + '</div>'
                + '<div class="note"><strong>Γράψε μία απάντηση:</strong>'
                    + '<div class="thread new">'
                        + '<div class="message mine new">'
                            + '<div><textarea></textarea></div>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>';
        if ( isreply ) {
            var notificationcomment = ''
                + '<div class="thread">'
                    + '<div class="message">'
                        + '<div class="author">'
                            + '<img class="avatar" src="' + '" alt="' + User + '" style="display:none" />'
                            + '<div class="details">'
                                + '<span class="username">' + User + '</span>'
                                + '<div class="time">' + '</div>'
                            + '</div>'
                        + '</div>'
                        + '<div class="text">...</div>'
                        + '<div class="eof"></div>'
                        + notificationcomment
                    + '</div>'
                + '</div>'
        }

        var html =
            '<div id="instantbox">'
                + '<ul class="tips"><li>Enter = <strong>Αποθήκευση απάντησης</strong></li><li>Escape = <strong>Αγνόηση</strong></li><li>Shift + Esc = <strong>Θα απαντήσω αργότερα</strong></li></ul>'
                + '<div class="content"></div>'
                + '<div class="details">'
                    + notificationcomment
                + '</div>'
            + '<div class="eof"></div></div>';

        $( 'body' ).prepend( html );

        $( '#instantbox > .details .new' ).show().find( 'textarea' ).focus().keyup( function ( event ) {
            if ( event.shiftKey ) {
                return;
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    Notifications.DoneWithCurrent();
                    // TODO
                    break;
                case 13: // Enter
                    var commenttext = this.value.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' );
                    if ( commenttext === '' ) {
                        $( '#instantbox textarea' ).css( { 'border': '3px solid red' } )[ 0 ].value = '';
                        break;
                    }
                    $.post( 'comment/create', {
                        text: commenttext,
                        typeid: {
                            'poll': 1,
                            'photo': 2,
                            'user': 3,
                            'journal': 4,
                            'school': 7
                        }[ type ],
                        'itemid': id,
                        'parentid': commentid,
                    } );
                    Notifications.DoneWithCurrent();
                    break;
            }
        } );
        if ( isreply ) {
            $.get( 'comments/' + parentid, {}, function ( res ) {
                $( '.message .author img' ).show()[ 0 ].src = $( res ).find( 'author avatar media' ).attr( 'url' );
                $( '.message .text' )[ 0 ].innerHTML = innerxml( $( res ).find( ' text' )[ 0 ] );
            } );
        }
        var data = $.get( type + 's/' + id, { 'verbose': 0 } );
        axslt( data, '/social/entry', function() {
            $( '#instantbox .content' ).append( $( this ).filter( '.contentitem' ) );
        } );
    },
    Check: function () {
        if ( typeof User != 'undefined' ) {
            $.get( 'notifications', {}, function ( res ) {
                var entries = $( res ).find( 'feed entry' );
                var entry, author, avatar, comment;
                var panel = document.createElement( 'div' );
                var box;

                panel.id = 'notifications';
                panel.className = 'panel bottom';
                panel.innerHTML = '<div class="xbutton"></div><h3>Ενημερώσεις (' + $( res ).find( 'feed' ).attr( 'count' ) + ')</h3>';

                for ( var i = 0; i < entries.length; ++i ) {
                    entry = $( entries[ i ] );
                    author = entry.find( 'discussion comment author name' ).text();
                    avatar = entry.find( 'discussion comment author avatar media' ).attr( 'url' );
                    comment = innerxml( entry.find( 'discussion comment text' )[ 0 ] );
                    box = document.createElement( 'div' );
                    box.className = 'box';
                    box.innerHTML = '<div><img alt="' + author + '" src="' + avatar + '" /></div><div class="details"><h4>' + author + '</h4><div class="text">' + comment+ '</div></div>';
                    $( box ).click( ( function ( e ) {
                        return function () {
                            Notifications.TakeOver();
                            $( '#notifications .box' ).removeClass( 'selected' );
                            $( this ).addClass( 'selected' );
                            Notifications.CreateCommentGUI( e );
                        };
                    } )( entry ) );
                    panel.appendChild( box );
                }
                
                $( panel ).find( '.xbutton' ).click( function () {
                    if ( Notifications.TakenOver ) {
                        Notifications.Done();
                    }
                    this.parentNode.style.display = 'none';
                } );
                document.body.appendChild( panel );
            } );
        }
    }
};

Notifications.Check();
