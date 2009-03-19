var maxi = 0;
var cur = 0;
var first = true;

function loaded() {
    if ( first === true ) {
        maxi = document.getElementById( "kolaz" ).getElementsByTagName( "img" ).length;
        first = false;
    }

    ++cur;
    document.getElementById( "percentage" ).style.width = (cur/maxi)*250 +  'px';
    if ( cur == maxi ) {
        document.getElementById( "kolaz" ).style.display = "block";
        document.getElementById( "content" ).removeChild( document.getElementById( "progress" ) );
    }
}
