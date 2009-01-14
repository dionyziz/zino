var Search = {
    check : function() {
        var check = false;
        if ( !$( 'div.ybubble div.body form div.search input' )[2].checked ) {
            return true;
        }
        $( 'div.ybubble div.body form div.search select' ).each( function() {
                if ( this.selectedIndex !== 0 ) {
                    check = true;
                }
            } );
        if ( !check ) {
            alert( "Όρισε κάποιες επιλογές για να πραγματοποιήσεις αναζήτηση ατόμων." );
            return false;
        }
        return true;
    }
};
