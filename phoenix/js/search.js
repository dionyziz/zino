var Search = {
    check : function() {
        var check = false;
        if ( $( 'div.ybubble div.body form div.search input' )[2].checked != 'checked' ) {
            return true;
        }
        $( 'div.ybubble div.body form div.search select' ).each( function() {
                if ( this.selectedIndex === 0 ) {
                    check = true;
                }
            } );
        if ( check ) {
            alert( "Please fill something" );
            return false;
        }
        return true;
    }
};
