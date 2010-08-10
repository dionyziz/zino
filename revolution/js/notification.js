var Notifications = {
    World: null,
    TakenOver: false,
    PendingRequests: 0,
    RequestDone: function () {
        --Notifications.PendingRequests;
    },
    RequestStart: function () {
        ++Notifications.PendingRequests;
    },
    TakeOver: function () {
        Notifications.TakenOver = true;
        Notifications.World = $( '#world' ).detach();
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
        var leave = function () {
            if ( Notifications.PendingRequests ) {
                // wait for pending requests to complete
                --LetFinish;
                if ( LetFinish ) {
                    setTimeout( leave, 100 );
                    return;
                }
            }
            // else
            Kamibu.Go( url );
        };
        leave();
    },
    Delete: function ( details ) {
        Notifications.DoneWithCurrent();
        Notifications.RequestStart();
        $.post( 'notification/delete', details, Notifications.RequestDone );
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
        if ( count && next ) {
            $( next ).click();
        }
        else {
            Notifications.Done();
        }
    },
    Shortcuts: {
        Save: 0, Skip: 0, Ignore: 0, KeyPressed: false,
        Assign: function ( skip, save, ignore, beforeSave ) {
            Notifications.Shortcuts.Remove();
            function keyDown() {
                if ( typeof beforeSave != 'undefined' ) {
                    beforeSave();
                }
                Notifications.Shortcuts.KeyPressed = true;
            }
            function keyUp() {
                Notifications.Shortcuts.KeyPressed = false;
            }
            Notifications.Shortcuts.Save = function () {
                if ( !Notifications.Shortcuts.KeyPressed ) {
                    return;
                }
                Notifications.Shortcuts.Remove();
                save();
                keyUp();
                return false;
            };
            Notifications.Shortcuts.Skip = function () {
                if ( !Notifications.Shortcuts.KeyPressed ) {
                    return;
                }
                Notifications.Shortcuts.Remove();
                skip();
                keyUp();
                return false;
            };
            Notifications.Shortcuts.Ignore = function () {
                if ( !Notifications.Shortcuts.KeyPressed ) {
                    return;
                }
                Notifications.Shortcuts.Remove();
                ignore();
                keyUp();
                return false;
            };
            $( document ).bind( 'keydown', 'shift+esc', keyDown )
                         .bind( 'keydown', 'return', keyDown )
                         .bind( 'keydown', 'esc', keyDown );
            $( document ).bind( 'keyup', 'shift+esc', Notifications.Shortcuts.Skip )
                         .bind( 'keyup', 'return', Notifications.Shortcuts.Save )
                         .bind( 'keyup', 'esc', Notifications.Shortcuts.Ignore );
        },
        Remove: function () {
            if ( Notifications.Shortcuts.Skip !== 0 ) {
                $( document ).unbind( 'keyup', 'shift+esc', Notifications.Shortcuts.Skip );
                Notifications.Shortcuts.Skip = 0;
                return false;
            }
            if ( Notifications.Shortcuts.Save !== 0 ) {
                $( document ).unbind( 'keyup', 'return', Notifications.Shortcuts.Save );
                Notifications.Shortcuts.Save = 0;
            }
            if ( Notifications.Shortcuts.Ignore !== 0 ) {
                $( document ).unbind( 'keyup', 'esc', Notifications.Shortcuts.Ignore );
                Notifications.Shortcuts.Ignore = 0;
            }
        }
    },
    Check: function () {
        if ( typeof User != 'undefined' ) {
            axslt( $.get( 'notifications' ), '/social', function() {
                $( document.body ).append( $( this ) );
                $( '.instantbox form' ).submit( function () {
                    Notifications.Save();
                    return false;
                } );
                $( '.box' ).click( function() {
                    if ( !Notifications.TakenOver ) {
                        Notifications.Shortcuts.Assign( function(){}, Notifications.Save, Notifications.Ignore );
                    }
                    Notifications.TakeOver();
                    $( '#notifications .box' ).removeClass( 'selected' );
                    $( this ).addClass( 'selected' );

                    var element  = $( this ).attr( 'id' ).split( '_' );
                    Notifications.Select( element[ 1 ], element[ 2 ] );
                } );
                $( '#notifications .vbutton' ).click( function () {
                    if ( Notifications.TakenOver ) {
                        Notifications.Done();
                    }
                    Notifications.Hide();
                } );
            } );
        }
    },
    Select: function ( notificationtype, notificationid ) {
        $( '.instantbox' ).hide();
        $( '#ib_' + notificationtype + '_' +  notificationid ).show().find( 'textarea' ).focus();
    },
    Ignore: function () {
        var notificationid = $( '#notifications .box.selected' ).attr( 'id' ).split( '_' )[ 2 ];
        $.post( '?resource=notification&method=delete', { notificationid: notificationid } );
    },
    Save: function() {
        var notificationid = $( '#notifications .box.selected' ).attr( 'id' ).split( '_' );
        var notificationtype = notificationid[ 1 ];
        notificationid = notificationid[ 2 ];
        var form = $( '#ib_' + notificationtype + '_' + notificationid + ' form.save' );
        var url = form.attr( 'action' );
        var params = form.serializeArray();
        var postdata = {};
        for( param in params ){
            postdata[ params[ param ].name ] = params[ param ].value;
        }
        alert( url );
        $.post( url, postdata );
        if( notificationtype != 'comment' ){
            $.post( '?resource=notification&method=delete', { notificationid: notificationid } );
        }
    },
    ItemNotification: function( type, id ) {
        $( '.instantbox' ).hide();
        $( '#ib_' + type + '_' +  id ).show();
        axslt( $.get( type + 's/' + id, { verbose: 0 } ), '/social', function() {
            $( '#ib_' + type + '_' +  id ).prepend( this );
        } );
    },
    Hide: function() {
        $( '#notifications' ).hide();
    }
};
