var Journal = {
    Init: function(){
        ItemView.Init( Type.Journal );
        $( '#deletebutton' ).click( function(){
            if ( confirm( 'Θέλεις να διαγράψεις την εικόνα;' ) ) {
                Journal.Remove( $( '.contentitem' ).attr( 'id' ).split( '_' )[ 1 ] );
            }
        });
    },
    Remove: function( id ) {
        $.post( 'index.php?resource=journal&method=delete', {
            id: id
        }, function(){
            window.location = 'journals/' + User;
        });     
    }
};
