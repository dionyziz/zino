var Tag = {
    focus : function( event ) {
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        if ( x < tag_width/2 ) {
            x = tag_width/2;
        }
        if ( x > image_width-tag_width/2 ) {
            x = image_width-tag_width/2;
        }
        if ( y < tag_height/2 ) {
            y = tag_height/2;
        }
        if ( y > image_height-tag_height/2 ) {
            y = image_height-tag_height/2;
        }
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } );
    }
};
