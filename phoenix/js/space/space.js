var Space = {
	Edit : function() {
		alert( 'test' );
		var text = WYSIWYG.ByName.text.getContents();
		if ( text.length < 5 ) {
			alert( "Δε μπορείς να έχεις κενό χώρο" );
			return false;
		}
		return true;
	}
};
$( document ).ready( function() {
	if ( $( '#editspace' )[ 0 ] ) {
        WYSIWYG.Create( document.getElementById( 'wysiwyg' ), 'text', [
            {
                'tooltip': 'Έντονη Γραφή',
                'image': 'http://static.zino.gr/phoenix/text_bold.png',
                'command': 'bold'
            },
            {
                'tooltip': 'Πλάγια Γραφή',
                'image': 'http://static.zino.gr/phoenix/text_italic.png',
                'command': 'italic'
            },
            {
                'tooltip': 'Εισαγωγή Link',
                'image': 'http://static.zino.gr/phoenix/world.png',
                'command': WYSIWYG.CommandLink( 'text' )
            },
            {
                'tooltip': 'Εισαγωγή Εικόνας',
                'image': 'http://static.zino.gr/phoenix/picture.png',
                'command': WYSIWYG.CommandImage( 'text' )
            },
            {
                'tooltip': 'Εισαγωγή Video',
                'image': 'http://static.zino.gr/phoenix/television.png',
                'command': WYSIWYG.CommandVideo( 'text' )
            }
        ], 2 );
	}
} );
