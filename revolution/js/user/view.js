var Profile = {
    Init: function () {
        $( '#accountmenu a:eq(0)' ).click( function () {
            axslt( false, 'call:user.settings.modal', function() {
                $( this ).filter( 'div' ).prependTo( 'body' ).modal();
            } );
            return false;
        } );
        $( '#accountmenu a:eq(1)' ).click( function () {
            document.body.style.cursor = 'pointer';
            $.post( 'session/delete', {}, function () {
                window.location.href = 'login';
            } );
            return false;
        } );
    }
}
