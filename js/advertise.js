var advertise = {
	SendEmail : function() {
		var body = document.getElementById( 'body' );
		var inputlist = body.getElementsByTagName( 'input' );
		var mailadress = inputlist[ 0 ].value;
		var textarealist = document.getElementsByTagName( 'textarea' );
		var mailtext = textarealist[ 0 ].value;
		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if ( filter.test( mailadress ) ) {
			alert( 'valid email' );
		}
		else {
			var wrongmailspan = document.createElement( 'span' );
			wrongmailspan.style.paddingLeft = '5px';
			wrongmailspan.appendChild( document.createTextNode( 'Παρακαλώ δώστε ένα έγκυρο email' ) );
			alert( mailaress.nextSibling );
			body.insertBefore( wrongmailspan , mailadress.nextSibling );
		}
		//alert( 'email is :' + mailadress );
		//alert( 'emailtext is :' + mailtext );	
	}
}