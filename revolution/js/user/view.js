var Profile = {
    Init: function () {
        if ( $( '#accountmenu' ).length ) {
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
            
            Profile.PopulateEditables();
        }

        if ( $( '#friendship' ).length ) {
            $( '#friendship' )[ 0 ].getElementsByTagName( 'a' )[ 0 ].onclick = function () {
                $.post( this.action, {
                    friendid: this.getElementsByTagName( 'input' )[ 0 ].value
                }, function () {
                    this.innerHTML = "OK";
                } );
                return false;
            };
        }
    },
    PopulateEditables: function() {
        Profile.MakeEditable( $( '.asl .gender' ), 'gender' );
    }
    MakeEditable: function( element, field ) {
        element.addClass( 'editable' );
        switch( field ) {
            case 'gender','sexualorientation':
        }
    }
}
