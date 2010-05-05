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
            $( '.newpoll ul' ).append(
                $( '<li><input /></li>' )
                    .find( 'input' )
                    .attr( 'id', 'newanswer_' + ( ++Poll.NewAnswers ) )
                    .attr( 'class', 'answer' )
                    .keydown( function() {
                        Poll.AnswerChange( this );
                    } ).end()
            );
        }
    }
}