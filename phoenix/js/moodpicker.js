var MoodDropdown = {
    CurrentOpen: 0,
    Unpush: function () {
        if ( this.CurrentOpen === 0 ) {
            return;
        }

        $( this.CurrentOpen ).css( 'overflow', 'hidden' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'' + ExcaliburSettings.imagesurl + 'dropbutton.png\')';
        $( this.CurrentOpen ).find( 'div.pick' ).fadeOut( 400 );
        $( this.CurrentOpen ).find( 'div.view' ).css( 'opacity', 1 );
        this.CurrentOpen = 0;
    },
    Select: function ( id, moodid, who ) {
        Settings.Enqueue( 'mood', moodid, 3000 );
        var imgnode = $( who.parentNode.parentNode.parentNode.parentNode ).find( 'div.view img.selected' )[ 0 ];
        imgnode.src = $( who ).find( 'img' )[ 0 ].src;
        imgnode.alt = $( who ).find( 'img' )[ 0 ].alt;
        imgnode.title = $( who ).find( 'img' )[ 0 ].title;
        this.Unpush();
    },
    Push: function ( who ) {
        if ( this.CurrentOpen !== 0 ) {
            if ( this.CurrentOpen == who ) {
                this.Unpush();
                return;
            }
        }
        this.CurrentOpen = who;
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
