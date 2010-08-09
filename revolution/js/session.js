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
    $( '#registermodal form' ).submit( function () {
        if ( $( '#name' ).val() === '' ) {
            alert( 'Πληκτρολόγησε το ψευδώνυμο που θέλεις' );
            $( '#name' ).focus();
            return false;
        }
        if ( $( '#password' ).val() == '' ) {
            alert( 'Πληκτρολόγησε τον κωδικό πρόσβασης που θέλεις' );
            $( '#password' ).focus();
            return false;
        }
        if ( $( '#email' ).val() == '' ) {
            alert( 'Πληκτρολόγησε την διεύθυνση e-mail σου' );
            $( '#email' ).focus();
            return false;
        }
        if ( $( '#password' ).val() != $( '#password2' ).val() ) {
            alert( 'Οι δύο κωδικοί δεν ταιριάζουν' );
            $( '#password2' ).val( '' );
            $( '#password' ).val( '' ).focus();
            return false;
        }
    } );
    return false;
} );
