var News = {
    Prepare: function( collection ) {
        $( collection ).click( 
            function() { 
                var id = $( this ).attr( 'id' ).split( '_' );
                return News.Preview.call( this, id[1], id[0] );
            } );
    },
    Preview: function( itemid, type ) {
        if ( $( this ).hasClass( 'previewing' ) ) {
            return true;
        }
        var data = $.get( type + 's/' + itemid, { 'preview': 'yes' } );
        axslt( data, '/social/entry', function() {
            $( '#preview .content' ).empty().append( $( this ).filter( '.contentitem' ) );
        } );
        $( this ).addClass( 'previewing' ).siblings().removeClass( 'previewing' );
        return false;
    }
}