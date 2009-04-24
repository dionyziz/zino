<?php
    function Image_Upload( $userid, $imageid, $tempfile, $resizeto = false /* 700x600 */ ) {
        global $xc_settings;
        global $rabbit_settings;
        global $user;              

        $curl = curl_init();

        if ( !file_exists( $tempfile ) ) {
            throw New Exception( 'Image_Upload() failed: Target temporary file does not exist: ' . $tempfile );
        }

        $data = array(
            'userid' => $userid,
            'imageid' => $imageid,
            'mime' => 'image/jpeg',
            'uploadimage' => "@$tempfile"
        );

        if ( $resizeto !== false ) {
            w_assert( preg_match( '#^[0-9]{1,4}x[0-9]{1,4}$', $resizeto ) );
            $data[ 'size' ] = $resizeto;
        }

        if ( !$rabbit_settings[ 'production' ] ) {
            $data[ 'sandbox' ] = 'yes';
        }

        $header[ 0 ] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Accept-Encoding: gzip,deflate";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Keep-Alive: 300";
        $header[] = "Connection: keep-alive";
        $header[] = "Expect:";
    
        $server = $xc_settings[ 'imagesupload' ][ 'host' ] . $xc_settings[ 'imagesupload' ][ 'url' ];
        curl_setopt( $curl, CURLOPT_URL, $server );
        curl_setopt( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8) Gecko/20071030 Firefox/2.0.0.8" );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' );
        curl_setopt( $curl, CURLOPT_AUTOREFERER, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

        // curl_setopt( $curl, CURLOPT_VERBOSE, 1 );

        $data = curl_exec( $curl );

        if ( $data === false ) {
            throw New ImageException( 'Image_Upload curl error: ' . curl_error( $curl ) . ' (' . curl_errno( $curl ) . ')' );
        }

        curl_close( $curl );

        if ( strpos( $data, "error" ) !== false ) {
            throw New ImageException( 'Image_Upload could not upload the image: Server returned an error: ' . $data );
        }
        else if ( strpos( $data, "success" ) !== false ) {
            // throw New ImageException( $data );

            $start = strpos( $data, "[" ) + 1;

            $data = substr( $data, $start, strlen( $data ) - $start - 1 );
            $split = explode( "," , $data );
            $width = $split[ 0 ];
            $height = $split[ 1 ];
            $filesize = $split[ 2 ];
            $mime = $split[ 3 ];

            $upload = array();
            $upload[ 'width' ] = ( integer )$width;
            $upload[ 'height' ] = ( integer )$height;
            $upload[ 'filesize' ] = ( integer )$filesize;
            $upload[ 'mime' ] = $mime;

            return $upload;
        }
        // err'd
        throw New ImageException( 'Image_Upload could not upload the image: Server returned an unknown state: ' . $data );
    }

?>
