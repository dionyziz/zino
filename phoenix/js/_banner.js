var Banner = {
    OnLoad : function() {
        alert( 'test' ); 
        $( "#lusername" ).focus( function() {
            alert( 'focus' );
            if ( $( this ).attr( "value" ) == 'ψευδώνυμο' ) {
                $( this ).css( 'color' , '#000' ).attr( 'value' , '' );
            }
        } );
    }
};
