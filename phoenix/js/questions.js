var Questions = {
    Renew: function ( questionid, questiontext ) {
        $( 'div.newquestion p.question' ).empty().text( questiontext );
        $( 'div.newquestion form#newanswer input' )[ 0 ].value = questionid;
        $( 'div.newquestion form#newanswer input' )[ 1 ].value = '';
        $( 'div.newquestion' ).fadeIn( 'fast' );
        $( 'div.newquestion form#newanswer input' )[ 1 ].focus();
    },
    Answer: function() {
        var answerText = $( 'form#newanswer input' )[ 1 ].value;
        var questionText = $(  'div.newquestion p.question' )[ 0 ].childNodes[ 0 ].nodeValue; 
        
        if ( $.trim( answerText ) === '' ) {
            alert( 'Δεν μπορείς να δημοσιεύσεις μία κενή απάντηση!' );
            return;
        }
        
        Coala.Warm( 'question/answer/new', {
            'questionid': $( 'form#newanswer input' )[ 0 ].value,
            'answertext': answerText,
            'callback': Questions.AnswerCallback
        } );
        Coala.Cold( 'question/get', {
            'callback': Questions.Renew
        } );

        var li = document.createElement( 'li' );
        $( li ).mouseover( function() {
				$( this ).find( 'a' ).show();
			} ).mouseout( function() {
				$( this ).find( 'a' ).hide();
			} );
        
        var question = document.createElement( 'p' );
        question.className = 'question';
        
        var answer = document.createElement( 'p' );
        answer.className = 'answer';
        
        var a = document.createElement( 'a' );
        var img = document.createElement( 'img' );
        img.src = ExcaliburSettings.imagesurl + 'delete.png';
        
        question.appendChild( document.createTextNode( questionText ) );
        answer.appendChild( document.createTextNode( answerText ) );
        a.appendChild( img );
        li.appendChild( question );
        li.appendChild( answer );
        li.appendChild( a );

        $( 'div#answers ul.questions' ).prepend( li );
        $( 'div.newquestion' )[ 0 ].style.display = 'none';
    },
    AnswerCallback: function( id ) {
    	$( 'div#answers ul.questions li:first' ).attr( "id", "q_" + id ).find( "a" ).click( function() {
    													Questions.Delete( id );
    												} );
   	},
   	Delete: function( id ) {
   		Coala.Warm( 'question/answer/delete', {
   			'id' : id
   		} );
   		$( 'li#q_' + id ).hide( 400, function() { 
   				$( this ).remove();
   			} );
   		if ( $( 'div.newquestion:first' ).css( 'display' ) === "none" ) {
   			Coala.Cold( 'question/get', {
		        'callback': Questions.Renew
		    } );
		}
   	}
};

$( document ).ready( function() {
		$( "div#answers ul.questions li" ).each( function( i ) {
			$( this ).mouseover( function() {
				$( this ).find( 'a' ).show();
			} ).mouseout( function() {
				$( this ).find( 'a' ).hide();
			} );
		} );
	} );
