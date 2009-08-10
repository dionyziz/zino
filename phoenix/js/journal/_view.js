var JournalView = {
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
		return false;
	},
    OnLoad : function() {
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            var ads = $( 'div.ads' )[ 0 ];
            ads.innerHTML = html;
            if ( ads.offsetHeight >= ads.parentNode.offsetHeight ) {
                $( ads.parentNode ).css( 'height' , ads.offsetHeight );
            }
        } } );
    }
};
