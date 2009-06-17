var About = {
    OnLoad: function() {
        if ( $( '#aboutpeople' ).length ) {
            $( '#aboutpeople li a' ).click( function () {
                alert( $( this ).find( 'img' ).alt );
            } );
        }
    }
};
