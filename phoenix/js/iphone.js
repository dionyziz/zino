var iPhone = {
    Frontpage: {
        ShoutboxHasValue: false,
        OnLoad: function () {
            var tx = $( 'textarea' );
            tx.focus( function() {
                if ( !iPhone.Frontpage.ShoutboxHasValue ) {
                    this.value = '';
                    this.style.color = 'black';
                    this.style.textAlign = 'left';
                }
            } );
            tx.blur( function() {
                if ( this.value != '' ) {
                    iPhone.Frontpage.ShoutboxHasValue = true;
                }
                else {
                    iPhone.Frontpage.ShoutboxHasValue = false;
                    this.style.color = '#999';
                    this.style.textAlign = 'center';
                    this.value = 'Άγγιξε εδώ για να γράψεις...';
                }
            } );
            $( 'form' ).submit( function() {
                var tx = $( 'textarea' );
                if ( !iPhone.Frontpage.ShoutboxHasValue ) {
                    alert( 'Πληκτρολόγησε ένα μήνυμα για να προστεθεί στη συζήτηση' );
                    tx.focus();
                    return false;
                }
                return true;
            } );
        }
    }
};

$( function() {
    $( 'a.loadable' ).click( function() {
        this.style.backgroundColor = '#047fbb';
        var img = document.createElement( 'img' );
        img.src = ' http://static.zino.gr/phoenix/iphone-ajax.gif';
        img.alt = 'Loading...';
        $( img ).addClass( 'loader' );
        this.insertBefore( img, this.firstChild );
    } );
} );

