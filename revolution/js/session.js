var username = document.getElementById( 'username' );
var password = document.getElementById( 'password' );
function loginresult( result ) {
    if ( result ) {
        window.location.href = 'photos';
    }
    else {
        alert( 'Λάθος κωδικός/όνομα χρήστη' );
    }
}
$( '.register form' ).submit( function() {
    $( '#registermodal input[name=username]' ).attr( 'value', $( '.register .text' ).attr( 'value' ) );
    $( '#registermodal, #registerbackground' ).css( { display: 'block' } );
    $( '#regisermodal form' ).submit( function () {
        if ( $( '#password' ).val() != $( '#password2' ).val() ) {
            $( '#password2' ).val( '' );
            $( '#password' ).val( '' ).focus();
            return false;
        }
    } );
    return false;
} );
