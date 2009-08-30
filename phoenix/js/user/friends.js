var Friends = {
    OwnSubdomain: false,
    Add: function( listitem ) {
        var already;
        var img;
        img = document.createElement( 'img' );
        img.src = 'http://static.zino.gr/phoenix/accept.png';
        img.alt = 'Έγινε';
        img.className = 'done';
        
        already = document.createElement( "span" );
        already.appendChild( img );
        already.appendChild( document.createTextNode( "φίλος" ) );
        already.className = "already";
        
        var anchor = $( "a.add", listitem ).unbind( "click" ).blur().fadeOut( 100, function() {
            anchor.removeClass( "add" ).addClass( "remove" ).contents()[ 0 ].nodeValue = "-";
            anchor.find( "span" ).contents()[ 0 ].nodeValue = "Διαγραφή φίλου";
            listitem.appendChild( already );
            $( anchor ).css( { 'z-index': 2 } );
            $( already ).hide().fadeIn( 300 );
            $( img ).css( { opacity: 0 } ).animate( {
                top: 3
            }, { queue: false, duration: 1000, easing: 'easeOutBounce' } ).animate( {
                opacity: 1
            }, { queue: false, duration: 800, easing: 'swing' } );
            setTimeout( function() {
                already.lastChild.nodeValue = 'φίλος';
                $( already ).css( { 'z-index': 0 } ).find( 'img' ).fadeOut( 200, function() { $( this ).remove() } );
                $( anchor ).show().click( function() {
                    return Friends.Remove( $( this ).closest( "li" )[0] );
                } );
            }, 3000 );
        } );
        if ( Friends.OwnSubdomain ) {
            $( "span.friendscount" ).text( ( $( "span.friendscount" ).text() * 1 + 1 ) );
        }
        Coala.Warm( 'user/relations/new', { userid: $( listitem ).attr( "id" ).split( '_' )[1] } );
        return false;
    }
    ,
    Remove: function( listitem ) {
        var already = $( "span.already", listitem )[0];
        var img;
        img = document.createElement( 'img' );
        img.src = 'http://static.zino.gr/phoenix/accept.png';
        img.alt = 'Έγινε';
        img.className = 'done';
        
        already.lastChild.nodeValue = "";
        already.appendChild( img );
        already.appendChild( document.createTextNode( "διαγράφηκε" ) );
        $( already ).hide();
        
        var anchor = $( "a.remove", listitem ).unbind( "click" ).blur().fadeOut( 100, function() {
            anchor.removeClass( "remove" ).addClass( "add" ).contents()[ 0 ].nodeValue = "+";
            anchor.find( "span" ).contents()[ 0 ].nodeValue = "Γίνε φίλος";
            listitem.appendChild( already );
            $( already ).hide().fadeIn( 300 );
            $( img ).css( { opacity: 0 } ).animate( {
                top: 3
            }, { queue: false, duration: 1000, easing: 'easeOutBounce' } ).animate( {
                opacity: 1
            }, { queue: false, duration: 800, easing: 'swing' } );
            setTimeout( function() {
                $( already ).find( 'img' ).fadeOut( 100, function() { $( this ).remove() } );
                $( already ).fadeOut( 100, function() { $( this ).remove(); } );
                $( anchor ).fadeIn( 300 ).click( function() {
                    return Friends.Add( $( this ).closest( "li" )[0] );
                } );
            }, 3000 );
        } );
        if ( Friends.OwnSubdomain ) {
            alert( 'noob' );
            $( "span.friendscount" ).text( ( $( "span.friendscount" ).text() * 1 - 1 ) );
        }
        Coala.Warm( 'user/relations/delete', { userid: $( listitem ).attr( "id" ).split( '_' )[1] } );
        return false;
    }
    ,
    Load: function() {
        $( "a.add" ).click( function() { return Friends.Add( $( this ).closest( "li" )[0] ); } );
        $( "a.remove" ).click( function() { return Friends.Remove( $( this ).closest( "li" )[0] ); } );
        Kamibu.ClickableTextbox( $( '#friends input' )[ 0 ], true, 'black', '#aaa', function () {} );
    }
};
