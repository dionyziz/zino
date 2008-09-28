var Search = {
    Edited: false,
    Focus: function( what ) {
        with ( what ) {
            style.color = 'black';
            if ( !Search.Edited ) {
                value = '';
            }
            focus();
            select();
        }
    },
    Blur: function( what ) {
        with ( what ) {
            style.color = '#cccccc';
            if ( value == '' ) {
                Search.Edited = false;
                value = 'Αναζήτηση';
            }
        }
    }
};
