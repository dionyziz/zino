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
        UserDetails.Init();
        Profile.MakeEditable( $( '.asl .gender' ), 'gender' );
        Profile.MakeEditable( $( 'li.smoker span' ), 'smoker' );
    },
    MakeEditable: function( element, field ) {
        //element.addClass( 'editable' );
        switch( field ) {
            default:
                var select = $( element ).find( 'select.dropdown' );
                Profile.CurrentValues[ field ] = select.val();
                select.empty();
                var map;
                map = UserDetails.GetMap( field );
                console.log( map );
                var nbsp = String.fromCharCode( 160 );
                var option;
                for ( key in map ) {
                    option = $( '<option />' ).attr( 'value', key ).text( nbsp + map[ key ] + nbsp).appendTo( select );
                }
                select.val( Profile.CurrentValues[ field ] );
                select.appendTo( element ).css( 'display', 'block' );
                $( select ).change( function() {
                    var span = $( this ).siblings().filter( 'span' );
                    $( span ).text( UserDetails.GetString( field, $( this ).val() /*, gender*/ ) ).removeClass( 'notshown' );
                    if ( $( this ).val() == '-' ) {
                        span.addClass( 'notshown' );
                    }
                    $.post( 'user/update', { 'gender': $( this ).val() } );
                } );
        }
    },
    CurrentValues: {}
}
