var News = {
    Item: {},
    Prepare: function( collection ) {
        $( collection ).click( 
            function() { 
                var id = $( this ).attr( 'id' ).split( '_' );
                return News.Preview.call( this, id[1], {
                            'poll': 1,
                            'photo': 2,
                            'journal': 4
                        }[ id[ 0 ] ] );
            } );
    },
    Preview: function( itemid, typeid ) {
        if ( News.Item.itemid == itemid && News.Item.typeid == typeid ) {
            return true;
        }
        $( '#preview' ).show();
        News.Item.itemid = itemid;
        News.Item.typeid = typeid;
        return false;
    }
}