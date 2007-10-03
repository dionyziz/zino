var InterestTag = {
    Submit: function( e ) {
        if ( !e ) {
            e = window.event;
        }
        if ( e.keyCode == 13 ) {
            var inp = g( 'newinteresttag' );
            var val = inp.value;
            Coala.Warm( 'interesttag/new', { 'text': val } );

            inp.parentNode.insertBefore( d.createTextNode( val + " " ) );
            inp.value = '';
            inp.focus();
        }
    }
};
