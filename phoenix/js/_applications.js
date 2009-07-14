Applications = {
    CheckValidity: function () {
        alert ( $( "div#newappbubble input[name=name]" ) );
        return false;
    }
    ,
    ShowNew: function() {
        var newapp = $( "div#newappbubble" );
        newapp.hide().fadeIn();
        $( "html,body" ).animate( {scrollTop: newapp.offset().top - 50}, 1000 );
    }
    ,
    OnLoad: function() {
        $( "a#newapplink" ).click( function() {
            showNew();
        } );
    }
}