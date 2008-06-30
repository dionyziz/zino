var Questions = {
    Renew: function ( questionid, questiontext ) {
        $( 'div.newquestion p.question' ).empty().text( questiontext );
        $( 'div.newquestion form#newanswer input' )[ 0 ].value = questionid;
        $( 'div.newquestion form#newanswer input' )[ 1 ].value = '';
        $( 'div.newquestion' ).style.display = '';
    },
    Answer: function() {
        var answerText = $( 'form#newanswer input' )[ 1 ].value;
        var questionText = $(  'div.newquestion p.question' )[ 0 ].childNodes[ 0 ].nodeValue; 

        Coala.Warm( 'question/answer/new', {
            'questionid': $( 'form#newanswer input' )[ 0 ].value,
            'answertext': answerText
        } );
        Coala.Cold( 'question/get', {
            'callback': Questions.Renew
        } );

        var li = document.createElement( 'li' );
        var question = document.createElement( 'p' );
        var answer = document.createElement( 'p' );
        question.className = 'question';
        answer.className = 'answer';
        question.appendChild( document.createTextNode( questionText ) );
        answer.appendChild( document.createTextNode( answerText ) );
        li.appendChild( question );
        li.appendChild( answer );

        $( 'div#answers ul.questions' ).prepend( li );
        $( 'div.newquestion' ).style.display = 'none';
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
