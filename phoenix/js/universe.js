function Stream() {
    Meteor.hostid = '409897502705';
    Meteor.host = "universe.zino.gr";
    Meteor.registerEventCallback( "process", function ( data ) { alert( data ); } );
    Meteor.joinChannel("test", 5);
    Meteor.mode = 'stream';
    Meteor.connect();
}
