var Notifications = {
    Check: function () {
        if ( typeof User != 'undefined' ) {
            $.get( 'notifications', {}, function ( res ) {
                var entries = $( res ).find( 'feed entry' );
                var entry, author, avatar, comment;
                var panel = document.createElement( 'div' );
                var box;

                panel.className = 'panel bottom';
                panel.innerHTML = '<div class="xbutton"></div><h3>Ενημερώσεις (' + $( res ).find( 'feed' ).attr( 'count' ) + ')</h3>';

                for ( var i = 0; i < entries.length; ++i ) {
                    entry = $( entries[ i ] );
                    author = entry.find( 'discussion comment author name' ).text();
                    avatar = entry.find( 'discussion comment author avatar media' ).attr( 'url' );
                    comment = entry.find( 'discussion comment text' ).text(); 
                    box = document.createElement( 'div' );
                    box.className = 'box';
                    box.innerHTML = '<div><img alt="' + author + '" src="' + avatar + '" /></div><div class="details"><h4>' + author + '</h4><div class="text">' + comment+ '</div></div>';
                    panel.appendChild( box );
                }
                
                $( panel ).find( '.xbutton' ).click( function () {
                    this.parentNode.style.display = 'none';
                } );
                document.body.appendChild( panel );
            } );
        }
    }
};

Notifications.Check();
