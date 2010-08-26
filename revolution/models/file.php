<?php
    class File {
        public static function Create( $userid, $tempfile, $filename ) {
            $url = File::Upload( $tempfile, $filename );
            clude( 'models/agent.php' );
            $ip = UserIp();
            db(
                'INSERT INTO `files` SET
                    `file_url` = :url,
                    `file_userid` = :userid,
                    `file_userip` = :ip', compact( 'url', 'userid', 'ip' )
            );
            $id = mysql_insert_id();
            return array(
                'id' => $id,
                'url' => $url
            );
        }
        public static function Upload( $tempfile, $filename ) {
            if ( filesize( $tempfile ) > 4 * 1024 * 1024 ) { // 4 MB
                throw New Exception( 'File::Upload() failed: File size limit of 4MB exceeded' );
            }

            if ( !file_exists( $tempfile ) ) {
                throw New Exception( 'File::Upload() failed: Target temporary file does not exist: ' . $tempfile );
            }

            $data = array(
                'file' => "@$tempfile",
                'filename' => $filename
            );

            $curl = curl_init();
            $header[ 0 ] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
            $header[] = "Accept-Language: en-us,en;q=0.5";
            $header[] = "Accept-Encoding: gzip,deflate";
            $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
            $header[] = "Keep-Alive: 300";
            $header[] = "Connection: keep-alive";
            $header[] = "Expect:";
        
            $curl = curl_init();
            curl_setopt( $curl, CURLOPT_URL, 'http://files.zino.gr/upload.php' );
            curl_setopt( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8) Gecko/20071030 Firefox/2.0.0.8" );
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' );
            curl_setopt( $curl, CURLOPT_AUTOREFERER, true );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $curl, CURLOPT_POST, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

            $data = curl_exec( $curl );

            if ( $data === false ) {
                throw New Exception( 'File::Upload curl error: ' . curl_error( $curl ) . ' (' . curl_errno( $curl ) . ')' );
            }

            curl_close( $curl );

            if ( strpos( $data, "FAIL" ) !== false ) {
                throw New Exception( 'File::Upload could not upload the file: Server returned an error: ' . $data );
            }
            else if ( strpos( $data, "SUCCESS" ) !== false ) {
                $url = substr( $data, strlen( 'SUCCESS: ' ) );
                return $url;
            }
            throw New Exception( 'File::Upload could not upload the file: Server returned an unknown state: ' . $data );
        }
    }
?>
