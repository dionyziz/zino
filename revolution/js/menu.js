Startup( function () {
    $( '#logoutbutton' ).click( function () {
        $.post( 'session/delete' );
        return false;
    } );
    $( '#chatbutton' ).click( function () {
        Chat.Toggle();
        return false;
    } );
} );
