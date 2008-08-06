var Tag = {
    friends : [],
    clicked : false,
    run : false,
    start : function() {
        var ul = $( 'div.thephoto div.frienders ul' ).find( 'li' ).remove().end()
        .get( 0 );
        for( var i=0; i < Tag.friends.length; ++i ) {
            var li = document.createElement( 'li' );
            li.style.cursor = "pointer";
            
            var a = document.createElement( 'a' );
            a.onmousedown = function( event ) {
                    return false;
                    Tag.ekso( event );
                };
            a.appendChild( document.createTextNode( Tag.friends[ i ] ) );
            li.appendChild( a );
            ul.appendChild( li );
        }
        $( 'div.thephoto div' ).show();
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.run = true;
    },
    focus : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        // Change border_width accordingly
        var border_width = 3*2;
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        x -= tag_width / 2;
        y -= tag_height / 2;
        if ( x < 0 ) {
            x = 0;
        }
        if ( x + tag_width + border_width > image_width ) {
            x = image_width - tag_width - border_width;
        }
        if ( y < 0 ) {
            y = 0;
        }
        if ( y + tag_height + border_width > image_height ) {
            y = image_height - tag_height - border_width;
        }
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } );
        $( 'div.thephoto div.frienders' ).css( { left: ( x + 170 ) + 'px', top : y + 'px' } );
        if ( $.browser.msie ) {
            event.cancelBubble = true;
        }
        else {
            event.stopPropagation();
        }
    },
    drag : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        if ( Tag.clicked ) {
            $( 'div.thephoto div.frienders' ).hide();
            Tag.focus( event );
        }
    },
    ekso : function( event ) {
        if ( $.browser.msie ) {
            event.cancelBubble = true;
        }
        else {
            event.stopPropagation();
        }
        Tag.clicked=false;
    },
    focusInput : function( event ) {
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.ekso( event );
    },
    showSug : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        Tag.clicked=false;
        $( 'div.thephoto div.frienders' ).show();
    },
    katoPontike : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        Tag.clicked=true;
        Tag.focus( event );
    }
};
