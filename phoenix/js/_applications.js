Applications = {
    checkValidity: function () {
        alert ( $( "div#newappbubble input[name=name]" ).match( /
    }
    ,
    showNew: function() {
        var newapp = $( "div#newappbubble" );
        newapp.hide().fadeIn();
        $( "html,body" ).animate( {scrollTop: newapp.offset().top - 50}, 1000 );
        
    }
}