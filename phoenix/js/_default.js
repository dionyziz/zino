function GetUsername() {
	var username = false;
	if ( $( 'a.profile' )[ 0 ] ) {
		if ( $( 'a.profile span.imageview img' )[ 0 ] ) {
			username = $( 'a.profile span.imageview img' ).attr( 'alt' ); //get the username of the logged in user from the banner
		}
		else {
			//for users without avatar
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
    var AdminUsers = new Array( 'dionyziz' , 'izual' , 'kostis90gr' , 'pagio' , 'd3nnn1z' , 'indy' );
	if ( $.browser.mozilla ) {
		$("img").lazyload( { 
			threshold : 200
		} );
	}
    var loggedinuser = GetUsername();
    if ( loggedinuser == 'dionyziz' || loggedinuser == 'izual' || loggedinuser == 'kostis90gr' || loggedinuser == 'pagio' || loggedinuser == 'indy' || loggedinuser == 'd3nnn1z' ) {
        var renderingend = new Date();
        var renderspan = document.createElement( 'div' );
        renderspan.appendChild( document.createTextNode( 'Rendering time: ' + ( renderingend.getTime() - renderingstart.getTime() ) / 1000 + ' seconds' ) ); 
        $( renderspan ).css( "color" , "red" );
        $( 'div.footer' ).append( renderspan );
    }
} );
