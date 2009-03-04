/*
    Developer: Dionyziz
    Questions? dionyziz@kamibu.com
*/
var Recent = {
    Events: [],
    Loading: true,
    Now: 0,
    Interval: 20,
    Resolution: 1,
    Smoothness: 0.03,
    Speed: 1,
    Bubbles: [],
    Status: function ( status ) {
        var d = document.createElement( 'a' );
        d.innerHTML = status;
        $( '#debugstatus' )[ 0 ].appendChild( d );
        d.scrollIntoView();
    },
    OnLoad: function () {
        document.title = 'Zino Live';
        Recent.Status( 'Η εφαρμογή πρόσφατων γεγονότων έχει φορτωθεί' );
        setInterval( Recent.GetEvents, Recent.Interval * 1000 );
        setInterval( Recent.RemoveOldies, Recent.Interval * 2 * 1000 );
        setInterval( Recent.Animate, Recent.Smoothness * 1000 );
        Recent.GetEvents();
    },
    OnFirstDownload: function ( now ) {
        Recent.Status( 'Πρώτη λήψη δεδομένων ολοκληρώθηκε' );
        $( 'div#recentevents img.loader' ).remove();
        Recent.Now = now;
        setInterval( Recent.Process, Recent.Resolution * 1000 );
        setTimeout( function () {
            $( '#debugstatus' ).fadeOut();
        }, 3000 );
    },
    GetEvents: function () {
        Recent.Status( 'Ενημέρωση με νέα δεδομένα...' );
        Coala.Cold( 'recent/get', { f: Recent.GotEvents } );
    },
    GotEvents: function ( events, now ) {
        var c = 0;
        var d = 0;
        
        Recent.Status( 'Λήψη δεδομένων ολοκληρώθηκε' );
        if ( Recent.Loading ) {
            Recent.Loading = false;
            Recent.OnFirstDownload( now );
        }
        Recent.Status( 'Τελευταία γνωστή χρονική απόκλιση: ' + Math.abs( Recent.Now - now ) + ' δευτερόλεπτ' + ( Math.abs( Recent.Now - now ) == 1? 'o': 'α' ) );
        for ( i = 0; i < events.length; ++i ) {
            var event = events[ i ];
            if ( event.type == 'Favourite' ) {
                if ( event.target.id == 100416 ) {
                    // force no skip for this; for debugging
                    ++c;
                    Recent.Events.push( event );
                    continue;
                }
            }
            if ( event.created < Recent.Now - Recent.Interval ) { // filter out too old events (older than 20 seconds ago) -- don't consider them at all
                ++d;
                continue;
            }
            ++c;
            Recent.Events.push( event );
        }
        if ( c + d ) {
            Recent.Status( 'Έγινε λήψη ' + c + ' γεγονότ' + ( c == 1? 'ος': 'ων' ) + ' (' + d + ' παραλήφθηκ' + ( d == 1? 'ε': 'αν' ) + ')' );
        }
    },
    DisplayAvatar: function ( who, reverse, thought ) {
        if ( who.avatar == 0 ) {
            var avatar = 'http://static.zino.gr/phoenix/anonymous100.jpg';
        }
        else {
            var avatar = 'http://images.zino.gr/media/' 
                          + who.id 
                          + '/' + who.avatar + '/' 
                          + who.avatar + '_100.jpg';
        }
        var classes = 'who';
        if ( thought ) {
            var speechurl = 'http://static.zino.gr/phoenix/thought.png';
        }
        else {
            var speechurl = 'http://static.zino.gr/phoenix/speech.png';
        }
        
        if ( reverse ) {
            classes += ' whoreversed';
            if ( thought ) {
                speechurl = 'http://static.zino.gr/phoenix/thought-rev.png';
            }
            else {
                speechurl = 'http://static.zino.gr/phoenix/speech-rev.png';
            }
        }
        
        var html = '<div class="' + classes + '">'
                    + '<a href="http://' 
                        + who.subdomain 
                        + '.zino.gr" target="_blank" title="Προβολή προφίλ '
                        + ( who.gender == 'f'? 'της ': 'του ' ) 
                        + who.name
                        + '">'
                        + '<img src="' + avatar + '" alt="'
                        + who.name
                        + '" width="50" height="50" class="avatar" />'
                        + '<span class="nick">'
                        + who.name
                        + '</span>'
                    + '</a>'
                    + '<img src="' + speechurl + '" class="speech" />'
                + '</div>';
        return html;
    },
    DisplayEvent: function ( event ) {
        var div = document.createElement( 'div' );
        var reverse = Math.floor( Math.random() * 2 ) == 1? true: false;
        
        switch ( event.type ) {
            case 'Comment':
                div.innerHTML = 
                  Recent.DisplayAvatar( event.who, reverse, false )
                  + '<div class="what"><a href="" target="_blank" title="Προβολή του σχόλιου">'
                  + event.text
                  + '</a></div>';
                $( div ).find( 'div.what a' )[ 0 ].href = event.url;
                break;
            case 'Favourite':
                switch ( event.target.type ) {
                    case 'Image':
                        var itemHTML = 
                            '<img src="http://images.zino.gr/media/' 
                                + event.target.owner.id + '/' + event.target.id + '/'
                                + event.target.id + '_210.jpg" alt="&lt;3" width="'
                                + event.target.width + '" height="'
                                + event.target.height + '" />';
                        break;
                    case 'Journal':
                        var itemHTML = event.target.title;
                        break;
                }
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true ) 
                    + '<div class="what"><a href="" target="_blank" title="Προβολή του στοιχείου"><em><img src="http://static.zino.gr/phoenix/heart.png" alt="&lt;3" class="icon" />' + itemHTML + '</em></a></div>';
                $( div ).find( 'div.what a' )[ 0 ].href = event.url;
                break;
            case 'FriendRelation':
                if ( event.target.avatar == 0 ) {
                    var avatarurl = 'http://static.zino.gr/phoenix/anonymous100.jpg';
                }
                else {
                    var avatarurl = 'http://images.zino.gr/media/' 
                                  + event.target.id 
                                  + '/' + event.target.avatar + '/' 
                                  + event.target.avatar + '_150.jpg';
                }
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true ) 
                    + '<div class="what"><em><a href="http://' 
                    + event.target.subdomain 
                    + '.zino.gr/" target="_blank" title="' 
                    + event.target.name 
                    + '"><img src="' 
                    + avatarurl 
                    + '" width="'
                    + width + '" height="'
                    + height + '" alt="" /></a>'
                    + '<img src="http://static.zino.gr/phoenix/user_add.png" alt="+φίλος" class="icon" /></em></div>';
                break;
            case 'Image':
                div.innerHTML =
                    Recent.DisplayAvatar( event.who, reverse, true )
                    + '<div class="what"><a href="" target="_blank" title="Προβολή πλήρους μεγέθους"><img src="http://images.zino.gr/media/'
                    + event.who.id + '/' + event.id + '/' 
                    + event.id + '_210.jpg" width="' + event.width + '" height="' + event.height + '" alt="" /></a></div>';
                $( div ).find( 'div.what a' )[ 0 ].href = event.url;
                break;
            case 'User':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true ) 
                    + '<div class="what"><em>Είμαι καινούργιος στο Zino!</em></div>';
                break;
            case 'Poll':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true )
                    + '<div class="what"><em>Δημιούργησα μία δημοσκόπηση</em></div>';
                break;
            case 'Journal':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true ) 
                    + '<div class="what"><em>Έγραψα ημερολόγιο</em></div>';
                break;
            case 'ImageTag':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true ) 
                    + '<div class="what"><em>Αναγνώρισα κάποιον σε μία φωτογραφία</em></div>';
                break;
            case 'Album':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse, true ) 
                    + '<div class="what"><em>Δημιούργησα ένα album</em></div>';
                break;
        }
        Recent.PutBubble( div, reverse );
    },
    PutBubble: function ( div, reverse ) {
        var par = document.getElementById( 'recentevents' );
        div.className = 'event';
        if ( reverse ) {
            div.className = 'event eventrev';
        }
        var item = {
            'node': div,
            'position': 0,
            'speed': 1 + Math.random()
        };
        item.node.style.bottom = item.position + 'px';
        item.node.style.display = 'none';
        par.appendChild( div );
        $( item.node ).fadeIn();
        item.node.style.left = Math.round( Math.random() * ( document.body.scrollWidth - div.scrollWidth ) ) + 'px';
        Recent.Bubbles.push( item );
    },
    Animate: function () {
        for ( i = 0; i < Recent.Bubbles.length; ++i ) {
            Recent.Bubbles[ i ].position += Recent.Speed * Recent.Bubbles[ i ].speed;
            Recent.Bubbles[ i ].node.style.bottom = Recent.Bubbles[ i ].position + 'px';
        }
    },
    RemoveOldies: function () {
        var Keep = [];
        var c = 0;
        var par = document.getElementById( 'recentevents' );
        
        for ( i = 0; i < Recent.Bubbles.length; ++i ) {
            if ( Recent.Bubbles[ i ].position <= document.body.scrollHeight + Recent.Bubbles[ i ].node.scrollHeight ) {
                Keep.push( Recent.Bubbles[ i ] );
            }
            else {
                par.removeChild( Recent.Bubbles[ i ].node );
                ++c;
            }
        }
        Recent.Bubbles = Keep;
        if ( c ) {
            Recent.Status( c + ' γεγονότα παρήλθ' + ( c == 1? 'ε': 'αν' ) );
        }
    },
    Process: function () {
        Recent.Now += Recent.Resolution;
        
        var newArray = [];
        
        for ( i = 0; i < Recent.Events.length; ++i ) {
            var event = Recent.Events[ i ];
            if ( event.created < Recent.Now - Recent.Interval ) { // display events with a 20-second offset from the time they ~really~ happened
                Recent.DisplayEvent( event );
            }
            else {
                newArray.push( event );
            }
        }
        
        Recent.Events = newArray;
    }
};
Recent.OnLoad();
