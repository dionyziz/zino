var Store = {
    OnLoad: function () {
        $( 'ul.toolbox .lurv a' ).click( function () {
            /* Coala.Warm(
                'favourites/addstore', {
                    itemid: 20, function ( html ) {
                        $( 'ul.wantz' )[ 0 ].innerHTML += '<li>' + html + '</li>';
                    }
                }
            ); */
            this.style.backgroundImage = "url('http://static.zino.gr/phoenix/store/heart.png')";
            this.innerHTML = '';
            return false;
        } );
    }
};
