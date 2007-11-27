var Chat = {
    SendMessage: function ( message ) {
        Coala.Warm( 'chat/send', { 'message': message } );
    },
    StartPolling: function ( sessionid, token ) {
        Orbited.connect( function () {
            alert( 'hello' );
        }, sessionid, 'http://beta.chit-chat.gr:8000/chat/channels/kamibu', token );
    }
};
