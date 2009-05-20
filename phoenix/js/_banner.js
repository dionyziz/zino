var Banner = {
    OnLoad : function() {
     
        $( "#lusername" ).focus( function() {
            alert( 'focus' );
            if ( $( this ).attr( "value" ) == 'ψευδώνυμο' ) {
                $( this ).css( 'color' , '#000' ).attr( 'value' , '' );
            }
        } );
    }
};
