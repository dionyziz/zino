var advertise = {
	SendEmail : function() {
		var inputlist = document.getElementsByTagName( 'input' );
		alert( 'inputlist length :' + inputlist.length );
		alert( 'inputlist is: ' + inputlist );
		//var mailadress = inputlist[ 0 ].value;
		var mailadress = document.getElementById( 'mailadress' ).value;
		var textarealist = document.getElementsByTagName( 'textarea' );
		var mailtext = textarealist[ 0 ].value;
		alert( 'email is :' + mailadress );
		alert( 'emailtext is :' + mailtext );	
	}
}