// if you modify these signatures, also modify the function modifiers in js/user/profile.js
var MoodDropdown = {
    CurrentOpen: 0,
    Unpush: function () {
        if ( MoodDropdown.CurrentOpen === 0 ) {
            return;
        }

        $( MoodDropdown.CurrentOpen ).css( 'overflow', 'hidden' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'' + ExcaliburSettings.imagesurl + 'dropbutton.png\')';
        $( MoodDropdown.CurrentOpen ).find( 'div.pick' ).fadeOut( 400 );
        $( MoodDropdown.CurrentOpen ).find( 'div.view' ).css( 'opacity', 1 );
        MoodDropdown.CurrentOpen = 0;
    },
    Select: function ( id, moodid, who ) {
        Settings.Enqueue( 'mood', moodid, 3000 );
        var imgnode = $( who.parentNode.parentNode.parentNode.parentNode ).find( 'div.view img.selected' )[ 0 ];
        imgnode.src = $( who ).find( 'img' )[ 0 ].src;
        imgnode.alt = $( who ).find( 'img' )[ 0 ].alt;
        imgnode.title = $( who ).find( 'img' )[ 0 ].title;
        MoodDropdown.Unpush();
    },
    Push: function ( who ) {
        if ( MoodDropdown.CurrentOpen !== 0 ) {
            if ( MoodDropdown.CurrentOpen == who ) {
                MoodDropdown.Unpush();
                return;
            }
        }
        MoodDropdown.CurrentOpen = who;
        $( who ).css( 'overflow', '' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'' + ExcaliburSettings.imagesurl + 'dropbuttonpushed.png\')';
        $( who ).find( 'div.view' ).css( 'opacity', 0.5 );
        $( who ).find( 'div.pick' ).hide();
        $( who ).find( 'div.pick' ).fadeIn( 400 );
        
        $( who ).find( 'ul li a' ).click( function ( event, a ) {
            MoodDropdown.Unpush( who );
            return false;
        } );
    }
};
