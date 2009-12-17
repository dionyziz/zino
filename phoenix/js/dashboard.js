var prevW = -1;
var prewH = -1;

function HandleResize() {
    var h = 0;
    var w = 0;
    
    if ( typeof window.innerHeight != 'undefined' && window.innerHeight ) {
        h = window.innerHeight;
        w = window.innerWidth;
    }
    else if ( typeof document.documentElement.clientHeight != 'undefined' && document.documentElement.clientHeight ) {
        h = document.documentElement.clientHeight;
        w = document.documentElement.clientWidth;
    }
    else {
        h = document.body.clientHeight;
        w = document.body.clientWidth;
    }
    
    if ( w == prevW && h == prevH ) {
        return;
    }
    
    prevW = w;
    prevH = h;
    
    var notifications = $( '#notifications' )[ 0 ];
    var frontpage = $( '#frontpage' )[ 0 ];
    
    $( '#nowbar' )[ 0 ].style.height = h - 5 + 'px';
    $( '#chat' )[ 0 ].style.height = Math.floor( ( h - 18 ) / 2 ) + 'px';
    $( '#friends' )[ 0 ].style.height = Math.floor( ( h - 18 ) / 2 ) + 'px';
    $( '#friends ol' )[ 0 ].style.height = Math.floor( ( h - 220 ) / 2 ) + 'px';
    $( '#chat ol' )[ 0 ].style.height = Math.floor( ( h - 220 ) / 2 ) + 'px';
    $( '#frontpage' )[ 0 ].style.width = document.body.offsetWidth - $( '#nowbar' )[ 0 ].offsetWidth + 'px';
    $( '#frontpage' )[ 0 ].style.height = h + 'px';
    
    notifications.style.marginLeft = Math.round( frontpage.offsetWidth / 2 - notifications.offsetWidth / 2 ) - 20 + 'px';
}
function OnLoad() {
    Kamibu.ClickableTextbox( $( 'textarea' )[ 0 ], true, 'black', '#aaa' );
    Kamibu.ClickableTextbox( $( 'input' )[ 0 ], true, 'black', '#aaa' );
    HandleResize();
    // setInterval( HandleResize, 2000 );
}

