var Notifications = {
    TakenOver: false,
    PendingRequests: 0,
    RequestDone: function () {
        --Notifications.PendingRequests;
        // document.title = 'Waiting... ' + Notifications.PendingRequests;
    },
    RequestStart: function () {
        ++Notifications.PendingRequests;
        // document.title = 'Waiting... ' + Notifications.PendingRequests;
    },
    TakeOver: function () {
        Notifications.TakenOver = true;
        $( '.col1, .col2' ).remove();
    },
    Navigate: function ( url ) {
        document.body.style.cursor = 'wait';
        $( 'body' ).empty();
        $( 'body' ).append(
              '<div class="wait">'
                + '<div class="progressbar">'
                    + '<div class="progress"></div>'
                + '</div>'
            + '</div>'
        );
        $( '.progress' ).css( { width: '25px' } );
        $( '.progress' ).animate( {
            width: '300px'
        }, 500 );
        var LetFinish = 30;
        var Leave = function () {
            if ( Notifications.PendingRequests ) {
                // wait for pending requests to complete
                --LetFinish;
                if ( LetFinish ) {
                    setTimeout( Leave, 100 );
                    return;
                }
            }
            // else
            window.location.href = url;
        };
        Leave();
    },
    Done: function () {
        Notifications.Navigate( '' );
    },
    DoneWithCurrent: function () {
        var current = $( '#notifications .selected' )[ 0 ];
        var next;
        var count = $( '#notifications h3 span' ).text() - 1;

        $( current ).addClass( 'done' ).removeClass( 'selected' ).empty().html( '&#10003;' );

        setTimeout( function () {
            $( current ).remove();
        }, 800 );

        $( '#notifications h3 span' ).text( count );
        do {
            next = current.nextSibling;
        } while ( next && $( next ).hasClass( '.done' ) );

        if ( !next ) {
            do {
                next = current.previousSibling;
            } while ( next && $( next ).hasClass( '.done' ) );
        }
        if ( count ) {
            $( next ).click();
        }
        else {
            Notifications.Done();
        }
    },
    CreateFriendGUI: function ( entry ) {
        $( '#instantbox' ).remove();

        var userid = entry.attr( 'id' );
        var gender = entry.find( 'gender' ).text();
        var author = entry.find( 'name' ).text(); 
        var avatar = entry.find( 'avatar media' ).attr( 'url' );
        var humangender = 'Αγόρι';
        var article = 'Ο';
        var artacc = 'τον';
        var article2 = 'του';

        if ( gender == 'f' ) { 
            humangender = 'Κορίτσι';
            article = 'Η';
            artacc = 'την';
            article2 = 'της';
        }
        var humanlocation = entry.find( 'location' ).text();
        var humanage = entry.find( 'age' ).text();

        var businesscard = ''
                + '<div class="businesscard">'
                    + '<div class="avatar"><img src="' + avatar + '" alt="' + author + '" /></div>'
                    + '<div class="username">' + author + '</div>'
                    + '<ul class="details">'
                        + '<li>' + humangender + ' &#8226;</li>'
                        + '<li>' + humanage + ' &#8226;</li>'
                        + '<li>' + humanlocation + '</li>'
                    + '</ul>'
                + '</div>'

        var html =
            '<div id="instantbox">'
                + '<div class="details">'
                    + '<p><strong>' + article + ' ' + author + ' σε πρόσθεσε στους φίλους.</strong></p>'
                    + businesscard
                + '</div>'
            + '<div class="eof"></div></div>';

        $( 'body' ).prepend( html );
        
        Notifications.RequestStart();
        $.get( 'friendship/' + author, {}, function ( res ) {
            Notifications.RequestDone();
            var users = $( res ).find( 'user knows user name' );

            for ( var i = 0; i < users.length; ++i ) {
                if ( $( users[ i ] ).text() == author ) {
                    // You have already befriended them
                    $( '#instantbox div.details' ).append(
                          '<div class="note"><p><strong>Γράψε ένα σχόλιο στο προφίλ ' + article2 + ':</strong></p>'
                        + '<div class="thread new">'
                            + '<div class="message mine new">'
                                + '<div><textarea></textarea></div>'
                            + '</div>'
                        + '</div>'
                        + '<p>Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p></div>'
                    );
                    $( '#instantbox' ).prepend( '<ul class="tips"><li>Enter = <strong>Αποθήκευση μηνύματος</strong></li><li>Escape = <strong>Αγνόηση</strong></li><li>Shift + Esc = <strong>Θα το δω μετά</strong></li></ul>' );

                    $( '#instantbox > .details .new' ).show().find( 'textarea' ).focus().keyup( function ( event ) {
                        if ( event.shiftKey ) {
                            if ( event.keyCode == 27 ) { // ESC
                            }
                            return;
                        }
                        switch ( event.keyCode ) {
                            case 27: // ESC
                                Notifications.DoneWithCurrent();
                                Notifications.RequestStart();
                                $.post( 'notification/delete', {
                                    friendname: author
                                }, Notifications.RequestDone );
                                break;
                            case 13: // Enter
                                var commenttext = this.value.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' );
                                if ( commenttext === '' ) {
                                    $( '#instantbox textarea' ).css( { 'border': '3px solid red' } )[ 0 ].value = '';
                                    break;
                                }
                                Notifications.RequestStart();
                                $.post( 'comment/create', {
                                    text: commenttext,
                                    typeid: user = 3,
                                    'itemid': userid,
                                    'parentid': 0
                                }, Notifications.RequestDone );
                                Notifications.RequestStart();
                                $.post( 'notification/delete', {
                                    friendname: author
                                }, Notifications.RequestDone );
                                Notifications.DoneWithCurrent();
                                break;
                        }
                    } );
                    return;
                }
            }
            // you have not friended them
            $( '#instantbox div.details' ).append( '<a class="friend" href="">Πρόσθεσέ ' + artacc + '!</a>' );
            $( '#instantbox' ).prepend( '<ul class="tips"><li>Enter = <strong>Προσθήκη φίλου</strong></li><li>Escape = <strong>Αγνόηση</strong></li><li>Shift + Esc = <strong>Θα το δω μετά</strong></li></ul>' );
            $( '#instantbox a.friend' ).focus().click( function () {
                Notifications.RequestStart();
                $.post( 'friendship/create', {
                    username: author
                }, Notifications.RequestDone );
                return false;
            } );
        } );
    },
    CreateFavouriteGUI: function ( entry ) {
        $( '#instantbox' ).remove();

        var id = entry.attr( 'id' );
        var author = entry.find( 'favourites user name' ).text();
        var gender = entry.find( 'favourites user gender' ).text();
        var userid = entry.find( 'favourites user' ).attr( 'id' );
        var article = 'Ο';
        var article2 = 'του';
        var humangender = 'Αγόρι';

        if ( gender == 'f' ) { 
            article = 'Η';
            article2 = 'της';
            humangender = 'Κορίτσι';
        }
        var avatar = entry.find( 'favourites user avatar media' ).attr( 'url' );
        var type = entry.attr( 'type' );
        var humantype = {
            'photo': 'τη φωτογραφία',
            'poll': 'τη δημοσκόπηση',
            'journal': 'το ημερολόγιο'
        }[ type ];
        var humanlocation = entry.find( 'favourites user location' ).text();
        var humanage = entry.find( 'favourites user age' ).text();

        var notificationfavourite = ''
            + '<div class="thread">'
                + '<div class="note">'
                    + '<div class="businesscard">'
                        + '<div class="avatar"><img src="' + avatar + '" alt="' + author + '" /></div>'
                        + '<div class="username">' + author + '</div>'
                        + '<ul class="details">'
                            + '<li>' + humangender + ' &#8226;</li>'
                            + '<li>' + humanage + ' &#8226;</li>'
                            + '<li>' + humanlocation + '</li>'
                        + '</ul>'
                    + '</div>'
                    + '<p><strong>' + article + ' ' + author + ' αγαπάει ' + humantype + ' σου.</strong></p>'
                    + '<p><strong>Γράψε ένα σχόλιο στο προφίλ ' + article2 + ':</strong></p>'
                    + '<div class="thread new">'
                        + '<div class="message mine new">'
                            + '<div><textarea></textarea></div>'
                        + '</div>'
                    + '</div>'
                    + '<p>Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</p>'
                + '</div>'
            + '</div>';
        var html =
            '<div id="instantbox">'
                + '<ul class="tips"><li>Enter = <strong>Αποθήκευση μηνύματος</strong></li><li>Escape = <strong>Αγνόηση</strong></li><li>Shift + Esc = <strong>Θα το δω μετά</strong></li></ul>'
                + '<div class="content"><div class="tips">Πάτα για μεγιστοποίηση</div></div>'
                + '<div class="details">'
                    + notificationfavourite
                + '</div>'
            + '<div class="eof"></div></div>';

        $( 'body' ).prepend( html );

        $( '#instantbox > .details .new' ).show().find( 'textarea' ).focus().keyup( function ( event ) {
            if ( event.shiftKey ) {
                if ( event.keyCode == 27 ) { // ESC
                }
                return;
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    Notifications.RequestStart();
                    $.post( 'notification/delete', {
                        favouritetype: type,
                        favouriteitemid: id,
                        favouriteuserid: userid
                    }, Notifications.RequestDone );
                    Notifications.DoneWithCurrent();
                    break;
                case 13: // Enter
                    var commenttext = this.value.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' );
                    if ( commenttext === '' ) {
                        $( '#instantbox textarea' ).css( { 'border': '3px solid red' } )[ 0 ].value = '';
                        break;
                    }
                    Notifications.RequestStart();
                    $.post( 'comment/create', {
                        text: commenttext,
                        typeid: user = 3,
                        'itemid': userid,
                        'parentid': 0
                    }, Notifications.RequestDone );
                    Notifications.RequestStart();
                    $.post( 'notification/delete', {
                        favouritetype: type,
                        favouriteitemid: id,
                        favouriteuserid: userid
                    }, Notifications.RequestDone );
                    Notifications.DoneWithCurrent();
                    break;
            }
        } );
        Notifications.RequestStart();
        var data = $.get( type + 's/' + id, { 'verbose': 0 } );
        axslt( data, '/social/entry', function() {
            $( '#instantbox .content' ).append( $( this ).filter( '.contentitem' ) );
            Notifications.RequestDone();
        } );
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
                + '<ul class="tips"><li>Enter = <strong>Αποθήκευση απάντησης</strong></li><li>Escape = <strong>Αγνόηση</strong></li><li>Shift + Esc = <strong>Θα το δω μετά</strong></li></ul>'
                + '<div class="content"><div class="tips">Πάτα για μεγιστοποίηση</div></div>'
                + '<div class="details">'
                    + notificationcomment
                + '</div>'
            + '<div class="eof"></div></div>';

        $( 'body' ).prepend( html );

        $( '#instantbox .content' ).click( function () {
            Notifications.Navigate( type + 's/' + id );
        } );
        $( '#instantbox > .details .new' ).show().find( 'textarea' ).focus().keyup( function ( event ) {
            if ( event.shiftKey ) {
                // TODO
                return;
            }
            switch ( event.keyCode ) {
                case 27: // ESC
                    Notifications.DoneWithCurrent();
                    Notifications.RequestStart();
                    $.post( 'notification/delete', {
                        'itemid': commentid,
                        'eventtypeid': EVENT_COMMENT_CREATED = 4,
                    }, Notifications.RequestDone );
                    break;
                case 13: // Enter
                    var commenttext = this.value.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' );
                    if ( commenttext === '' ) {
                        $( '#instantbox textarea' ).css( { 'border': '3px solid red' } )[ 0 ].value = '';
                        break;
                    }
                    Notifications.RequestStart();
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
                        'parentid': commentid
                    }, Notifications.RequestDone );
                    Notifications.DoneWithCurrent();
                    break;
            }
        } );
        if ( isreply ) {
            Notifications.RequestStart();
            $.get( 'comments/' + parentid, {}, function ( res ) {
                Notifications.RequestDone();
                $( '.message .author img' ).show()[ 0 ].src = $( res ).find( 'author avatar media' ).attr( 'url' );
                $( '.message .text' )[ 0 ].innerHTML = innerxml( $( res ).find( ' text' )[ 0 ] );
            } );
        }
        Notifications.RequestStart();
        var data = $.get( type + 's/' + id, { 'verbose': 0 } );
        axslt( data, '/social/entry', function() {
            $( '#instantbox .content' ).append( $( this ).filter( '.contentitem' ) );
            Notifications.RequestDone();
        } );
    },
    Check: function () {
        if ( typeof User != 'undefined' ) {
            $.get( 'notifications', {}, function ( res ) {
                var entries = $( res ).find( 'stream > entry, stream > user' );
                var entry, author, avatar, comment;
                var panel = document.createElement( 'div' );
                var box;
                var eventtype;

                if ( !entries.length ) {
                    // no new notifications
                    return;
                }

                panel.id = 'notifications';
                panel.className = 'panel bottom novideo';
                panel.innerHTML = '<div class="background"></div><div class="xbutton"></div><h3>Ενημερώσεις (<span>' + $( res ).find( 'stream' ).attr( 'count' ) + '</span>)</h3>';

                for ( var i = 0; i < entries.length; ++i ) {
                    entry = $( entries[ i ] );
                    if ( entry.find( 'discussion' ).length ) { // comment notification
                        eventtype = 'comment';
                    }
                    else if ( entry.find( 'favourites' ).length ) { // favourites notification
                        eventtype = 'favourite';
                    }
                    else {
                        eventtype = 'friend';
                    }

                    box = document.createElement( 'div' );
                    box.className = 'box';
                    switch ( eventtype ) {
                        case 'comment':
                            author = entry.find( 'discussion comment author name' ).text();
                            avatar = entry.find( 'discussion comment author avatar media' ).attr( 'url' );
                            comment = innerxml( entry.find( 'discussion comment text' )[ 0 ] );
                            box.innerHTML = '<div><img alt="' + author + '" src="' + avatar + '" /></div><div class="details"><h4>' + author + '</h4><div class="background"></div><div class="text">' + comment+ '</div></div>';
                            break;
                        case 'favourite':
                            author = entry.find( 'favourites user name' ).text();
                            avatar = entry.find( 'favourites user avatar media' ).attr( 'url' );
                            box.innerHTML = '<div><img alt="' + author + '" src="' + avatar + '" /></div><div class="details"><h4>' + author + '</h4><div class="background"></div><div class="love">&#10084;</div></div>';
                            break;
                        case 'friend':
                            author = entry.find( 'name' ).text();
                            avatar = entry.find( 'avatar media' ).attr( 'url' );
                            gender = entry.find( 'gender' ).text();
                            var friend;
                            if ( gender == 'f' ) {
                                friend = 'φίλη';
                            }
                            else {
                                friend = 'φίλος';
                            }
                            box.innerHTML = '<div><img alt="' + author + '" src="' + avatar + '" /></div><div class="details"><h4>' + author + '</h4><div class="friend">' + friend + '</div></div>';
                            break;
                    }
                    $( box ).click( ( function ( e, eventtype ) {
                        return function () {
                            Notifications.TakeOver();
                            $( '#notifications .box' ).removeClass( 'selected' );
                            $( this ).addClass( 'selected' );
                            switch ( eventtype ) {
                                case 'comment':
                                    Notifications.CreateCommentGUI( e );
                                    break;
                                case 'favourite':
                                    Notifications.CreateFavouriteGUI( e );
                                    break;
                                case 'friend':
                                    Notifications.CreateFriendGUI( e );
                                    break;
                            }
                        };
                    } )( entry, eventtype ) );
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
