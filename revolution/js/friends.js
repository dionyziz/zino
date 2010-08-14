var Friends = {
    Init: function() {
        $( 'form.friendship a' ).each( function() {
            form = $( this ).parent();
            this.onclick = ( function( form ) { return function() {
                $.post( form[ 0 ].action, form.serialize(), function( res ) {
                    method = $( res ).find( 'operation' ).attr( 'method' );
                    friendid = $( res ).find( 'friend' ).attr( 'id' );
                    if ( method == 'delete' && 0 in $( 'ul.mine' ) ) {
                        $( '#friendship_' + friendid ).parent().parent().fadeOut();
                    }
                    else {
                        $( '#friendship_' + friendid )[ 0 ].innerHTML = 'OK';
                    }
                } );
                return false;
            }; }( form ) );
        } );
    }
};
