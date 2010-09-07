var Notifications = {
    TakenOver: false,
    PendingRequests: 0,
    OriginalTitle: '',
    RequestDone: function () {
        --Notifications.PendingRequests;
    },
    RequestStart: function () {
        ++Notifications.PendingRequests;
    },
    TakeOver: function () {
        Notifications.TakenOver = true;
        $( '#world' ).hide();
    },
    Release: function(){
        Notifications.Shortcuts.Remove();
        Notifications.TakenOver = false;
        $( '#notificationWrapper .instantbox' ).hide();
    },
    Ignore: function () {
        Notifications.Shortcuts.Assign( function(){}, Notifications.Save, Notifications.Ignore );
        var notificationid = $( '#notifications .box.selected' ).attr( 'id' ).split( '_' )[ 2 ];
        Notifications.RequestStart();
        $.post( '?resource=notification&method=delete', { notificationid: notificationid }, Notifications.RequestDone );
        Notifications.DoneWithCurrent();
    },
    Save: function() {
        Notifications.Shortcuts.Assign( function(){}, Notifications.Save, Notifications.Ignore );
        var notificationid = $( '#notifications .box.selected' ).attr( 'id' ).split( '_' );
        var notificationtype = notificationid[ 1 ];
        notificationid = notificationid[ 2 ];
        var form = $( '#ib_' + notificationtype + '_' + notificationid + ' form.save' );
        var url = form.attr( 'action' );
        var params = form.serializeArray();
        var postdata = {};
        for ( var i = 0; i < params.length; ++i ){
            postdata[ params[ i ].name ] = params[ i ].value;
        }
        if ( form.find( 'textarea' ).val() === '' ){
            form.find( 'textarea' ).css( { border: '3px solid red' } ).focus();
            return;
        }
        Notifications.RequestStart();
        $.post( url, postdata, Notifications.RequestDone );
        if ( notificationtype != 'comment' ){
            Notifications.RequestStart();
            $.post( '?resource=notification&method=delete', { notificationid: notificationid }, Notifications.RequestDone );
        }
        Notifications.DoneWithCurrent();
    },
    Done: function () {
        $( '#world' ).fadeIn();
        setTimeout( Notifications.Hide, 800 );
    },
    DoneWithCurrent: function () {
        var current = $( '#notifications .selected' )[ 0 ];
        var next;
        var count = $( '#notifications h3 span' ).text() - 1;

        $( current ).addClass( 'done' ).removeClass( 'selected' ).empty().html( '&#10003;' );

        setTimeout( function () {
            $( '#' + current.id ).remove();
        }, 800 );

        $( '#notifications h3 span' ).text( count );
        document.title = '(' + count + ') ' + Notifications.OriginalTitle;

        $( '#ib_' + current.id.split( '_' )[ 1 ] + '_' + current.id.split( '_' )[ 2 ] ).remove();
        
        next = $( current ).nextAll( '.box' );
        if ( next.length === 0 ) {
            next = $( current ).prevAll( '.box' );
            if ( next.length === 0 ) {
                // no more notification boxes
                Notifications.Done();
                return;
            }
        }
        next = next[ 0 ];
        $( next ).click();
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
        if ( typeof User == 'undefined' ) {
            return false;
        }
        axslt( $.get( 'notifications' ), '/social', function() {
            if ( $( this ).find( 'h3 span' ).text() == '0' || $( this ).find( '.box' ).length === 0 ) {
                return;
            }
            Notifications.OriginalTitle = document.title;
            document.title = '(' + $( this ).find( 'h3 span' ).text() + ') ' + document.title;
            $( this ).find( '.businesscard ul li:last' ).addClass( 'last' );
            
            var notificationbody = $( '<div id="notificationWrapper"><div title="Εμφανιση ενημερωσεων" class="nbutton"><span class="num"></span></div></div>' );
            notificationbody.append( $( this ) )
                .find( '.nbutton' ).css( 'display', 'none' ); //hide it
            $( '#world' ).after( notificationbody );
            
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

                var element = $( this ).attr( 'id' ).split( '_' );
                Notifications.Select( element[ 1 ], element[ 2 ] );
            } );
            $( '#notifications .vbutton' ).click( function () {
                if ( Notifications.TakenOver ) {
                    Notifications.Done();
                }
                Notifications.Hide();
            } );
            $( '#notificationWrapper .nbutton' ).click( function(){
                Notifications.Show();
            });
        } );
    },
    Select: function ( notificationtype, notificationid ) {
        var $ib = $( '#ib_' + notificationtype + '_' + notificationid );
        var url = '';
        var $form = $ib.find( 'form.save' );
        var type, itemid;

        switch ( notificationtype ) {
            case 'comment':
                type = $form.find( 'input[name="type"]' ).val();
                itemid = $form.find( 'input[name="itemid"]' ).val();

                if ( type != 'user' ) { // for now 
                    url = type + 's/' + itemid;
                }
                break;
            case 'favourite':
                type = $form.find( 'input[name="favouritetype"]' ).val();
                itemid = $form.find( 'input[name="favouriteitemid"]' ).val();
                url = type + 's/' + itemid;
                break;
            case 'friend':
                $ib.find( 'a.friend' ).click( function () {
                    Notifications.Save();
                    return false;
                } );
                break;
            case 'tag':
                $ib.find( '.image' ).click( function(){
                    Async.Go( 'photos/' + $( this ).attr( 'id' ).split( '_' )[ 1 ] );
                });
        }

        if ( url !== '' ) {
            if ( $ib.children().length !== 0 ) { // content not yet loaded
                $ib.find( '.content' ).html( '<div class="contentitem">...</div>' );
                axslt( $.get( url + '?verbose=0' ), '/social',
                    function () {
                        $ib.find( '.content' ).empty().append( $( this ) );
                        $ib.find( '.content .contentitem' ).append( '<div class="tips">Κάνε κλικ για μεγιστοποίηση</div>' );
                        $ib.find( '.content' ).click( function () {
                            Async.Go( url );
                        } );
                    }
                );
            }
        }
        $( '.instantbox' ).hide();
        $ib.show().find( 'textarea' ).focus();
    },
    ItemNotification: function( type, id ) {
        $( '.instantbox' ).hide();
        $( '#ib_' + type + '_' +  id ).show();
        axslt( $.get( type + 's/' + id, { verbose: 0 } ), '/social', function() {
            $( '#ib_' + type + '_' +  id ).prepend( this );
        } );
    },
    Hide: function() {
        document.title = Notifications.OriginalTitle;
        $( '#notifications' ).slideUp( function(){ // this will hide the panel
            var count = $( '#notifications .box' ).length;
            if( count ){
                $( '#notificationWrapper .nbutton' ).stop( 1 ).slideDown() //this will show the arrow
                    .children( 'span' ).html( count ).show();
            }
        });
        $( '.instantbox' ).fadeOut();
        Notifications.Shortcuts.Remove();
        Notifications.TakenOver = false;
    },
    Show: function(){
        if( !$( '#notifications .box' ).length ){
            return;
        }
        $( '#notifications' ).slideDown();
        setTimeout( function(){
            $( '#notificationWrapper .nbutton' ).hide();
        }, 100 );
    }
};
