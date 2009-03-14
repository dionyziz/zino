var maxi = 0;
var cur = 0;

function loaded() {
    cur++;
    document.getElementById( "percentage" ).style.width = (cur/maxi)*250 +  'px';
    maxi = document.getElementsByTagName( "img" ).length
    alert( cur + " " + maxi );
    if( cur == maxi ) {
        document.getElementById( "kolaz" ).style.display = "block";
        document.getElementById( "content" ).removeChild( document.getElementById( "progress" ) );
    }
}
