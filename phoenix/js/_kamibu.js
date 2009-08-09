var Kamibu = {
    ClickableTextbox : function( element , reshowtext , aftercolor , beforecolor ,  callback ) {
        //element is the input on which the function executes
        //callback is a function that will be executed at the end of the function
        
        if ( typeof( element ) == 'string' ) {
            element = document.getElementById( element );
        }
        if ( !element ) {
            return;
        }
        if ( element.nodeType == 1 ) {
            var clicked = false; 
            
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
                        element.value = text;
                        clicked = false;
                        element.style.color = beforecolor;
                    }
                }
            }
            if ( typeof( callback ) == 'function' ) {
                callback();
            }
        }

        return;
    }
}
