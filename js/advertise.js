var advertise = {
	SendEmail : function() {
		var inputlist = document.getElementsByTagName( 'input' );
		alert( 'inputlist length :' + inputlist.length );
		inputlist[ 0 ].style.border = '1px solid red';
		inputlist[ 1 ].style.border = '1px solid yellow';
		inputlist[ 2 ].style.border = '1px solid green';
		alert( 'inputlist is: ' + inputlist );
		//var mailadress = inputlist[ 0 ].value;
		var mailadress = document.getElementById( 'mailadress' ).value;
		var textarealist = document.getElementsByTagName( 'textarea' );
		var mailtext = textarealist[ 0 ].value;
		alert( 'email is :' + mailadress );
		alert( 'emailtext is :' + mailtext );	
	}
}