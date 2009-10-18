var Store = {
    OnLoad: function ( productid ) {
        $( 'ul.toolbox .lurv a' ).click( function () {
            if ( this.id == 'luved' ) {
                return;
            }
            
            Coala.Warm(
                'favourites/addstore', {
                    itemid: productid, f: function ( html ) {
                        $( 'ul.lurv' )[ 0 ].innerHTML += '<li>' + html + '</li>';
                    }
                }
            );
            this.innerHTML = '';
            this.id = 'luved';
            return false;
        } );
        $( '#buynow select' ).change( function () {
            switch ( $( '#buynow select' )[ 0 ].value ) {
                case '1':
                case '2':
                case '102':
                case '107':
                case '139':
                case '164':
                    /*$( '#delivery1' ).hide();
                    $( '#delivery2' ).show();
                    $( '#needaddy' ).hide();*/
                    break;
                default:
                    $( '#delivery1' ).show();
                    $( '#delivery2' ).hide();
                    $( '#needaddy' ).show();
            }
        } );
        $( '#buynow select' ).change();
    }
};
