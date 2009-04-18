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

            foreach ( $ids as $id ) {
                w_assert( is_int( $id ) );
            }

            $keyids = array_flip( $ids );
            $already = array_intersect_key( $keyids, self::$mFetched );
            foreach ( $already as $id ) {
                die( "Found $id in cache" );
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
            global $db;

            $query = $db->Prepare( 
                "SELECT
                    `bulk_id`, `bulk_text`
                FROM
                    :bulk
                WHERE
                    `bulk_id` IN :Ids;"
            );

            $query->BindTable( 'bulk' );
            $query->Bind( 'Ids', $ids );
            
            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[ $row[ "bulk_id" ] ] = $row[ 'bulk_text' ];
                self::$mFetched[ $row[ "bulk_id" ] ] = $row[ 'bulk_text' ]; // add data to cache
            }

            return $ret;
        }
        static public function Store( $text, $id = 0 ) {
            global $db;

            if ( strlen( $text ) > pow( 2, 20 ) ) { // strlen is significant; do not change to mb_strlen, as we want to count actual bytes
                // if text is more than 1MB
                // drop it
                $text = substr( $text, 0, pow( 2, 20 ) );
            }
            if ( $id == 0 ) {
                $query = $db->Prepare(
                    "INSERT INTO :bulk ( `bulk_text` ) VALUES
                                       ( :text );"
                );
            }
            else {
                $query = $db->Prepare(
                    "UPDATE :bulk SET `bulk_text`=:text WHERE `bulk_id`=:id LIMIT 1;"
                );
                $query->Bind( 'id', $id );
            }
            $query->BindTable( 'bulk' );
            $query->Bind( 'text', $text );
            $result = $query->Execute();
            if ( $id == 0 ) {
                $id = $result->InsertId();
            }
            return $id;
        }
        static public function Delete( $ids ) {
            global $db;
            
            if ( !is_array( $ids ) ) {
                w_assert( is_int( $ids ) );
                $ids = array( $ids );
            }
            $query = $db->Prepare( "DELETE FROM :bulk WHERE `bulk_id` IN :Ids;" );
            $query->BindTable( 'bulk' );
            $query->Bind( 'Ids', $ids );
            foreach ( $ids as $id ) {
                unset( self::$mFetched[ $id ] );
            }
        }
    }
?>
