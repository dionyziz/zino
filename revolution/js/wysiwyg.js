var WYSIWYG = {
    VideoPlay: function ( id, node ) {
        if ( $( node ).parents( '.novideo' ).length ) {
            return;
        }
        node.innerHTML = '<object type="application/x-shockwave-flash" style="width:425px; height:344px;" data="http://www.youtube.com/v/' + id + '&amp;autoplay=1"><param name="movie" value="http://www.youtube.com/v/' + node + '&amp;autoplay=1" /></object>';
        node.style.width = '425px';
        node.style.height = '344px';
        node.style.backgroundColor = 'black';
        node.style.clear = 'both';
    }
};
