var HappeningAdmin = {
    OnLoad: function() {
        $( "table#happeninglist > tr" ).click( function () {
            HappeningAdmin.ChangeHappening( $( "td:eq(0)", this ).text() * 1 );
        } );
    }
    ,
    ChangeHappening: function( id ) {
        
    }