var Store = {
    OnLoad: function () {
        $( 'ul.toolbox .lurv a' ).click( function () {
            if ( this.id == 'luved' ) {
                return;
            }
            
            Coala.Warm(
                'favourites/addstore', {
                    itemid: 20, f: function ( html ) {
                        $( 'ul.wantz' )[ 0 ].innerHTML += '<li>' + html + '</li>';
                    }
                }
            );
            this.innerHTML = '';
            this.id = 'luved';
            return false;
        } );
    }
};
