var About = {
    VisiblePerson: 'noone',
    OnLoad: function() {
        if ( $( '#aboutpeople' ).length ) {
            $( '#aboutpeople li a' ).click( function () {
                var username = $( this ).find( 'img' )[ 0 ].alt;
                if ( About.VisiblePerson ) {
                    $( $( '#aboutperson div#iam' + About.VisiblePerson )[ 0 ] ).animate( {
                        left: '-100%'
                    }, 400, 'swing', function ( removed, added ) {
                        return function () {
                            $( '#iam' + added ).removeClass( 'aboutonepersonslide' );
                            $( '#iam' + removed ).addClass( 'aboutonepersonslide' );
                            $( '#iam' + removed ).css( { left: '100%' } );
                        }
                    }( About.VisiblePerson, username ) );
                }
                $( $( '#aboutperson div#iam' + username )[ 0 ] ).animate( {
                    left: 0
                }, 400, 'swing' );
                About.VisiblePerson = username;
                return false;
            } );
        }
    }
};
