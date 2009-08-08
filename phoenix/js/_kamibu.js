var Kamibu = {
    ClickableTextbox : function( element , reshowtext , aftercolor , beforecolor ,  callback ) {
        //element is the input on which the function executes
        //callback is a function that will be executed at the end of the function
        
        if ( typeof( element ) == 'string' ) {
            element = $( '#' + element )[ 0 ];
        }
        if ( element.nodeType == 1 ) {
            var clicked = false; 
            
            $( element ).focus( function() {
                    if ( !clicked ) {
                        clicked = true;
                        element.value = '';
                        $( this ).css( 'color' , aftercolor );
                    }
            } );
            if ( reshowtext ) {
                var text = element.value;
                $( element ).blur( function() {
                    if ( element.value == '' ) {
                        element.value = text;
                        clicked = false;
                        $( this ).css( 'color' , beforecolor );
                    }
                } );
            }
            callback();
        }

        return;
    }
}
