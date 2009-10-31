var Settings = {
    SectionsArray: ['personal','characteristics','interests','contact','account'],
    SectionsLoaded: [],
    CurrentTab: false,
    
    OnLoad: function() {
        $.each( Settings.SectionsArray, function() {
            Settings.SectionsLoaded[ this ] = false; //Initiate the array
            $( "#settingslist li." + this + " a" ).click( function( section ) {
                return function() {
                    Settings.SwitchSection( section );
                    return false;
                }
            } ( this ) );
        } );
    },
    SectionSwitch: function( section ) {
        Settings.CurrentTab = section;
        if ( !Settings.SectionsLoaded[ section ] ) {
            Settings.SectionLoad( section );
            return;
        }
    },
    SectionLoad: function( section ) {
        Coala.Warm( 'user/settings/tab', { 'tab': section } );
    },
    LoadProperties: function( section ) {
		if( section = 'interests' ) {
			Suggest.OnLoad();
		}
    }
};
