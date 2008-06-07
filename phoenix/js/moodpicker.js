var MoodDropdown = {
    CurrentOpen: 0,
    Unpush: function ( who ) {
        this.CurrentOpen = 0;
        $( who ).css( 'overflow', 'hidden' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'http://static.zino.gr/phoenix/dropbutton.png\')';
        $( who ).find( 'div.pick' ).fadeOut( 400 );
        $( who ).find( 'div.view' ).css( 'opacity', 1 );
    },
    Push: function ( who ) {
        if ( this.CurrentOpen !== 0 ) {
            if ( this.CurrentOpen == who ) {
                this.Unpush( who );
                return;
            }
        }
        this.CurrentOpen = who;
        $( who ).css( 'overflow', '' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'http://static.zino.gr/phoenix/dropbuttonpushed.png\')';
        $( who ).find( 'div.view' ).css( 'opacity', 0.5 );
        $( who ).find( 'div.pick' ).hide();
        $( who ).find( 'div.pick' ).fadeIn( 400 );
        
        $( who ).find( 'ul li a' ).click( function ( event, a ) {
            MoodDropdown.Unpush( who );
            $( who ).find( 'div.view img.selected' )[ 0 ].src = $( this ).find( 'img' )[ 0 ].src;
            $( who ).find( 'div.view img.selected' )[ 0 ].alt = $( this ).find( 'img' )[ 0 ].alt;
            $( who ).find( 'div.view img.selected' )[ 0 ].title = $( this ).find( 'img' )[ 0 ].title;
            return false;
        } );
    }
};
