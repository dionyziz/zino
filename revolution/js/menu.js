$( '#logoutbutton' ).click( function () {
    $.post( 'session/delete' );
    return false;
} );
