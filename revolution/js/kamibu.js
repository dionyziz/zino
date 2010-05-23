var Kamibu = {
    ClickableTextbox: function( element , reshowtext , aftercolor , beforecolor ,  callback ) {
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
        
            element.style.color = beforecolor;
            
            element.onfocus = function() {
                element.value = '';
                //Kamibu.removeClass( element, 'blured' );
                if( aftercolor ) {
                    element.style.color = aftercolor;
                }
            };
            if ( reshowtext ) {
                var text = element.value;
                element.value = reshowtext;
                element.onblur = function() { 
                    if ( element.value === '' ) {
                        //Kamibu.addClass( element, 'blured' );
                        if ( beforecolor ) {
                            element.style.color = beforecolor;
                        }
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
    ValidEmail: function( email ) {
        if ( typeof( email ) == 'string' ) {
            return /^[a-zA-Z0-9.\-_]+@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,4}$/.test( email );
        }
        return false;
    },
    hasClass: function( element, name ) {
        element.className.match( new RegExp( '(\\s|^)' + name + '(\\s|$)' ) );
    },
    addClass: function( element, name ) {
        if ( !Kamibu.hasClass( element, name ) ) {
            element.className += " " + name;
        }
    },
    removeClass: function( element, name ) {
        if ( Kamibu.hasClass( element, name ) ) {
            element.className = element.className.replace( new RegExp( '(\\s|^)' + name + '(\\s|$)' ) ,' ');
        }
    }
}
