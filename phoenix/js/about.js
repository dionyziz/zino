var About = {
    VisiblePerson: 'noone',
    OnLoad: function() {
        if ( $( '#aboutpeople' ).length ) {
            $( '#aboutpeople li a' ).click( function () {
                var username = $( this ).find( 'img' )[ 0 ].alt;
                
                if ( username == About.VisiblePerson ) {
                    return false;
                }
                
                $( this ).parent().parent().find( 'li' ).removeClass( 'selected' );
                $( this ).parent().addClass( 'selected' );
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
        if ( $( '#aboutcontact' ).length ) {
            $( '#aboutcontact select#reason' ).change( function () {
                var options = document.getElementById( 'reason' ).options;
                
                for ( var i = 1; i < options.length; ++i ) { // skip the first empty item
                    var option = options[ i ];
                    
                    if ( option.selected ) {
                        document.getElementById( 'contact_' + option.value ).style.display = '';
                        switch ( option.value ) {
                            case '':
                            case 'purge':
                                document.getElementById( 'submit' ).style.display = 'none';
                                break;
                            default:
                                document.getElementById( 'submit' ).style.display = '';
                        }
                    }
                    else {
                        document.getElementById( 'contact_' + option.value ).style.display = 'none';
                    }
                }
            } );
        }
    },
    Contact: function() {
    }
};
