var advertise = {
	SendEmail : function() {
		var inputlist = document.getElementById( 'body' ).getElementsByTagName( 'input' );
		var mailadress = inputlist[ 0 ].value;
		var textarealist = document.getElementsByTagName( 'textarea' );
		var mailtext = textarealist[ 0 ].value;
		alert( 'email is :' + mailadress );
		alert( 'emailtext is :' + mailtext );	
	}
}