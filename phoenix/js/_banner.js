var Banner = {
    OnLoad : function() {
        $( "#lusername" ).focus( function() {
            if ( $( this ).value == 'κωδικός' ) {
                $( this ).css( 'color' , '#000' ).attr( 'value' , '' );
            }
        } );
    }
};
