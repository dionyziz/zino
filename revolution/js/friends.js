var Friends = {
    Init: function() {
        $( 'form.friendship a' ).each( function() {
            form = $( this ).parent();
            this.onclick = ( function( form ) { return function() {
                $.post( form[ 0 ].action, form.serialize(), function() {
                    form.innerHTML = "OK";
                } );
                return false;
            }; }( form ) );
        } );
    }
}
