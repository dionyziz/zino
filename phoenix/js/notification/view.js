var Notification = {
	Visit : function( url , typeid ) {
		//type can be either comment or relation
		alert( typeid );
		document.location.href = url;
	}
};