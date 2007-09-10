var lastpollli = 0;

function CreatePoll() {
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
            h4.appendChild( document.createTextNode( inp.value ) );
            h4.removeChild( inp );
            CreatePollAnswer();
            sbmt = document.createElement( 'input' );
            sbmt.type = 'button';
            sbmt.value = 'Τέλος επιλογών';
            sbmt.style.margin = '2px 0 2px 145px';
            sbmt.style.border = '1px solid #666';
            sbmt.onclick = function () {
                var question = g( 'newpoll' ).getElementsByTagName( 'h4' )[ 0 ].firstChild.nodeValue;
                
                var options = '';
                var fields = g( 'newpoll' ).getElementsByTagName( 'ul' )[ 0 ].getElementsByTagName( 'input' );
                for ( var i in fields ) {
                    var field = fields[ i ];
                    if ( field == fields.length - 1 ) {
                        break;
                    }
                    options += field.value + '|';
                }

                options = options.substring( 0, options.length - 2 );

                alert( question + " " + options );
                
                // Coala.Warm( 'poll/new', question, options );
                // window.location.reload();
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
}
function CreatePollAnswer() {
    if ( lastpollli !== 0 ) {
        var answer = lastpollli.getElementsByTagName( 'input' )[ 0 ];
        lastpollli.appendChild( document.createTextNode( answer.value ) );
        lastpollli.removeChild( answer );
    }
    var newpoll_ = document.getElementById( 'newpoll' );
    var ul = newpoll_.getElementsByTagName( 'ul' )[ 0 ];
    var li = document.createElement( 'li' );
    var iinp = document.createElement( 'input' );
    iinp.style.border = '1px solid #666';
    iinp.style.width = '100%';
    iinp.value = 'Πληκτρολόγησε μία επιλογή και πίεσε enter...';
    li.appendChild( iinp );
    if ( lastpollli !== 0 ) {
        ul.insertBefore( li, lastpollli.nextSibling );
    }
    else {
        ul.appendChild( li );
    }
    lastpollli = li;
    Animations.Create( ul, 'height', 1000, ul.offsetHeight, ul.offsetHeight + 6 );
    setTimeout( function () {
        iinp.select();
        iinp.focus();
    }, 10 );
    
    iinp.onkeypress = function ( e ) {
        if ( !e ) {
            e = window.event;
        }
        if ( e.keyCode == 13 ) {
            CreatePollAnswer();
        }
    };
}
