var About = {
    VisiblePerson: 'dionyziz',
    OnLoad: function() {
        if ( $( '#aboutpeople' ).length ) {
            $( '#aboutpeople li a' ).click( function () {
                var username = $( this ).find( 'img' )[ 0 ].alt;
                if ( About.VisiblePerson ) {
                    $( $( '#aboutperson div#iam' + About.VisiblePerson )[ 0 ] ).animate( {
                        left: '-100%'
                    }, 500, 'swing', function () {
                        alert( 'Boo' );
                    } );
                }
                $( $( '#aboutperson div#iam' + username )[ 0 ] ).animate( {
                    left: 0
                } );
                About.VisiblePerson = username;
                return false;
            } );
        }
    }
};
