var Tag = {
    clicked : false,
    focus : function( event ) {
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
    },
    drag : function( event ) {
        if ( event.button == 0 ) {
            Tag.focus( event );
        }
        else {
            alert( event.button );
        }
    }
};
