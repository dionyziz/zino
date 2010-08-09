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
    $( '#registermodal input[name=name]' ).attr( 'value', $( '.register .text' ).attr( 'value' ) ).focus();
    $( '#registermodal, #registerbackground' ).css( { display: 'block' } );
    $( '#registermodal form' ).submit( function () {
        if ( $( '#name' ).val() === '' ) {
            alert( 'Πληκτρολόγησε το ψευδώνυμο που θέλεις' );
            $( '#name' ).focus();
            return false;
        }
        if ( $( '#pass' ).val() == '' ) {
            alert( 'Πληκτρολόγησε τον κωδικό πρόσβασης που θέλεις' );
            $( '#pass' ).focus();
            return false;
        }
        if ( $( '#email' ).val() == '' ) {
            alert( 'Πληκτρολόγησε την διεύθυνση e-mail σου' );
            $( '#email' ).focus();
            return false;
        }
        if ( $( '#pass' ).val() != $( '#pass2' ).val() ) {
            alert( 'Οι δύο κωδικοί δεν ταιριάζουν' );
            $( '#pass2' ).val( '' );
            $( '#pass' ).val( '' ).focus();
            return false;
        }
    } );
    return false;
} );
