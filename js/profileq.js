var Profileq = {
	Edit : function ( id ) {
		// alert( 'Answer: ' . g( 'qraw_' + id ).innerHTML );
		var answer = g( 'qraw_' + id ).innerHTML;
		
		var element = g( 'qedit_' + id );
		while (element.firstChild) {
			element.removeChild(element.firstChild);
		}
		
		var qform = d.createElement( 'form' );

		qform.onsubmit = (function( id ){ return function(){ Animations.SetAttribute( element , 'opacity' , 0.5 );Coala.Warm( 'question/edit', {'id':qid.value , 'answer':qanswer.value, 'callback':Profileq.EditCallback });Profileq.EditCancel(id, qanswer.value);Animations.SetAttribute( element , 'opacity' , 1 );return false;} })(id);

		var imageaccept = document.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';

		var imagecancel = document.createElement( 'img' );
		imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';

		var qid = d.createElement( 'input' );
		qid.type = 'hidden';
		qid.name = 'qid';
		qid.value = id;
		
		var qanswer = d.createElement( 'input' );
		qanswer.type = 'text';
		qanswer.size = '80';
		qanswer.name = 'qanswer';
		qanswer.value = answer;
		
		var qsubmit = d.createElement( 'a' );
		qsubmit.href = '';
		qsubmit.onclick = (function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( qform );
		qsubmit.alt = 'Επεξεργασία';
		qsubmit.title = 'Επεξεργασία';
		qsubmit.appendChild( imageaccept );

		var qcancel = d.createElement( 'a' );
		qcancel.href = '';
		qcancel.alt = 'Ακύρωση';
		qcancel.title = 'Ακύρωση';
		qcancel.onclick = (function( id, answer ){ return function(){ Profileq.EditCancel(id, answer);return false;} })(id, answer);
		qcancel.appendChild( imagecancel );
		
		
		qform.appendChild( qid );
		qform.appendChild( qanswer );
		qform.appendChild( d.createTextNode( " " ) );
		qform.appendChild( qsubmit );
		qform.appendChild( d.createTextNode( " " ) );
		qform.appendChild( qcancel );
		element.appendChild( qform );
	},
    EditCallback : function( id, answer, answerraw ) {
		var q = document.getElementById( 'qedit_' + id );
		while ( q.firstChild ) {
   			q.removeChild( q.firstChild );
		}
		
		var l = document.createElement( 'a' );
		l.onclick = function() { Profileq.Edit( id );return false;}
		l.href = "";
		l.title = "Επεξεργασία ερώτησης";

		var imag = document.createElement( 'img' );
		imag.src = "http://static.chit-chat.gr/images/icons/icon_wand.gif";
		imag.width = "16";
		imag.height = "16";
		imag.alt = "Επεξεργασία Ερώτησης";

		l.appendChild( imag );
		q.innerHTML = answer;
		q.appendChild( l );

        g( 'qraw_' + id ).firstChild.nodeValue = answerraw;
    },
    EditCancel : function( id, answer ) {
		var element = g( 'qedit_' + id );
		while (element.firstChild) {
			element.removeChild(element.firstChild);
		}
		
		var raw = g( 'qraw_' + id )
		while (raw.firstChild) {
			raw.removeChild(raw.firstChild);
		}
		
		var qeditlink = d.createElement( 'a' );
		qeditlink.href = 'javascript: Profileq.Edit( ' + id + ' )';
		qeditlink.title = 'Επεξεργασία Ερώτησης';
		var qeditimg = d.createElement( 'img' );
		qeditimg.src = 'http://static.chit-chat.gr/images/icons/icon_wand.gif';
		qeditimg.width = '16';
		qeditimg.height = '16';
		qeditimg.alt = 'Επεξεργασία Ερώτησης';
		
		qeditlink.appendChild( qeditimg );
		
		element.appendChild( d.createTextNode( answer ) );
		element.appendChild( d.createTextNode( " " ) );
		element.appendChild( qeditlink );

		raw.appendChild( d.createTextNode( answer ) );
	},
	AnswerCallback : function( id, answer, answerraw ) {
		var element = g( 'newquest' );
		var form = g( 'newquestform' );
		while( form.firstChild ) {
			form.removeChild(form.firstChild);
		}
		element.removeChild(form);

        var bigdiv = d.createElement( 'div' );
        bigdiv.id = 'qedit_' + id;
        bigdiv.innerHTML = answer;

        var editb = d.createElement( 'a' );
        editb.onclick = function () {
                    Profileq.Edit(id);
                    return false;
                };
        editb.href='';
        editb.title='Επεξεργασία ερώτησης';
        
        var accept = d.createElement( 'img' );
        accept.src = 'http://static.chit-chat.gr/images/icons/icon_wand.gif';
        accept.style.width = '16px';
        accept.style.height = '16px';
        accept.alt = 'Επεξεργασία ερώτησης';

        editb.appendChild( accept );
        bigdiv.appendChild( editb );

        var raw = d.createElement( 'div' );
        raw.id = 'qraw_' + id;
        raw.style.display = 'none';
        raw.appendChild( d.createTextNode( answerraw ) );

        element.appendChild( bigdiv );
        element.appendChild( raw );
        element.id = '';

	},
    ShowNewQuestion : function ( id, question, preid ) {
        var prequestion = g( 'qedit_' + preid );
        var father = prequestion.parentNode.parentNode;

        var newquest = d.createElement( 'div' );
        newquest.id = 'newquest';

        var bold = d.createElement( 'b' );
        bold.appendChild( d.createTextNode( question ) );
        newquest.appendChild(bold);
        newquest.appendChild( d.createElement( 'br' ) );

        var form = d.createElement( 'form' );
        form.id = 'newquestform';
        form.onkeypress = function (e) {
                return submitenter( form, e );
             };
        form.onsubmit = function () {
                Profileq.Save( id );
                return false;
            };

        var input = d.createElement( 'input' );
        input.type = 'text';
        input.className = 'mybigtext';
        input.size = 60;
        input.id = 'qanswer';

        var link = d.createElement( 'a' );
        link.href = '';
        link.onclick = function () {
                g( 'newquestform' ).onsubmit();
                return false;
            };
        link.alt = "Αποθήκευση";
        link.title = "Αποθήκευση";

        var image = d.createElement( 'img' );
        image.src = 'http://static.chit-chat.gr/images/icons/accept.png';

        var other = d.createElement( 'a' );
        other.href='';
        other.onclick = function () {
                Coala.Warm( 'question/changeq', { 'id':id, 'callback':Profileq.changeQuestion } );
                return false;
            };
        other.alt = "Αλλαγή Ερώτησης";
        other.title = "Αλλαγή Ερώτησης";

        var changeimage = d.createElement( 'img' );
        changeimage.src = 'http://static.chit-chat.gr/images/icons/arrow_refresh.png';

        link.appendChild( image );
        other.appendChild( changeimage );
        form.appendChild( input );
        form.appendChild( d.createTextNode( ' ' ) );
        form.appendChild( link );
        form.appendChild( d.createTextNode( ' ' ) );
        form.appendChild( other );
        newquest.appendChild( form );
        father.insertBefore( newquest, father.childNodes[3] );
        father.insertBefore( d.createElement( 'br' ), father.childNodes[3] );
        input.focus();
    },
    Save : function( id ) {
        g('qanswer').blur();
        var answer=g('qanswer').value;
		if( answer != '' ) {
			Profileq.Wait( );
			Coala.Warm( 'question/answer', {'questionid': id, 'answer':answer, 'callback' : Profileq.AnswerCallback, 'newquest' : Profileq.ShowNewQuestion } );
			d.body.style.cursor = "default";
		}
		else {
			alert( 'Δεν μπορείς να έχεις κενή απάντηση!' );
		}
		return false;
	},
    changeQuestion : function ( newid , newquestion ) {
        var bigdiv = g('newquest');
        // When the page is loaded there is an empty text node between the <div> and the <b>.This node is removed
        // when a question is answered
        var oldquestion = (bigdiv.firstChild.nodeName.toLowerCase() == "#text")?bigdiv.childNodes[1].firstChild:bigdiv.firstChild.firstChild;
        oldquestion.nodeValue = newquestion;

        var form = g('newquestform');
        form.onsubmit = function () {
                return Profileq.Save( newid );
            };

        var link = form.childNodes[4].nodeName.toLowerCase() == "#text"?form.childNodes[5]:form.childNodes[4];
        link.onclick = function () {
                Coala.Warm( 'question/changeq', { 'id':newid, 'callback':Profileq.changeQuestion } );
                g( 'qanswer' ).focus();
                return false;
        };
    },
    Wait : function ( ) {
    	document.body.style.cursor = "wait";
    	
    	var element = g( 'newquestform' );
    	for( var i in element.childNodes ) {
    		var child = element.childNodes[ i ];
			if( child.nodeType == 1 ) {
				child.style.display = 'none';
			}
		}
	
		var loading = d.createElement( 'span' );
		loading.id = "loading";
		loading.style.opacity = "0.5";
		loading.appendChild( d.createTextNode( "Αποθήκευση.." ) );
		
		element.appendChild( loading );
	}
};

