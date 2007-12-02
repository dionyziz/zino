<?php

    function Image_Upload( $path, $tempfile, $resizeto = false ) {
        global $xc_settings;
        global $rabbit_settings;
		global $user;              

		if ( $user->Rights() < $xc_settings[ "allowuploads" ] ) {
			return -1; // disallowed uploads
		}
        
        $curl = curl_init();

        $data = array(
            'path' => $path,
            'mime' => 'image/jpeg',
            'uploadimage' => "@$tempfile"
        );

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

        curl_close( $curl );

        $upload = array();

		if ( strpos( $data, "error" ) !== false && $user->IsSysOp() ) {
			die( $data );
		}
		else if ( strpos( $data, "error" ) !== false ) {
			$upload[ 'successful' ] = false;
		}
		else if ( strpos( $data, "success" ) !== false ) {
			$upload[ 'successful' ] = true;
			$start = strpos( $data, "[" ) + 1;

			$resolution = substr( $data, $start, strlen( $data ) - $start - 1 );
			$split = explode( "," , $resolution );
			$width = $split[ 0 ];
            $height = $split[ 1 ];
			$filesize = $split[ 2 ];
			$upload[ 'width' ] = (integer) $width;
			$upload[ 'height' ] = (integer) $height;
			$upload[ 'filesize' ] = (integer) $filesize;
		}
        else {
            if ( $user->IsSysOp() ) {
                die( $data );
            }
        }

		return $upload;
	}

?>
