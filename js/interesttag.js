var InterestTag = {
    Submit: function( e ) {
        if ( !e ) {
            e = window.event;
        }
        if ( e.keyCode == 13 ) {
            var inp = g( 'newinteresttag' );
            var val = inp.value;
            if ( val.length === 0 ) {
            	alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον" );
            	return;
            }
            Coala.Warm( 'interesttag/new', { 'text': val } );

            inp.parentNode.insertBefore( d.createTextNode( val + " " ), inp );
            inp.value = '';
            inp.focus();
        }
    }
};
