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
		return true;
	}
};
$( document ).ready( function() {
    if ( $( '#journalnew' )[ 0 ] ) {
        window.title = 'Firing "Create"';
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
                'command': function () {
                    var q = prompt( 'Πληκτρολόγησε την διεύθυνση προς την οποία θέλεις να γινει link:', 'http://www.zino.gr/' );
                    
                    if ( typeof q == "string" && q !== '' ) {
                        WYSIWYG.ExecCommand( 'text', 'createLink', q );
                    }
                }
            },
            {
                'tooltip': 'Εισαγωγή Εικόνας',
                'image': 'http://static.zino.gr/phoenix/picture.png',
                'command': function () {
                    
                }
            },
            {
                'tooltip': 'Εισαγωγή Video',
                'image': 'http://static.zino.gr/phoenix/television.png',
                'command': function () {
                    var q = prompt( 'Πληκτρολόγησε την διεύθυνση του video:', 'http://www.youtube.com/watch?v=aaaaaa' );

                    if ( typeof q == 'string' && q != '' ) {
                        WYSIWYG.ExecCommand( 'text', 'inserthtml', '<object width="425" height="344"><param name="movie" value="' + q + '"></param><embed src="' + q + '" type="application/x-shockwave-flash" width="425" height="344"></embed></object>' );
                    }
                }
            }
        ], 2 );
    }
} );
