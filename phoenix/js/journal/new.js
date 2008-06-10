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
                    var q = prompt( 'Πληκτρολόγησε την διεύθυνση του video στο YouTube:', 'http://www.youtube.com/watch?v=aaaaaa' );

                    if ( typeof q == 'string' && q != '' ) {
                        match = /v\=([a-zA-Z0-9_-]+)/.exec( q );
                        if ( match === null || match.length != 2 ) {
                            alert( 'Το video δεν ήταν έγκυρη διεύθυνση του YouTube' );
                            return;
                        }
                        WYSIWYG.ExecCommand( 'text', 'inserthtml', '<br /><img src="http://static.zino.gr/phoenix/video-placeholder.png?v=' + match[ 1 ] + '" alt="Στη θέση αυτή θα εμφανιστεί το video σου" style="1px dotted blue;" /><br />' );
                    }
                }
            }
        ], 2 );
    }
} );
