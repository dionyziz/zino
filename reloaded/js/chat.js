var Chat = {
    SendMessage: function ( message ) {
        Coala.Warm( 'chat/send', { 'message': message } );
    },
    StartPolling: function ( sessionid, token ) {
        Orbited.connect( function () {
            alert( message );
        }, sessionid, '/chat/channels/kamibu', token );
    }
};
