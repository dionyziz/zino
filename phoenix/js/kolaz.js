var maxi = 3;
var cur = 0;

function loaded() {
    cur++;
    document.getElementById( "percentage" ).style.width = (cur/maxi)*250 +  'px';
    if( cur == maxi ) {
        document.getElementById( "kolaz" ).style.display = "block";
        document.body.removeChild( document.getElementById( "progress" ) );
    }
}
