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
        // New method, "data"
        css: function(a,i,m) {
            var e = $(a).get(0), keyVal;
            // m[3] refers to value inside parenthesis (if existing) e.g. :data(___)
            if(!m[3]) {
                // Loop through properties of element object, find any jquery references:
                for (var x in e) { if((/jQuery\d+/).test(x)) { return true; } }
            } else {
                // Split into array (name,value):
                keyVal = m[3].split('=');
                // If a value is specified:
                if (keyVal[1]) {
                    // Test for regex syntax and test against it:
                    if((/^\/.+\/([mig]+)?$/).test(keyVal[1])) {
                        return
                         (new RegExp(
                             keyVal[1].substr(1,keyVal[1].lastIndexOf('/')-1),
                             keyVal[1].substr(keyVal[1].lastIndexOf('/')+1))
                          ).test($(a).css(keyVal[0]));
                    } else {
                        // Test key against value:
                        return $(a).css(keyVal[0]) == keyVal[1];
                    }
                } else {
                    // Test if element has data property:
                    if($(a).css(keyVal[0])) {
                        return true;
                    } else {
                        // If it doesn't remove data (this is to account for what seems
                        // to be a bug in jQuery):
                        $(a).removeData(keyVal[0]);
                        return false;
                    }
                }
            }
            // Strict compliance:
            return false;
        }
    });
})(jQuery);
