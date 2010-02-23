<?php 
    /*
        Developers: abresas, dionyziz
    */

    class Bulk { // namespace
        private static $mFetched = array(); // array for caching bulk data in current request
        
        static public function FindById( $ids ) {
            $ret = array();
            if ( !is_array( $ids ) ) {
                $ids = array( $ids );
                $was_array = false;
            }
            else {
                $was_array = true;
            }

            $keyids = array_flip( $ids );
            $already = array_intersect( $ids, array_keys( self::$mFetched ) );
            foreach ( $already as $id ) {
                $ret[ $id ] = self::$mFetched[ $id ];
                unset( $ids[ $keyids[ $id ] ] );
            }

            if ( count( $ids ) ) {
                $ret += self::Fetch( $ids );
            }

            foreach ( $ids as $id ) {
                if ( !isset( $ret[ $id ] ) ) {
                    $ret[ $id ] = false; // always return what we were asked for
                }
            }

            if ( $was_array ) {
                return $ret;
            }

            return array_shift( $ret );
        }

        static private function Fetch( $ids ) {
            $res = db(
                "SELECT
                    `bulk_id`, `bulk_text`
                FROM
                    `bulk`
                WHERE
                    `bulk_id` IN :ids;",
                compact( 'ids' )
            );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[ $row[ "bulk_id" ] ] = $row[ 'bulk_text' ];
                self::$mFetched[ $row[ "bulk_id" ] ] = $row[ 'bulk_text' ]; // add data to cache
            }

            return $ret;
        }
        static public function Store( $text, $id = 0 ) {
            if ( strlen( $text ) > pow( 2, 20 ) ) { // strlen is significant; do not change to mb_strlen, as we want to count actual bytes
                // if text is more than 1MB
                // drop it
                $text = '';
            }
            if ( $id == 0 ) {
                db( "INSERT INTO `bulk` ( `bulk_text` ) VALUES ( :text );", compact( 'text' ) );
            }
            else {
                db( "UPDATE `bulk` SET `bulk_text`=:text WHERE `bulk_id`=:id LIMIT 1;", compact( 'id', 'text' ) );
            }
            if ( $id == 0 ) {
                $id = mysql_insert_id();
            }
            return $id;
        }
        static public function Delete( $ids ) {
            if ( !is_array( $ids ) ) {
                $ids = array( $ids );
            }
            db( "DELETE FROM `bulk` WHERE `bulk_id` IN :ids;", compact( 'ids' ) );
            foreach ( $ids as $id ) {
                unset( self::$mFetched[ $id ] );
            }
        }
    }
?>

