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
        a.style.display="none";
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
    													return false;
    												} );
   	},
   	Edit : function( id ) {
   		var form = document.createElement( 'form' );
   		form.onsubmit = function() { return false; };
   		form.id = "q_edit_" + id;
   		
   		var input = document.createElement( 'input' );
   		input.value = $( 'li#q_' + id + ' p.answer' ).get( 0 ).firstChild.nodeValue;
   		
   		var accept = document.createElement( 'a' );
   		accept.onclick = function() { return false; };
   		
   		var acceptimg = document.createElement( 'img' );
   		acceptimg.alt = "Επεξεργασία";
   		acceptimg.title = "Επεξεργασία";
   		acceptimg.src = ExcaliburSettings.imagesurl + 'accept.png';
   		
   		var cancel = document.createElement( 'a' );
   		cancel.onclick = function() { return false; };
   		
   		var cancelimg = document.createElement( 'img' );
   		cancelimg.alt = "Ακύρωση";
   		cancelimg.title = "Ακύρωση";
   		cancelimg.src = ExcaliburSettings.imagesurl + 'cancel.png';
   		
   		accept.appendChild( acceptimg );
   		cancel.appendChild( cancelimg );
   		form.appendChild( input );
   		form.appendChild( document.createTextNode( " " ) );
   		form.appendChild( accept );
   		form.appendChild( cancel );
   		
   		$( 'li#q_' + id + ' p.answer, li#q_' + id + ' a' ).hide();
   		$( 'li#q_' + id).unbind( "mouseover" ).unbind( "mouseout" ).geet( 0 ).appendChild( form );
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
		return false;
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
