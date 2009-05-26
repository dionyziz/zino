var JournalList = {
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
