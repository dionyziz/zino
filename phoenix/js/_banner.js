var Banner = {
    OnLoad : function() {
        Kamibu.ClickableTextbox( 'lusername' , false , '#000' );
        Banner.Lpassword = false;
        Kamibu.ClickableTextbox( 'lpassword' , false , '#000' , '' , function() {
            $( '#lpassword' ).focus( function() {
                $( this ).remove();
                var newinput = document.createElement( 'input' );
                $( newinput ).addClass( 's2_0008' ).css( 'margin-left' , '3px' ).attr( 
                    { 
                    type : 'password',
                    name : 'password'
                } );
                $( "#lusername" ).after( newinput ); 
                newinput.focus();
            } );

        } );
        /*$( "#lpassword" ).focus( function() {
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
        */
    }
};
