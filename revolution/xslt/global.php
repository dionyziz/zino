<?php
isset( $js ) or header( 'Content-Type: text/xsl; charset=UTF-8' );

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
        echo '<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />
    <xsl:template match="/">';
        echo "global.xsl: File \"$line\" does not exist
    </xsl:template>
</xsl:stylesheet>";
        exit;
    }
    $modtime = filemtime( $line );
    $datelist[ $line ] = filemtime( $line );
    if ( $maxtime < $modtime ) {
        $maxtime = $modtime;
    }
}

if ( USE_CACHING ) {
    header( 'Pragma: public' );
    header( 'Cache-Control: maxage=' . (60*60*24*356) );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' , $maxtime ).' GMT' );
    if ( @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $maxtime ) {
        header( 'HTTP/1.1 304 Not Modified' );
        exit;
   }
}

$entries = array();

foreach ( $list as $line ) {
    $line = trim( $line );
    $entryfh = @fopen( $line, 'r' );
    $entry = @fread( $entryfh, filesize( $line ) ); 
    if ( mb_detect_encoding( $entry ) == 'ASCII' ) {
        $entry = utf8_encode( $entry );
    }
    echo $entry;
    echo "\n";
    @fclose( $entryfh );
}

// echo '<!-- Last modified time: ' . $maxtime . ', Caching: ' . (int) ( USE_CACHING == true ) . '-->';
?>
