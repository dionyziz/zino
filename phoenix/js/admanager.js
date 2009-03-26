var AdManager = {
    Create: {
        OnLoad: function() {
            $( $( 'div.buyad a.start' )[ 0 ] ).click( function () {
                $( 'div.buyad form' ).submit();
            } );
            $( "#adtitle" ).keydown( function () {
                alert( 'test' );
            });
        }
    }
};
