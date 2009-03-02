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
        document.title = 'Πρόσφατα στο Zino';
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
    DisplayAvatar: function ( who, reverse ) {
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
        var speechurl = 'http://static.zino.gr/phoenix/speech.png';
        
        if ( reverse ) {
            classes += ' whoreversed';
            speechurl = 'http://static.zino.gr/phoenix/thought-rev.png';
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
                  Recent.DisplayAvatar( event.who, reverse )
                  + '<div class="what"><a href="" target="_blank" title="Προβολή του σχόλιου">'
                  + event.text
                  + '</a></div>';
                $( div ).find( 'div.what a' )[ 0 ].href = event.url;
                break;
            case 'Favourite':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse ) 
                    + '<div class="what"><a href="" target="_blank" title="Προβολή του στοιχείου"><em>Πρόσθεσε κάτι στα αγαπημένα</em></a></div>';
                $( div ).find( 'div.what a' )[ 0 ].href = event.url;
                break;
            case 'FriendRelation':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse ) 
                    + '<div class="what"><em>Πρόσθεσε ένα φίλο</em></div>';
                break;
            case 'Image':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse )
                    + '<div class="what"><em>Ανέβασε μία φωτογραφία</em></div>';
                break;
            case 'User':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse ) 
                    + '<div class="what"><em>Είναι καινούργιος στο Zino!</em></div>';
                break;
            case 'Poll':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse )
                    + '<div class="what"><em>Δημιούργησε μία δημοσκόπηση</em></div>';
                break;
            case 'Poll':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse ) 
                    + '<div class="what"><em>Έγραψε ημερολόγιο</em></div>';
                break;
            case 'ImageTag':
                div.innerHTML = 
                    Recent.DisplayAvatar( event.who, reverse ) 
                    + '<div class="what"><em>αναγνώρισε κάποιον σε μία φωτογραφία</em></div>';
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
        par.appendChild( div );
        var item = {
            'node': div,
            'position': -div.scrollHeight,
            'speed': 1 + Math.random()
        };
        item.node.style.bottom = item.position + 'px';
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
        
        for ( i = 0; i < Recent.Bubbles.length; ++i ) {
            if ( Recent.Bubbles[ i ].position <= document.body.scrollHeight + Recent.Bubbles[ i ].node.scrollHeight ) {
                Keep.push( Recent.Bubbles[ i ] );
            }
            else {
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
