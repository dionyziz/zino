var maxi = 0;
var cur = 0;

function loaded() {
    cur++;
    document.getElementById( "percentage" ).style.width = (cur/maxi)*500 +  'px';
    if( cur == maxi ) {
        document.getElementById( "kolaz" ).style.display = "block";
        document.body.removeChild( document.getElementById( "progress" ) );
    }
}
