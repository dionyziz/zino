var Questions = {
    Answer: function() {
        var answerText = $( 'form#newanswer input' )[ 1 ].value;
        var questionText = $(  'div.newquestion p.question' )[ 0 ].innerText; 

        Coala.Warm( 'question/answer/new', {
            'questionid': $( 'form#newanswer input' )[ 0 ].value,
            'answertext': answerText
        } );

        var li = documenet.createElement( 'li' );
        var question = document.createElement( 'p' );
        var answer = document.createElement( 'p' );
        question.className = 'question';
        answer.className = 'answer';
        question.appendChild( document.createTextNode( questionText ) );
        answer.appendChild( document.createTextNode( answerText ) );
        li.appendChild( question );
        li.appendChild( answer );

        $( 'div#answers ul' ).prepend( li );
    },
	Create : function() {
		$( 'form' ).show( 450 );
		$( '#newq' ).hide( 450 );
		return false;
	},
	cancelCreate : function() {
		$( 'form' ).hide( 450 );
		$( '#newq' ).show( 450 );
	}
}
