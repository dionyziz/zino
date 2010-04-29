var News = {
    Item: {},
    Prepare: function( collection ) {
        $( collection ).click( 
            function() { 
                var id = $( this ).attr( 'id' ).split( '_' );
                return News.Preview.call( this, id[1], id[0] );
            } );
    },
    Preview: function( itemid, type ) {
        if ( News.Item.itemid == itemid && News.Item.type == type ) {
            return true;
        }
        var data = $.get( type + 's/' + itemid, { 'preview': 'yes' } );
        axslt( data, '/social/entry', function() {
            $( '#preview' ).empty().append( this );
        } );
        News.Item.itemid = itemid;
        News.Item.type = type;
        return false;
    }
}