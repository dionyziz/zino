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
    var renderingend = new Date();
    //alert( "Render time: " + ( renderingend.getTime() - renderingstart.getTime() )/1000 + " seconds" );
} );
