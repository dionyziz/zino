var Banner = {
    OnLoad : function() {
        Banner.Lpassword = false;
        Kamibu.ClickableTextbox( 'lusername' , false , '#000' );
        $( "#lpassword" ).focus( function() {
            if ( !Banner.Lpassword ) {
                $( "#lpassword" ).remove();
                var newinput = document.createElement( 'input' );
                $( newinput ).addClass( 's2_0008' ).css( {
                    'color' : '#000',
                    'margin-left' : '3px'
                    } ).attr( 
                    { value : '',
                    type : 'password',
                    name : 'password'
                } );
                $( "#lusername" ).after( newinput ); 
                newinput.focus();
                Banner.Lpassword = true; 
            }

        } );
    }
};
