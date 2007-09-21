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
        var h4 = g( 'userpoll_' + pollid ).getElementsByTagName( "h4" )[ 0 ];

        while ( h4.firstChild ) {
            h4.removeChild( h4.firstChild );
        }

        var input           = d.createElement( "input" );
        input.type          = "text";
        input.value         = question;
        input.style.width   = '100%';
        input.onkeypress    = function ( e ) {
            if ( !e ) {
                e = window.event;
            }
            if ( e.keyCode == 13 ) {
                var question = input.value;

                var deletep             = d.createElement( "a" );
                deletep.style.cssFloat  = 'right';
                deletep.alt             = 'Διαγραφή Δημοσκόπησης';
                deletep.title           = 'Διαγραφή Δημοσκόπησης';
                deletep.onclick         = function() {
                    Poll.DeletePoll( pollid );
                }

                var deletepimg  = d.createElement( "img" );
                deletepimg.src  = 'http://static.chit-chat.gr/images/icons/delete.png';
                deletepimg.alt  = 'Διαγραφή Δημοσκόπησης';

                deletep.appendChild( deletepimg );

                h4.appendChild( deletep );

                h4.appendChild( document.createTextNode( question ) );
                h4.appendChild( document.createTextNode( " " ) );

                var editp       = d.createElement( "a" );
                editp.alt       = 'Επεξεργασία Δημοσκόπησης';
                editp.title     = 'Επεξεργασία Δημοσκόπησης';
                editp.onclick   = function() {
                    Poll.EditQuestion( pollid, question );
                };

                var editpimg    = d.createElement( "img" );
                editpimg.src    = 'http://static.chit-chat.gr/images/icons/edit.png';
                editpimg.alt    = 'Επεξεργασία Δημοσκόπησης';

                editp.appendChild( editpimg );

                h4.appendChild( editp );
                
                h4.removeChild( input );

                Coala.Warm( 'poll/editquestion', { 'pollid': pollid, 'question': question } );
            }
        };

        h4.appendChild( input );

        input.select();
        input.focus();
    },
    DeletePoll: function( pollid ) {
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
    },
    DeleteOption: function( id ) {
        Coala.Warm( 'poll/option/delete', id );
    },
    EditOption: function( which, id, text ) {
        var container = which.parentNode.parentNode;

        while ( container.firstChild ) {
            container.removeChild( container.firstChild );
        }

        var inp     = d.createElement( 'input' );
        inp.type    = 'text';
        inp.value   = text;
        inp.style.width = '120px';
        
        container.appendChild( inp );

        var submit      = d.createElement( "a" );
        submit.title    = 'Αποθήκευση';
        submit.onclick  = function() {
            Coala.Warm( 'poll/option/edit', { 'id': id, 'text': text, 'callback': EditOptionCallback } );
        };
        submit.style.marginLeft = '2px';
        submit.style.cursor     = 'hand';

        var submitimg   = d.createElement( "img" );
        submitimg.src   = 'http://static.chit-chat.gr/images/icons/disk.png';
        submitimg.alt   = 'Αποθήκευση';

        submit.appendChild( submitimg );

        container.appendChild( submit );

        var cancel      = d.createElement( "a" );
        cancel.title    = 'Ακύρωση';
        cancel.onclick  = function() {
            EditOptionCallback( id, text );
        }
        };
        cancel.style.marginLeft = '2px';
        cancel.style.cursor = 'hand';

        var cancelimg   = d.createElement( "img" );
        cancelimg.alt   = 'Ακύρωση';
        cancelimg.src   = 'http://static.chit-chat.gr/images/icons/cancel.png';

        cancel.appendChild( cancelimg );
        
        container.appendChild( cancel );
    },
    EditOptionCallback: function( id, text ) {
        var p = g( 'polloption_' + id );

        while ( p.firstChild ) {
            p.removeChild( p.firstChild );
        }

        p.appendChild( d.createTextNode( text ) );

        toolbox     = d.createElement( 'div' );
        toolbox.id  = 'optiontoolbox_' + id;
        toolbox.className   = 'optiontoolbox';
        
        editop          = d.createElement( 'a' );
        editop.title    = 'επεξεργασία επιλογής';
        editop.onclick  = function() {
            Poll.EditOption( this, id, text );
        }
        
        editopimg       = d.createElement( 'img' );
        editopimg.src   = "http://static.chit-chat.gr/images/icons/edit.png";
        editopimg.alt   = "επεξεργασία επιλογής";

        editop.appendChild( editopimg );
        
        deleteop        = d.createElement( 'a' );
        deleteop.title  = 'διαγραφή επιλογής';
        deleteop.onclick= function() {
            Poll.DeleteOption( id );
        };
        
        deleteopimg     = d.createElement( 'img' );
        deleteopimg.src = "http://static.chit-chat.gr/images/icons/delete.png";
        deleteopimg.alt = "διαγραφή επιλογής";

        deleteop.appendChild( deleteopimg );

        toolbox.appendChild( editop );
        toolbox.appendChild( deleteop );

        p.appendChild( toolbox );
    }
};

