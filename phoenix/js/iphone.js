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
                    this.style.color = '#ccc';
                    this.style.textAlign = 'center';
                    this.value = 'Άγγιξε εδώ για να γράψεις...';
                }
            } );
        }
    }
};

