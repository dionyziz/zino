var Banner = {
    OnLoad : function() {
        $( "#lusername" ).focus( function() {
            if ( $( this ).attr( "value" ) == 'ψευδώνυμο' ) {
                $( this ).css( 'color' , '#000' ).attr( 'value' , '' );
            }
        } ).blur( function() {
            if ( $( this ).attr( "value" ) == '' ) {
                $( this ).css( 'color' , '#aaa' ).attr( 'value' , 'ψευδώνυμο' );
            }
        } );
    }
};
