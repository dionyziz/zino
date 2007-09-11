var Poll = {
    lastli: 0,
    options: '',
    question: '',
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
    CreateCallback: function() {
        window.location.reload();
    },
    Vote: function( pollid, optionid ) {
        g( 'newpoll' ).style.opacity = '0';
        Coala.Warm( 'poll/vote', { 'pollid': pollid, 'optionid': optionid, 'callback': Poll.VoteCallback } );
    },
    VoteCallback: function() {
        g( 'newpoll' ).style.opacity = '1';
        window.location.reload();
    }
};

