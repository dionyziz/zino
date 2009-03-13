var z = 0;
var maximg = 0;
var imgs = {};
var numloaded = 0;

function loaded() {
    ++numloaded;
    //document.getElementById( 'percentage' ).style.width = ( numloaded / maxi ) * 200 + 'px';
    alert( "one more" );
    if ( numloaded == maxi ) {
        //document.body.removeChild( document.getElementById( "loading" ) );
        return;
    }
}

alert("inside");
maxi = document.getElementsByTagName( 'img' ).length;
for ( z = 0 ; z < maxi ; z += 1 ) { 
    alert( "one pic found!" );
    document.getElementsByTagName( 'img' )[ z ].onload = function () { loaded(); };
}
