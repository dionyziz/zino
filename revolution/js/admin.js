var Admin = {
    Banlist: {
        Init: function () {
            $( 'table.bans a' ).click( function () {
                $( this ).replaceWith( 'OK' );
                $.post( 'ban/delete', {
                    userid: this.id.split( '_' )[ 1 ] 
                }, function () {
                    Kamibu.Go( 'ban/list' );
                } ); 
                return false;
            } );
        }
    }
};
