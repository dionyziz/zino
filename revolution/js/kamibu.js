var Kamibu = {
    ClickableTextbox: function( element , reshowtext , aftercolor , beforecolor ,  callback ) {
        //todo: password fields
        if ( typeof( element ) == 'string' ) {
            element = document.getElementById( element );
        }
        if ( !element ) {
            return;
        }
        if ( typeof( jQuery ) != 'undefined' && element instanceof jQuery ) {
            element = element.get()[0];
        }
        if ( element && element.nodeType == 1 ) {
            
            Kamibu.addClass( element, 'clickable' );
            Kamibu.addClass( element, 'blured' );
            
            if ( beforecolor ) {
                element.style.color = beforecolor;
            }
            element.onfocus = function() {
                if ( Kamibu.hasClass( element, 'blured' ) ) {
                    Kamibu.removeClass( element, 'blured' );
                    element.value = '';
                    if ( aftercolor ) {
                        element.style.color = aftercolor;
                    }
                }
            };
            if ( reshowtext ) {
                element.value = reshowtext;
            }
            else {
                reshowtext = element.value;
            }
            element.onblur = function() { 
                if ( element.value === '' && !Kamibu.hasClass( element, 'blured' ) ) {
                    Kamibu.addClass( element, 'blured' );
                    if ( beforecolor ) {
                        element.style.color = beforecolor;
                    }
                    element.value = reshowtext;
                }
            };
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
        return element.className.match( new RegExp( '(\\s|^)' + name + '(\\s|$)' ) ) !== null;
    },
    addClass: function( element, name ) {
        if ( !Kamibu.hasClass( element, name ) ) {
            element.className += " " + name;
        }
    },
    removeClass: function( element, name ) {
        if ( Kamibu.hasClass( element, name ) ) {
            element.className = element.className.replace( new RegExp( '\\b' + name + '\\b' ), '' ).replace( /^\s*|\s*$/, '' ).replace( /\s+/, ' ' );
        }
    }
};
