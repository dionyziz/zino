<?php
header( 'Content-Type: application/javascript' );

if ( file_exists( '../nocache' ) ) {
    define( 'USE_CACHING', false );
}
else {
    define( 'USE_CACHING', true );
}

$listfh = fopen( 'global.lst', 'r' );
$list = fread( $listfh, filesize( 'global.lst' ) ); 
fclose( $listfh );

$list = explode( "\n", $list );

$maxtime = filemtime( 'global.lst' );
foreach ( $list as $num=>$line ) {
    $line = trim( $line );
    if ( $line == '' || $line == "\r" ) {
        unset( $list[ $num ] );
        continue;
    }
    if ( !file_exists( $line ) ) {
        //Generate viewable error
        echo "alert( 'global.js: File \"$line\" in global.lst does not exist' );";
        exit;
    }
    $modtime = filemtime( $line );
    $datelist[ $line ] = filemtime( $line );
    if ( $maxtime < $modtime ) {
        $maxtime = $modtime;
    }
}

header( 'Pragma: public' );
header( 'Cache-Control: maxage=' . (60*60*24*356) );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' , $maxtime ).' GMT' );

if ( USE_CACHING && @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $maxtime ) {
    header( 'HTTP/1.1 304 Not Modified' );
    exit;
}

foreach ( $list as $line ) {
    $line = trim( $line );
    $entryfh = @fopen( $line, 'r' );
    $entry = @fread( $entryfh, filesize( $line ) ); 
    echo $entry;
    echo "\n";
    @fclose( $entryfh );
}

?>
