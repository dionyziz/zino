var Banner = {
    OnLoad : function() {
        Banner.Lusername = false;
        Banner.Lpassword = false;
        $( "#lusername" ).focus( function() {
            if ( !Banner.Lusername ) {
                $( this ).css( 'color' , '#000' ).attr( 'value' , '' );
                Banner.Lusername = true;
            }
        } );
        $( "#lpassword" ).focus( function() {
            if ( !Banner.Lpassword ) {
                $( "#lpassword" ).remove();
                var newinput = document.createElement( 'input' );
                $( newinput ).css( {
                    'color' : '#000',
                    'margin-left' : '3px'
                    } ).attr( 
                    { value : '',
                    type : 'password',
                    name : 'password'
                } ).addClass( 's2_0008' ); //temporary solution until Kamibu.ClickableTextbox is published
                $( "#lusername" ).after( newinput ); 
                newinput.focus();
                Banner.Lpassword = true; 
            }

        } );
    }
};
