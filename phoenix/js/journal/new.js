var JournalNew = {
	Create : function( journalid ) {
		alert( journalid );
		var text = WYSIWYG.ByName[ 'text' ].getContents();
		alert( text );
		var title = $( 'div#journalnew form div.title input' )[ 0 ].value;
		alert( title );
	},
	Edit : function( journalid ) {
	
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
                    
                    if ( typeof q == "string" && q != '' ) {
                        WYSIWYG.ExecCommand( 'text', 'createLink', q );
                    }
                }
            },
            {
                'tooltip': 'Εισαγωγή Εικόνας',
                'image': 'http://static.zino.gr/phoenix/picture.png'
            },
            {
                'tooltip': 'Εισαγωγή Video',
                'image': 'http://static.zino.gr/phoenix/television.png'
            }
        ], 2 );
    }
} );
