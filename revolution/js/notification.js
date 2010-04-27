var Notifications = {
    TakeOver: function () {
        $( '.col1, .col2' ).remove();
    },
    CreateCommentGUI: function ( entry ) {
        var author = entry.find( 'discussion comment comment author name' ).text();
        var avatar = entry.find( 'discussion comment comment author avatar media' ).attr( 'url' );
        var comment = entry.find( 'discussion comment comment text' ).text(); 
        var published = entry.find( 'discussion comment comment published' ).text(); 
        var parentid = entry.find( 'discussion comment' ).attr( 'id' );
        
        var html =
            '<div class="thread">'
                + '<div class="message">'
                    + '<div class="author">'
                        + '<img class="avatar" src="' + '" alt="' + User + '" />'
                        + '<div class="details">'
                            + '<span class="username">' + User + '</span>'
                            + '<div class="time">' + '</div>'
                        + '</div>'
                    + '</div>'
                    + '<div class="text">' + '</div>'
                    + '<div class="eof"></div>'
                + '</div>'
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
                + '</div>';
            + '</div>';

        $.get( 'comments/' + parentid, {}, function ( res ) {
            alert( 'Got parent details' );
        } );
        $( 'body' ).prepend( html );
    },
    Check: function () {
        if ( typeof User != 'undefined' ) {
            $.get( 'notifications', {}, function ( res ) {
                var entries = $( res ).find( 'feed entry' );
                var entry, author, avatar, comment;
                var panel = document.createElement( 'div' );
                var box;

                panel.className = 'panel bottom';
                panel.innerHTML = '<div class="xbutton"></div><h3>Ενημερώσεις (' + $( res ).find( 'feed' ).attr( 'count' ) + ')</h3>';

                for ( var i = 0; i < entries.length; ++i ) {
                    entry = $( entries[ i ] );
                    author = entry.find( 'discussion comment comment author name' ).text();
                    avatar = entry.find( 'discussion comment comment author avatar media' ).attr( 'url' );
                    comment = entry.find( 'discussion comment comment text' ).text(); 
                    box = document.createElement( 'div' );
                    box.className = 'box';
                    box.innerHTML = '<div><img alt="' + author + '" src="' + avatar + '" /></div><div class="details"><h4>' + author + '</h4><div class="text">' + comment+ '</div></div>';
                    $( box ).click( ( function ( e ) {
                        return function () {
                            Notifications.TakeOver();
                            $( this ).addClass( 'selected' );
                            Notifications.CreateCommentGUI( e );
                        };
                    } )( entry ) );
                    panel.appendChild( box );
                }
                
                $( panel ).find( '.xbutton' ).click( function () {
                    this.parentNode.style.display = 'none';
                } );
                document.body.appendChild( panel );
            } );
        }
    }
};

Notifications.Check();
