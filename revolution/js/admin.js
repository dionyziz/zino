var Admin = {
    Banlist: {
        Init: function () {
            $( 'table.bans a' ).click( function () {
                $( this ).replaceWith( 'OK' );
                $.post( 'ban/delete', {
                    userid: this.id.split( '_' )[ 1 ] 
                }, function () {
                    Kamibu.Go( 'ban/list' );
                } ); 
                return false;
            } );
        }
    },
    Bar: {
        Visible: false,
        Loaded: false,
        Loading: false,
        Toggling: false,
        PermissionsRetrieved: false,
        Selection: {
            Threads: []
        },
        Init: function () {
            $( document ).bind( 'keydown', 'F4', Admin.Bar.Toggle )
        },
        Load: function () {
            if ( Admin.Bar.Loading ) {
                return;
            }
            Admin.Bar.Loading = true;
            $.get( 'users/' + User + '?verbose=3', function ( res ) {
                if ( $( res ).find( 'user' ).attr( 'admin' ) == 'yes' ) {
                    $( document.body ).append( $( '<div id="admin" style="display:none"></div>' ) );
                    Admin.Bar.Loaded = true;
                    Admin.Bar.Toggle();
                }
                else {
                    // no permissions; make no further calls
                    $( document ).unbind( 'keydown', 'F4', Admin.Bar.Toggle );
                }
                Admin.Bar.Loading = false;
            } );
        },
        MakeDefaultButtons: function () {
            var username, reason, itemid;

            $( '#admin' ).empty();
            switch ( MasterTemplate ) {
                case 'user.view':
                    username = $( '.maininfo .username' ).text();
                    $( '#admin' ).text( 'Μέλος ' + username )
                                 .append( $( ' <span title="Διαγραφή επιλεγμένου μέλους" class="delete">Διαγραφή</span><span title="Αποκλεισμός επιλεγμένου μέλους από το Zino" class="ban">Αποκλεισμός</span>' ) );
                    $( '#admin span.delete' ).click( function () {
                        reason = prompt( 'Ποιο είναι το παράπτωμα στο οποίο υπέπεσε;' );
                        if ( typeof reason == 'string' ) {
                            if ( reason !== '' ) {
                                $.post( 'ban/create', {
                                    username: username,
                                    reason: reason,
                                    daysbanned: 0
                                }, function () {
                                    alert( 'Ο λογαριασμός διαγράφηκε' );
                                    Kamibu.Go( '' );
                                } );
                            }
                            else {
                                alert( 'Πρέπει να πληκτρολογήσεις μία αιτία' );
                            }
                        }
                    } );
                    $( '#admin span.ban' ).click( function () {
                        reason = prompt( 'Ποιο είναι το παράπτωμα στο οποίο υπέπεσε;' );
                        if ( typeof reason == 'string' ) {
                            if ( reason !== '' ) {
                                days = prompt( 'Για πόσες μέρες θα ήθελες να τον αποκλείσεις;' );
                                if ( typeof days != 'string' ) {
                                    return;
                                }
                                days = days - 0;
                                if ( days <= 0 ) {
                                    alert( 'Ο αριθμός των ημερών θα πρέπει να είναι θετικός' );
                                }
                                $.post( 'ban/create', {
                                    username: username,
                                    reason: reason,
                                    daysbanned: days
                                }, function () {
                                    alert( 'Ο λογαριασμός αποκλείσθηκε' );
                                    Kamibu.Go( '' );
                                } );
                            }
                            else {
                                alert( 'Πρέπει να πληκτρολογήσεις μία αιτία' );
                            }
                        }
                    } );
                    break;
                case 'photo.view':
                    itemid = $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ];
                    $( '#admin' ).text( 'Φωτογραφία ' + itemid );
                    $( '#admin' ).append( '<span class="delete" title="Διαγραφή αυτής της φωτογραφίας">Διαγραφή</span>' );
                    $( '#admin .delete' ).click( function () {
                        if ( confirm( 'Σίγουρα θέλεις να διαγράψεις αυτή τη φωτογραφία;' ) ) {
                            $.post( 'photo/delete', {
                                id: itemid
                            }, function () {
                                alert( 'Η φωτογραφία διαγράφηκε' );
                                Kamibu.Go( '' );
                            } );
                        }
                    } );
                    break;
                case 'poll.view':
                    itemid = $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ];
                    $( '#admin' ).text( 'Δημοσκόπηση ' + itemid );
                    $( '#admin' ).append( '<span class="delete" title="Διαγραφή αυτής της δημοσκόπησης">Διαγραφή</span>' );
                    $( '#admin .delete' ).click( function () {
                        if ( confirm( 'Σίγουρα θέλεις να διαγράψεις αυτή τη δημοσκόπηση;' ) ) {
                            $.post( 'poll/delete', {
                                id: itemid
                            }, function () {
                                alert( 'Η δημοσκόπηση διαγράφηκε' );
                                Kamibu.Go( '' );
                            } );
                        }
                    } );
                    break;
                case 'journal.view':
                    itemid = $( '.contentitem' )[ 0 ].id.split( '_' )[ 1 ];
                    $( '#admin' ).text( 'Ημερολόγιο ' + itemid );
                    $( '#admin' ).append( '<span class="delete" title="Διαγραφή αυτού του ημερολογίου">Διαγραφή</span>' );
                    $( '#admin .delete' ).click( function () {
                        if ( confirm( 'Σίγουρα θέλεις να διαγράψεις αυτό το ημερολόγιο;' ) ) {
                            $.post( 'journal/delete', {
                                id: itemid
                            }, function () {
                                alert( 'Το ημερολόγιο διαγράφηκε' );
                                Kamibu.Go( '' );
                            } );
                        }
                    } );
                    break;
                default:
                    $( '#admin' ).append( 'Μπάρα διαχείρισης μη διαθέσιμη σε αυτή τη σελίδα.' );
            }
        },
        MakeThreadButtons: function () {
            var text;

            $( '#admin' ).empty();
            if ( Admin.Bar.Selection.Threads.length == 1 ) {
                text = '1 νήμα επιλέχθηκε';
            }
            else {
                text = Admin.Bar.Selection.Threads.length + ' νήματα επιλέχθηκαν';
            }
            $( '#admin' ).text( text )
                         .append( $( ' <span title="Διαγραφή επιλεγμένων νημάτων" class="delete">Διαγραφή</span>' ) );
            $( '#admin .delete' ).click( function () {
                text = 'Θέλεις σίγουρα να διαγράψεις ';
                if ( Admin.Bar.Selection.Threads.length == 1 ) {
                    text += 'αυτό το νήμα;';
                }
                else {
                    text += 'αυτά τα ' + Admin.Bar.Selection.Threads.length + ' νήματα;';
                }
                if ( confirm( text ) ) {
                    $.post( 'comment/delete', {
                        commentids: Admin.Bar.Selection.Threads.join( ',' )
                    }, function () {
                        alert( 'Τα νήματα διαγράφτηκαν' );
                        location.reload();
                    } );
                }
            } );
        },
        Select: function( $element ) {
            var div = document.createElement( 'div' );
            div.className = 'adminselection';
            div.style.top = $element.offset().top + 'px';
            div.style.left = $element.offset().left + 'px';
            div.style.width = $element.width() + 'px';
            div.style.height = $element.height() + 'px';
            return div;
        },
        ThreadClick: function () {
            var dta = this.id.split( '_' );
            var commentid;

            if ( dta.length == 2 ) {
                commentid = dta[ 1 ];
                if ( commentid == 0 ) {
                    return;
                }
            }
            Admin.Bar.Selection.Threads.push( commentid );
            Admin.Bar.MakeThreadButtons();
            var div = Admin.Bar.Select( $( this ) );
            $( div ).click( function () {
                var i;
                $( this ).remove();
                for ( i = 0; i < Admin.Bar.Selection.Threads.length; ++i ) {
                    if ( Admin.Bar.Selection.Threads[ i ] == commentid ) {
                        Admin.Bar.Selection.Threads.splice( i, 1 );
                        break;
                    }
                }
                if ( Admin.Bar.Selection.Threads.length ) {
                    Admin.Bar.MakeThreadButtons();
                }
                else {
                    Admin.Bar.MakeDefaultButtons();
                }
            } );
            document.body.appendChild( div );

            return false;
        },
        PhotoClick: function () {
            var div = Admin.Bar.Select( $( this ) );
            $( div ).click( function () {
                $( this ).remove();
            } );
        },
        AttachPage: function () {
            $( '.thread' ).click( Admin.Bar.ThreadClick );
            $( '.photostream li' ).click( Admin.Bar.PhotoClick );
        },
        DetachPage: function () {
            $( '.thread' ).unbind( 'click', Admin.Bar.ThreadClick );
            $( '.adminselection' ).remove();
            Admin.Bar.Selection.Threads = [];
        },
        Toggle: function () {
            if ( !Admin.Bar.Loaded ) {
                Admin.Bar.Load();
                return;
            }
            if ( Admin.Bar.Toggling ) {
                return;
            }
            Admin.Bar.Toggling = true;
            setTimeout( function () {
                Admin.Bar.Toggling = false;
            }, 500 );
            Admin.Bar.Visible = !Admin.Bar.Visible;
            if ( Admin.Bar.Visible ) {
                $( '#admin' ).fadeIn();
                $( '#content' ).animate( { top: '12px' } );
                Admin.Bar.AttachPage();
                Admin.Bar.MakeDefaultButtons();
            }
            else {
                $( '#admin' ).fadeOut();
                $( '#content' ).animate( { top: '0' } );
                Admin.Bar.DetachPage();
            }
        }
    }
};
