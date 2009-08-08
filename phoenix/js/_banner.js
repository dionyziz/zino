var Banner = {
    OnLoad : function() {
        Kamibu.ClickableTextbox( 'lusername' , false , '#000' );
        Kamibu.ClickableTextbox( 'lpassword' , false , '#000' , '' , function() {
            $( '#lpassword' ).focus( function() {
                $( this ).remove();
                var newinput = document.createElement( 'input' );
                $( newinput ).addClass( 's2_0008' ).css( {
                    'margin-left' : '3px',
                    'color' : '#000'
                    } ).attr( 
                    { 
                    type : 'password',
                    name : 'password'
                } );
                $( "#lusername" ).after( newinput ); 
                newinput.focus();
            } );

        } );
    }
};
