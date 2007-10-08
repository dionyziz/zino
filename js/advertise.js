var advertise = {
	SendEmail : function() {
		var inputlist = document.getElementById( 'body' ).getElementsByTagName( 'input' );
		var mailadress = inputlist[ 0 ].value;
		var textarealist = document.getElementsByTagName( 'textarea' );
		var mailtext = textarealist[ 0 ].value;
		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if ( filter.test( mailadress ) ) {
			alert( 'valid email' );
		}
		else {
			alert( 'invalid email' );
		}
		//alert( 'email is :' + mailadress );
		//alert( 'emailtext is :' + mailtext );	
	}
}