<?php
    /* 
        Developer: Dionyziz
        
        Stand-alone static content script;
        Until we move to a proper static server,
        speeds up loading by forcing client-side caching
        (in accordance to .htaccess)
        Use ?version to force reload, e.g. /static/folder/file?43
    */
    
    if ( !isset( $_GET[ 'file' ] ) ) {
        header( 'HTTP/1.1 403 Forbidden' );
        return;
    }
    $file = $_GET[ 'file' ];
    if ( !file_exists( $file ) ) {
        header( 'HTTP/1.1 404 Not Found' );
        return;
    }
    
    if ( strpos( $file, '..' ) !== false ) {
        header( 'HTTP/1.1 404 Not Found' );
        return;
    }
    
    $extension = substr( $file , strrpos( $file , '.' ) + 1 );
    $EXTENSION2MIME = array(
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'txt'  => 'text/plain',
        'html' => 'text/html',
        'js'   => 'text/javascript',
        'css'  => 'text/css'
    );
    
    if ( !isset( $EXTENSION2MIME[ strtolower( $extension ) ] ) ) {
        header( 'HTTP/1.1 404 Not Found' );
        return;
    }
    
    $mime = $EXTENSION2MIME[ strtolower( $extension ) ];

    $sendcontent = true;
    $lastmodified = filemtime( $file );
    if ( isset( $_SERVER[ 'HTTP_IF_MODIFIED_SINCE' ] ) && strtotime( $_SERVER[ 'HTTP_IF_MODIFIED_SINCE' ] ) >= $lastmodified ) {
        header( 'HTTP/1.1 304 Not Modified' );
        $sendcontent = false;
    }
    
    header( 'Content-type: ' . $mime );
    header( 'Cache-Control: public, max-age=' . 60 * 60 * 24 * 7 );
    header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + 60 * 60 * 24 * 7 ) . " GMT" );
    header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", $lastmodified ) . " GMT" );
    header( "Pragma: " );
    if ( $sendcontent ) {
        ob_start( 'ob_gzhandler' );
        $contents = file_get_contents( $file );
        if ( $extension == 'js' ) {
            require '../libs/jsmin.php';
            $contents = JSMin::minify( $contents );
        }
        echo $contents;
    }
?>
