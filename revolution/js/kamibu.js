var Kamibu = {
    ClickableTextbox : function( element , reshowtext , aftercolor , beforecolor ,  callback ) {
        if ( typeof( element ) == 'string' ) {
            element = document.getElementById( element );
        }
        if ( !element ) {
            return;
        }
        if ( typeof( jQuery ) != 'undefined' && element instanceof jQuery ) {
            element = element.get()[0];
        }
        if ( element.nodeType == 1 ) { 
            var clicked = false;
            
            element.style.color = beforecolor;
            element.value = reshowtext;
            
            element.onfocus = function() {
                if ( !clicked ) {
                    clicked = true;
                    element.value = '';
                    element.style.color = aftercolor;
                }
            };
            if ( reshowtext ) {
                var text = element.value;
                element.onblur = function() { 
                    if ( element.value === '' ) {
                        clicked = false;
                        element.style.color = beforecolor;
                        element.value = text;
                    }
                }
            }
            if ( typeof( callback ) == 'function' ) {
                callback();
            }
        }

        return;
    }, 
    ValidEmail : function( email ) {
        if ( typeof( email ) == 'string' ) {
            return /^[a-zA-Z0-9.\-_]+@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,4}$/.test( email );
        }

        return;
    }
}
