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
                var options = $( '#aboutcontact select#reason' )[ 0 ].options;
                
                for ( var i = 1; i < options.length; ++i ) { // skip the first empty item
                    var option = options[ i ].value;
                    
                    if ( option.selected ) {
                        $( '#contact_' + option ).style.display = '';
                    }
                    else {
                        $( '#contact_' + option ).style.display = 'none';
                    }
                }
            } );
        }
    },
    Contact: function() {
    }
};
