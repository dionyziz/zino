var JournalNew = {
	Create : function( journalid ) {
		var title = $( 'div#journalnew form div.title input' )[ 0 ].value;
		var text = WYSIWYG.ByName.text.getContents();
		if ( title === '' ) {
			alert( "Πρέπει να ορίσεις τίτλο" );
			$( 'div#journalnew form div.title input' )[ 0 ].focus();
			return false;
		}
		if ( text.length < 5 ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενή καταχώρηση" );
			return false;
		}
        $( '#publish' )[ 0 ].disabled = true;
		return true;
	},
    OnLoad : function() {
        window.title = 'Firing "Create"';
        WYSIWYG.Create( document.getElementById( 'wysiwyg' ), 'text', [
            {
                'tooltip': 'Έντονη Γραφή',
                'image': ExcaliburSettings.imagesurl + 'text_bold.png',
                'command': 'bold',
                'textfocus': true
            },
            {
                'tooltip': 'Πλάγια Γραφή',
                'image': ExcaliburSettings.imagesurl + 'text_italic.png',
                'command': 'italic',
                'textfocus': true
            },
            {
                'tooltip': 'Εισαγωγή Link',
                'image': ExcaliburSettings.imagesurl + 'world.png',
                'command': WYSIWYG.CommandLink( 'text' ),
                'textfocus': true
            },
            {
                'tooltip': 'Εισαγωγή Εικόνας',
                'image': ExcaliburSettings.imagesurl + 'picture.png',
                'command': WYSIWYG.CommandImage( 'text' ),
                'textfocus': false
            },
            {
                'tooltip': 'Εισαγωγή Video',
                'image': ExcaliburSettings.imagesurl + 'television.png',
                'command': WYSIWYG.CommandVideo( 'text' ),
                'textfocus': false
            }
        ], 2 );
    }
};
