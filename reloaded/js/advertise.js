var advertise = {
	SendEmail : function() {
		var body = document.getElementById( 'body' );
		var mailadress = body.getElementsByTagName( 'input' )[ 0 ];
		var mailtext = document.getElementsByTagName( 'textarea' )[ 0 ];
		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if ( filter.test( mailadress.value ) ) {
            Coala.Warm( 'advertise/sendemail' , { text : mailtext.value , from : mailadress.value , domnode : mailtext } );
		}
		else {
			var wrongmailspan = document.createElement( 'span' );
			wrongmailspan.style.paddingLeft = '15px';
			wrongmailspan.appendChild( document.createTextNode( 'Παρακαλώ δώστε ένα έγκυρο email' ) );
			mailadress.parentNode.insertBefore( wrongmailspan , mailadress.nextSibling );
			Animations.Create( wrongmailspan , 'opacity' , 12000 , 1 , 0 , function() {
				body.removeChild( wrongmailspan );
			} );
			
			mailadress.focus();
			mailadress.select();
		}
	}
}
