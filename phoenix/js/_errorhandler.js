window.onerror = function( msg, url, linenumber ){
	Coala.Warm( 'errorhandler', {
			'msg': msg,
			'url': url,
			'linenumber': linenumber
		} );
	return false;
	);
};
