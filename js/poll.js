var Poll = {
    lastli: 0,
    options: '',
    question: '',
    votingPoll: 0,
    deletingPoll: 0,
    Create: function() {
        var newpoll_ = document.getElementById( 'newpoll' );
        var ul = newpoll_.getElementsByTagName( 'ul' )[ 0 ];
        var h4 = newpoll_.getElementsByTagName( 'h4' )[ 0 ];
        while ( h4.firstChild ) {
            h4.removeChild( h4.firstChild );
        }
        var inp = document.createElement( 'input' );
        inp.style.border = '1px solid #666';
        inp.style.width = '100%';
        h4.appendChild( inp );
        inp.value = 'Πληκτρολόγησε μία ερώτηση και πίεσε enter...';
        inp.onkeypress = function ( e ) {
            if ( !e ) {
                e = window.event;
            }
            if ( e.keyCode == 13 ) {
                Poll.question = inp.value;
                h4.appendChild( document.createTextNode( inp.value ) );
                h4.removeChild( inp );
                Poll.CreateOption();
                sbmt = document.createElement( 'input' );
                sbmt.type = 'button';
                sbmt.value = 'Τέλος επιλογών';
                sbmt.style.margin = '2px 0 2px 145px';
                sbmt.style.border = '1px solid #666';
                sbmt.onclick = function () {
                    options = Poll.options.substring( 0, Poll.options.length - 1 );
                    g( 'newpoll' ).style.opacity = '0.7';

                    Coala.Warm( 'poll/new', { 'question': Poll.question, 'options': options, 'callback': Poll.CreateCallback } );
                };

                ul.appendChild( sbmt );
            }
        };
        Animations.Create( newpoll_, 'opacity', 1000, 0.5, 1 );
        setTimeout( function () {
            inp.select();
            inp.focus();
        }, 10 );
        Animations.Create( ul, 'height', 1000, ul.offsetHeight, ul.offsetHeight + 18 );
    },
    CreateOption: function() {
        if ( Poll.lastli !== 0 ) {
            var answer = Poll.lastli.getElementsByTagName( 'input' )[ 0 ];
            Poll.lastli.appendChild( document.createTextNode( answer.value ) );
            Poll.options += answer.value + "|";
            Poll.lastli.removeChild( answer );
        }
        var newpoll_ = document.getElementById( 'newpoll' );
        var ul = newpoll_.getElementsByTagName( 'ul' )[ 0 ];
        var li = document.createElement( 'li' );
        var iinp = document.createElement( 'input' );
        iinp.style.border = '1px solid #666';
        iinp.style.width = '100%';
        iinp.value = 'Πληκτρολόγησε μία επιλογή και πίεσε enter...';
        li.appendChild( iinp );
        if ( Poll.lastli !== 0 ) {
            ul.insertBefore( li, Poll.lastli.nextSibling );
        }
        else {
            ul.appendChild( li );
        }
        Poll.lastli = li;
        Animations.Create( ul, 'height', 1000, ul.offsetHeight, ul.offsetHeight + 7 );
        setTimeout( function () {
            iinp.select();
            iinp.focus();
        }, 10 );
        
        iinp.onkeypress = function ( e ) {
            if ( !e ) {
                e = window.event;
            }
            if ( e.keyCode == 13 ) {
                Poll.CreateOption();
            }
        };
        lastinput = iinp;
    },
    CreateCallback: function( html ) {
        var undo = g( 'userpoll_' + Poll.deletingPoll );
        if ( undo ) { // shouldn't undo poll anymore
            Animations.Create( undo, 'opacity', 1000, 1, 0, function() {
                    g( 'userpoll_' + Poll.deletingPoll ).style.display = 'none';
                    Poll.deletingPoll = 0;
                }
            );
        }

        g( 'newpoll' ).style.opacity = 1;
        g( 'newpoll' ).innerHTML = html;
        g( 'newpoll' ).className = 'pollview';
    },
    Vote: function( pollid, optionid ) {
        Poll.votingPoll = pollid;
        Coala.Warm( 'poll/vote', { 'pollid': pollid, 'optionid': optionid, 'callback': Poll.VoteCallback } );
    },
    VoteCallback: function( html ) {
        var newpoll = d.createElement( 'div' );
        newpoll.innerHTML = html;
        newpoll.className = 'pollresults';

        var userpoll = g( 'userpoll_' + Poll.votingPoll );
        userpoll.parentNode.insertBefore( newpoll, userpoll );
        newpoll.parentNode.removeChild( userpoll );
    },
    EditQuestion: function( pollid, question ) {
        var h4 = g( 'userpoll_' + Poll.deletingPoll ).getElementsByTagName( "h4" );

        while ( h4.firstChild ) {
            h4.removeChild( h4.firstChild );
        }

        var input           = d.createElement( "input" );
        input.type          = "text";
        input.value         = question;
        input.style.width   = '100%';
        input.onkeypress      = function ( e ) {
            if ( !e ) {
                e = window.event;
            }
            if ( e.keyCode == 13 ) {
                Poll.question = input.value;
                h4.appendChild( document.createTextNode( input.value ) );
                h4.removeChild( inp );
            }
        };

        h4.append( input );

        input.select();
        input.focus();
    },
    Delete: function( pollid ) {
        Poll.deletingPoll = pollid;
        Coala.Warm( 'poll/delete', { 'pollid': pollid, 'callback': Poll.DeleteCallback } );
    },
    DeleteCallback: function( html ) {
        var poll = g( 'userpoll_' + Poll.deletingPoll );

        var ul = poll.getElementsByTagName( 'ul' )[ 0 ];
        var div = d.createElement( 'div' );

        while ( ul.firstChild ) {
            div.appendChild( ul.removeChild( ul.firstChild ) );
        }

        ul.appendChild( div );

        Animations.Create( div, 'opacity', 1000, 1, 0 );
        Animations.Create( ul, 'height', 2500, ul.style.height, 5 );

        div.style.display = 'none';

        var h4 = poll.getElementsByTagName( 'h4' )[ 0 ];
        while ( h4.firstChild ) {
            h4.removeChild( h4.firstChild );
        }

        h4.appendChild( d.createTextNode( 'η δημοσκόπηση διεγράφη. ' ) );
        
        var undolink = d.createElement( 'a' );
        undolink.onclick = Poll.UndoDelete;
        undolink.appendChild( d.createTextNode( 'αναίρεση διαγραφής' ) );

        h4.appendChild( undolink );
        
        var newpoll = d.createElement( 'div' );
        newpoll.innerHTML = html;

        poll.parentNode.insertBefore( newpoll, poll.nextSibling );
    },
    UndoDelete: function() {
        if ( !( Poll.deletingPoll > 0 ) ) {
            return false;
        }
        Coala.Warm( 'poll/undodelete', { 'pollid': Poll.deletingPoll, 'callback': Poll.UndoDeleteCallback } );
    },
    UndoDeleteCallback: function( html ) {
        if ( !( Poll.deletingPoll > 0 ) ) {
            return false;
        }

        Animations.Create( g( 'newpoll' ), 'opacity', 1000, 0.5, 0, function() {
            g( 'newpoll' ).parentNode.removeChild( g( 'newpoll' ) );
        });

        var poll = g( 'userpoll_' + Poll.deletingPoll );
        poll.innerHTML = html;
        poll.parentNode.insertBefore( poll.firstChild, poll );
        poll.parentNode.removeChild( poll );

        Poll.deletingPoll = 0; 
    }
};

