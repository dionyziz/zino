var Poll = {
    NewOptions: 2,
    PreCreate: function() {
        axslt( false, 'call:poll.new', function() {
            $( '.col1, .col2, #notifications' ).remove();
            $( this ).appendTo( 'body' );
            $( '.newpoll' ).find( 'input.question' ).focus();
            $( '.newpoll' ).find( 'input.option' ).keydown( function() {
                Poll.OptionChange( this );
            } );
            Kamibu.ClickableTextbox( $( '.newpoll' ).find( 'input.option:eq(0)' ),
                'Πρώτη επιλογή', 'black', 'grey' );
            Kamibu.ClickableTextbox( $( '.newpoll' ).find( 'input.option:eq(1)' ),
                'Δεύτερη επιλογή', 'black', 'grey' );
        } );
        return false;
    },
    OptionChange: function( node ) {
        console.warn( 'option changing' );
        //This is the last, and every else is filled
        if ( $( node ).attr( 'id' ).split( '_' )[1] == Poll.NewOptions ) {
            for ( var i = 1; i < Poll.NewOptions; ++i ) {
                if ( !$( '#newoption_' + i ).val() ) {
                    return true;
                }
            }
            var newoption = $( '<li><input /></li>' )
                .find( 'input' )
                .attr( 'id', 'newoption_' + ( ++Poll.NewOptions ) )
                .attr( 'class', 'option' )
                .keydown( function() {
                    Poll.OptionChange( this );
                } ).end();
            $( '.newpoll ul' ).append( newoption );
            Kamibu.ClickableTextbox( newoption.find( 'input' ), 'Eπιπλέον επιλογή;', 'black', 'grey' );
        }
    }
}