var Settings = {
    OnLoad: function() {
        var arr = ['personal','characteristics','interests','contact','account'];
        $.each( arr, function() {
            $( "#settingslist li " + this + " a" ).click( function() {
                return function( section ) {
                    alert( section );
                    return false;
                } ( this )
            } );
        } );
    }
};
