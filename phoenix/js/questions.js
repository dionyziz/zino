var Questions = {
	busy : false, // do not allow two answers to be edited simultaneously
    Renew: function ( questionid, questiontext ) {
        $( 'div.newquestion p.question' ).empty().text( questiontext );
        $( 'div.newquestion form#newanswer input' )[ 0 ].value = questionid;
        $( 'div.newquestion form#newanswer input' )[ 1 ].value = '';
        $( 'div.newquestion form#newanswer a:last' ).get( 0 ).onclick = function() {
        	Coala.Cold( 'question/get', { 
            	'callback': Questions.Renew,
            	'excludeid' : questionid
            } );
            return false;
        };
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
        
        var edit = document.createElement( 'a' );
        edit.style.display = "none";
        var editimg = document.createElement( 'img' );
        editimg.src = ExcaliburSettings.imagesurl + 'edit.png';
        
        var del = document.createElement( 'a' );
        del.style.display = "none";
        var delimg = document.createElement( 'img' );
        delimg.src = ExcaliburSettings.imagesurl + 'delete.png';
        
        question.appendChild( document.createTextNode( questionText ) );
        answer.appendChild( document.createTextNode( answerText ) );
        edit.appendChild( editimg );
        del.appendChild( delimg );
        li.appendChild( question );
        li.appendChild( answer );
        li.appendChild( edit );
        li.appendChild( del );

        $( 'div#answers ul.questions' ).prepend( li );
        $( 'div.newquestion' )[ 0 ].style.display = 'none';
    },
    AnswerCallback: function( id ) {
    	$( 'div#answers ul.questions li:first' ).attr( "id", "q_" + id ).find( "a:first" ).click( function() {
    													Questions.Edit( id );
    													return false;
    												} ).end()
    											.find( "a:last" ).click( function() {
    												Questions.Delete( id );
    												return false;
    											} );
   	},
   	Edit : function( id ) {
   		if ( Questions.busy ) {
   			return;
   		}
   		Questions.busy = true;
   		var form = document.createElement( 'form' );
   		form.onsubmit = function() { return false; };
   		
   		var input = document.createElement( 'input' );
   		input.value = $( 'li#q_' + id + ' p.answer' ).get( 0 ).firstChild.nodeValue;
   		$( input ).keydown( function( event ) {
   				if ( event.keyCode == 13 ) {
   					Questions.submitEdit( id );
   				}
   			} ).blur( function() {
   				Questions.submitEdit( id );
   			} );
   		
   		var accept = document.createElement( 'a' );
   		accept.onclick = function() {
   				Questions.submitEdit( id );	
   			};
   		
   		var acceptimg = document.createElement( 'img' );
   		acceptimg.alt = "Επεξεργασία";
   		acceptimg.title = "Επεξεργασία";
   		acceptimg.src = ExcaliburSettings.imagesurl + 'accept.png';
   		
   		var cancel = document.createElement( 'a' );
   		cancel.onclick = function() { 
   				Questions.finishEdit( id, false );
   				return false;
   			};
   		
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
   		Questions.hide();
   		$( 'li#q_' + id ).get( 0 ).appendChild( form );
   		$( accept ).show();
   		$( cancel ).show();
   	},
   	submitEdit : function( id ) {
   		var texter = $( 'li#q_' + id + ' form input' ).val();
		if ( $.trim( texter ) === '' ) {
			alert( "Δεν μπορείς να δημοσιεύσεις μία κενή απάντηση" );
			return false;
		}
		Coala.Warm( 'question/answer/edit', {
			'id' : id,
			'answertext' : texter
		} );
		Questions.finishEdit( id, texter );
		return false;
	},
   	finishEdit : function( id, texter ) {
   		$( 'li#q_' + id + ' form' ).remove();
   		if ( texter !== false ) {
   			$( 'li#q_' + id + ' p.answer' ).text( texter );
   		}
   		$( 'li#q_' + id + ' p.answer, li#q_' + id + ' a' ).show();
   		Questions.show();
		Questions.busy = false;
	},
   	Delete : function( id ) {
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
   	},
   	show : function() {
   		$( "div#answers ul.questions li" ).each( function( i ) {
			$( this ).mouseover( function() {
				$( this ).find( 'a' ).show();
			} ).mouseout( function() {
				$( this ).find( 'a' ).hide();
			} );
		} );
	},
	hide : function() {
		$( "div#answers ul.questions li" ).each( function( i ) {
			$( this ).unbind( "mouseover" ).unbind( "mouseout" );
		} );
	},
    OnLoad : function() {
        if ( $( 'div#answers div.questions div.newquestion p.answer form input' )[ 1 ] ) {
            $( 'div#answers div.questions div.newquestion p.answer form input' )[ 1 ].focus();
        }
        Questions.show();
    }
};
