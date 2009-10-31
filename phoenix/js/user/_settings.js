var Settings = {
    SectionsArray: ['personal','characteristics','interests','contact','account'],
    SectionsLoaded: [],
    CurrentTab: false,
    
    OnLoad: function() {
        $.each( Settings.SectionsArray, function() {
            Settings.SectionsLoaded[ this ] = false; //Initiate the array
            $( "#settingslist li." + this + " a" ).click( function( section ) {
                return function() {
                    Settings.SectionSwitch( section );
                    return false;
                }
            } ( this ) );
        } );
    },
    SectionSwitch: function( section ) {
        Settings.CurrentTab = section;
        if ( !Settings.SectionsLoaded[ section ] ) {
            $( "#settingsloader" ).fadeIn();
            Settings.SectionLoad( section );
            return;
        }
    },
    SectionLoad: function( section ) {
        Coala.Cold( 'user/settings/tab', { 'tab': section } );
    },
    LoadProperties: function( section ) {
		if( section = 'interests' ) {
			Suggest.OnLoad();
		}
    }
};
