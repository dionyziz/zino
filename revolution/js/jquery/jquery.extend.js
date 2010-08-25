/*
    Developer: Ted
*/
(function($){
    $.fn.reverse = [].reverse;
    $.fn.top = function(){
        if( $( this ).length == 0 ){
            return false;
        }
        return $( this ).position().top;
    }
    $.fn.left = function(){
        if( $( this ).length == 0 ){
            return false;
        }
        return $( this ).position().left;
    }
    $.fn.right = function(){
        if( $( this ).length == 0 ){
            return false;
        }
        var rap = $( this ).parents( ':css(position=absolute),:css(position=relative)' );
        if( rap.length == 0 ){
            rap = $( 'body' );
        }
        return $( rap ).outerWidth() - $( this ).position().left - $( this ).outerWidth();
    }
    $.fn.bottom = function(){
        if( $( this ).length == 0 ){
            return false;
        }
        var rap = $( this ).parents( ':css(position=absolute),:css(position=relative)' );
        if( rap.length == 0 ){
            rap = $( 'body' );
        }
        return $( rap ).outerHeight( true ) - $( this ).position().top - $( this ).outerHeight( true );
    }

 
    // Extend jQuery's native ':'
    $.extend($.expr[':'],{
        containsCI: function( a, i, m ){
            var val = m[ 3 ];
            if( $( a ).text().toLowerCase().indexOf( val.toLowerCase() ) != -1 ){
                return true;
            }
            return false;
        } /*,
        css: function(a,i,m) {
            var keyVal;
            //m[ 3 ] == content of parenthesys
            var c, s;
            var cs = [ '>', '>=', '<', '<=', '!=' ];
            for( var i = 0; i < cs.length; ++i ){
                if( m[ 3 ].indexOf( cs[ i ] ) != -1 ){
                    c = true;
                    s = cs[ i ];
                }
            }
            if( c === true ){
                keyVal = m[ 3 ].split( s );
                var val = parseInt( $( a ).css( keyVal[ 0 ] ) );
                var cv = parseInt( keyVal[ 1 ] );
                return ( s == '>'  ? val >  cv : 
                         s == '>=' ? val >= cv :
                         s == '<'  ? val <  cv :
                         s == '<=' ? val <= cv : false );
            }
            if( m[ 3 ].indexOf( '=' ) != -1 ){
                keyVal = m[ 3 ].split( '=' );
                return $( a ).css( keyVal[ 0 ] ) == keyVal[ 1 ] || $( a ).css( keyVal[ 0 ] ) == keyVal[ 1 ] + 'px';
            }

            if($(a).css(keyVal[0])) {
                return true;
            } 
            return false;
        } */
    });
})(jQuery);
