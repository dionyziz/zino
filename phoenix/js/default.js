function GetUsername() {
	var username = false;
	if ( $( 'a.profile' )[ 0 ] ) {
		if ( $( 'a.profile span.imageview img' )[ 0 ] ) {
			username = $( 'a.profile span.imageview img' ).attr( 'alt' ); // get the username of the logged in user from the banner
		}
		else {
			// for users without avatar
			username = $( 'a.profile' ).text();
		}
	}
	else {
		username = false;
	}
	return username;
}
function IsAdmin( username ) {

}
$( function() {
    if ( $.browser.mozilla ) {
		$( "img:not(.nolazy)" ).lazyload( { 
			threshold : 200
		} );
	}
    /* 
    var loggedinuser = GetUsername();
    if ( loggedinuser == 'dionyziz' || loggedinuser == 'izual' || loggedinuser == 'kostis90gr' || loggedinuser == 'pagio' || loggedinuser == 'indy' || loggedinuser == 'd3nnn1z' ) { // should we change this now?  --Indy
	var renderingend = new Date();
	var renderspan = document.createElement( 'div' );
	renderspan.appendChild( document.createTextNode( 'Rendering time: ' + ( renderingend.getTime() - renderingstart.getTime() ) / 1000 + ' seconds' ) ); 
	$( renderspan ).css( {
	    'color' : 'red', 
	    'font-size' : '90%',
	    'text-align' : 'center'
	} );
	$( 'div.footer' ).append( renderspan );
    }
    */
} );
