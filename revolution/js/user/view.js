var Profile = {
    Init: function () {
        $( $( '#accountmenu a' )[ 1 ] ).click( function () {
            document.body.style.cursor = 'pointer';
            $.post( 'session/delete', {}, function () {
                window.location.href = 'login';
            } );
            return false;
        } );
        $( $( '#accountmenu a' )[ 2 ] ).click( function () {
        } );
    }
}
