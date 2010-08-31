var Kamibu = {
    Go: function ( href ) {
        var base = document.getElementsByTagName( 'base' )[ 0 ].href;
        window.location.href = base + href;
    },
    Nl2Br: function ( text ) {
        /** Requirements: jQuery */
        var res = document.createElement( 'div' );
        var br = document.createElement( 'br' );
        var lns = text.split( '\n' );
        for ( var i = 0, l = lns.length ; i < l; ++i ) {
            if ( lns[ i ] != '' ) {
                $( res ).append( document.createTextNode( lns[ i ] ) );
            }
            if ( i != l - 1 ) {
                $( res ).append( br.cloneNode() );
            }
        }
        return res.childNodes;
    },
    Br2Nl: function ( element ) {
        /** Requirements: jQuery */
        var text = '';
        element = element.get( 0 );
        for ( var i = 0, l = element.childNodes.length; i < l; ++i ) {
            if ( element.childNodes[ i ].nodeType == Node.TEXT_NODE ) {
                text += element.childNodes[ i ].nodeValue;
            }
            else if ( element.childNodes[ i ].nodeType == Node.ELEMENT_NODE &&
                      element.childNodes[ i ].nodeName.toLowerCase() == 'br' ) {
                text += '\n';
            }
        }
        return text;
    },
    EditableTextElement: function( element, placeholder, callback ) {
        /** Requirements: jQuery */
        
        if( $( element ).css( 'position' ) == 'static' ){
            $( element ).css({
                'top': 0,
                'left': 0,
                'position': 'relative'
            });
        }
        Kamibu.addClass( element, 'editabletext' );
        var input = document.createElement( 'input' );
        input.className = 'editableinput';
        if ( $( element ).text() === '' ) {
            Kamibu.addClass( element, 'editableempty' );
            $( element ).text( placeholder );
        }
        else {
            input.value = $( element ).text();
        }
        element.appendChild( input );
        $( input ).focus( function() {
            input.style.display = 'block';
        } );
        $( input ).blur( function() {
            input.style.display = '';
        } );
        $( input ).keydown( function( e ) {
             if ( e.keyCode == 13 ) {
                input.blur();
                input.style.display = '';
             }
        } );
        $( input ).change( function() {
            if ( input.value === '' ) {
                return;
            }
            Kamibu.removeClass( element, 'editableempty' );
            $( element ).text( input.value );
            Kamibu.EditableTextElement( element );
            if ( typeof callback === 'function' ) {
                callback( input.value );
            }
        } );
    },
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
    TimeFollow: function( timeNode ){
        /** Requirements: jQuery */
        
        /*
            Developer: ted
        */  
        if( $( timeNode ).hasClass( 'processedtime' ) ){
            return true;
        }
        var diff = dateDiff( $( timeNode ).text(), Now );
        $( timeNode ).html( '<span class="friendly">' + greekDateDiff( diff ) + '</span>'
                           +'<span class="timestamp">' + stringToDate( $( timeNode ).text() ).getTime() + '</span>' );
        $( timeNode ).addClass( 'processedtime' );
        var fol = function(){
            var fri = $( timeNode ).children( '.friendly' ).text();
            var ts = $( timeNode ).children( '.timestamp' ).text();
            var dat = new Date();
            dat.setTime( ts );
            var diff = NowDate.getTime() - ts;
            var dt = dateToString( dat );
            var newfri = greekDateDiff( dateDiff( dt, Now ) );
            
            $( timeNode ).children( '.friendly' ).text( newfri );
            $( timeNode ).trigger( 'updated' );
            if( diff / 60000 < 60 ){ //for an hour
                setTimeout( fol, 60000 - ( diff % 60000 ) ); //1 min - seconds of diff
                return;
            }
            if( diff / 60000 < 60 * 24 ){ //for a day
                setTimeout( fol, 60 * 60000 - ( diff % 60000 ) ); //60 mins - seconds of diff
            }
            //No need of more, I think
        };
        fol();
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
    },
    PreLoadImage: function( url, callback ) {
        var loader = new Image();
        loader.onload = callback.call( loader );
        loader.src = url;
    }
 };
