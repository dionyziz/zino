var InterestTag = {
    Submit: function( e ) {
        if ( !e ) {
            e = window.event;
        }
        if ( e.keyCode == 13 ) {
            var val = g( 'newinteresttag' ).value;
            alert( val );
        }
    }
};
