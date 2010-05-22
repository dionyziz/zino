var Poll = {
    NewAnswers: 2,
    PreCreate: function() {
        axslt( false, 'call:poll.new', function() {
            $( '.col1, .col2, #notifications' ).remove();
            $( this ).appendTo( 'body' );
            $( '.newpoll' ).find( 'input.question' ).focus();
            $( '.newpoll' ).find( 'input.answer' ).keydown( function() {
                Poll.AnswerChange( this );
            } );
            Kamibu.ClickableTextbox( $( '.newpoll' ).find( 'input.answer:eq(0)' ).get()[0],
                'Γράψε μία απάντηση', 'black', 'grey' );
            Kamibu.ClickableTextbox( $( '.newpoll' ).find( 'input.answer:eq(1)' ).get()[0],
                'Γράψε μία ακόμη απάντηση', 'black', 'grey' );
        } );
        return false;
    },
    AnswerChange: function( node ) {
        console.warn( 'answer changing' );
        //This is the last, and every else is filled
        if ( $( node ).attr( 'id' ).split( '_' )[1] == Poll.NewAnswers ) {
            for ( var i = 1; i < Poll.NewAnswers; ++i ) {
                if ( !$( '#newanswer_' + i ).val() ) {
                    console.warn( 'returning' + i );
                    return true;
                }
            }
            var newanswer = $( '<li><input /></li>' )
                .find( 'input' )
                .attr( 'id', 'newanswer_' + ( ++Poll.NewAnswers ) )
                .attr( 'class', 'answer' )
                .keydown( function() {
                    Poll.AnswerChange( this );
                } ).end();
            $( '.newpoll ul' ).append( newanswer );
            Kamibu.ClickableTextbox( newanswer.find( 'input' ), 'Θες επιπλέον απάντηση;', 'black', 'grey' );
        }
    }
}