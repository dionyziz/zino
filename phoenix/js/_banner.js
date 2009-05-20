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
        $( "#lpassword" ).focus( function() {
            if ( $( this ).attr( "value" ) == 'κωδικός' ) {
                $( this ).css( 'color' , '#000' ).attr( 
                    {
                        value : '',
                        type : 'password'
                    } );
            }

        } ).blur( function() {
            if ( $( this ).attr( "value" ) == '' ) {
                $( this ).css( 'color' , '#aaa' ).attr( 
                {
                    value : 'κωδικός',
                    type : 'text' 
                } );
            }
        } );
    }
};
