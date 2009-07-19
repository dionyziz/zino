var Store = {
    OnLoad: function () {
        $( 'ul.toolbox .lurv a' ).click( function () {
            Coala.Warm(
                'favourites/addstore', {
                    itemid:
                }
            );
            this.style.backgroundImage = "url('http://static.zino.gr/phoenix/store/heart.png')";
            this.innerHTML = '';
            return false;
        } );
    }
};
