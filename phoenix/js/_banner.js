var Banner = {
    OnLoad : function() {
        $( "#lusername" ).focus( function() {
            if ( $( this ).attr( "value" ) == 'ψευδώνυμο' ) {
                $( this ).css( 'color' , '#000' ).attr( 'value' , '' );
            }
        } );
    }
};
