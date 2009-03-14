var AdManager = {
    Create: {
        OnLoad: function() {
            $( 'div.buyad a.start' ).click( function () {
                $( 'div.buyad form' ).submit();
            } );
        }
    }
};
