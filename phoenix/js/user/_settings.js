var Settings = {
    SectionsArray: [ 'personal', 'characteristics', 'interests', 'contact', 'account' ],
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
            } ( this.toString() ) );
        } );
    },
    SectionSwitch: function( section ) {
        Settings.CurrentTab = section;
        $( ".settings .tabs > form" ).fadeOut();
        if ( !Settings.SectionsLoaded[ section ] ) {
            $( "#settingsloader" ).center().fadeIn();
            Settings.SectionLoad( section );
            return;
        }
        $( ".settings .tabs form#" + section + "info" ).fadeIn().siblings();
    },
    SectionLoad: function( section ) {
        Coala.Cold( 'user/settings/tab', { tab: section } );
    },
    OnTabLoad: function( section ) {
        $( '#settingsloader' ).fadeOut();
        Settings.SectionsLoaded[ section ] = true;
		if( section == 'interests' ) {
			Suggest.OnLoad();
		}
        Settings.SectionSwitch( section );
    }
};
