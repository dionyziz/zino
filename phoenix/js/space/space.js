$( document ).ready( function() {
	if ( $( '#editspace' )[ 0 ] ) {
        WYSIWYG.Create( document.getElementById( 'wysiwyg' ), 'text', [
            {
                'tooltip': 'Έντονη Γραφή',
                'image': ExcaliburSettings.imagesurl + 'text_bold.png',
                'command': 'bold'
            },
            {
                'tooltip': 'Πλάγια Γραφή',
                'image': ExcaliburSettings.imagesurl + 'text_italic.png',
                'command': 'italic'
            },
            {
                'tooltip': 'Εισαγωγή Link',
                'image': ExcaliburSettings.imagesurl + 'world.png',
                'command': WYSIWYG.CommandLink( 'text' )
            },
            {
                'tooltip': 'Εισαγωγή Εικόνας',
                'image': ExcaliburSettings.imagesurl + 'picture.png',
                'command': WYSIWYG.CommandImage( 'text' )
            },
            {
                'tooltip': 'Εισαγωγή Video',
                'image': ExcaliburSettings.imagesurl + 'television.png',
                'command': WYSIWYG.CommandVideo( 'text' )
            }
        ], 2 );
	}
} );
