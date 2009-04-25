<?php
    /* 
        Developer: Dionyziz
    */

    interface MemCacheOperations {
        public function get( $key );
        public function get_multi( $keys );
        public function add( $key, $value, $expires = 0 );
        public function set( $key, $value, $expires = 0 );
        public function delete( $key, $aftertimeout = 0 );
        public function replace( $key, $value );
    }
    
    final class MemCacheDummy implements MemCacheOperations {
        public function MemCacheDummy() {
        }
        public function get( $key ) {
            return false; // not found
        }
        public function get_multi( $keys ) {
            return false; // not found
        }
        public function add( $key , $value , $expires = 0 ) {
            return true; // success
        }
        public function set( $key, $value, $expires = 0 ) {
            return true; // success
        }
        public function delete( $key , $aftertimeout = 0 ) {
        }
        public function replace( $key , $value ) {
        }
    }
    
    final class MemCacheSQL implements MemCacheOperations {
        private $mRequestCaches;
        private $mDbTableAlias;
        private $mDb;

        public function MemCacheSQL( DBTable $table ) {
            $this->mRequestCaches = array();

            w_assert( $table instanceof DBTable );
            w_assert( $table->Exists() );

            $this->mDb = $table->Database;
            $this->mDbTableAlias = $table->Alias;
        }
        public function get( $key ) {
            $multi = array( $key );
            $ret = $this->get_multi( $multi );
            
            return array_shift( $ret );
        }
        public function get_multi( $keys ) {
            $ret = array();
            foreach ($keys as $i => $key) {
                if ( isset( $this->mRequestCaches[ $key ] ) ) { 
                    $ret[ $key ] = $this->mRequestCaches[ $key ];
                    unset( $keys[ $i ] );
                }
                else {
                    $ret[ $key ] = false;
                    $keys[ $i ] = '\'' . addslashes( $key ) . '\'';
                }
            }
            
            if ( count( $keys ) ) {
                $query = $this->mDb->Prepare(
                    "SELECT
                        `mc_key`, `mc_value`
                    FROM
                        :" . $this->mDbTableAlias . "
                    WHERE
                        `mc_key` IN :_keys
                    AND
                        `mc_expires` >= NOW();"
                );
                $query->BindTable( $this->mDbTableAlias );
                $query->Bind( '_keys', $keys );
                $res = $query->Execute();
                while ( $row = $res->FetchArray() ) {
                    $key = $row[ 'mc_key' ];
                    $value = $row[ 'mc_value' ];
                    w_assert( isset( $ret[ $key ] ) );
                    w_assert( $ret[ $key ] === false );
                    $ret[ $key ] = $this->mRequestCaches[ $key ] = unserialize( $value );
                }
            }
            
            return $ret;
        }
        public function add( $key , $value , $expires = 0 ) {
            $value = $this->get( $key );

            if ( $value === false ) {
                $this->set( $key, $value, $expires );
            }
        }
        public function set( $key, $value, $expires = 0 ) {
            if ( $expires == 0 ) {
                $expires = 30 * 60; // by default expire in 30 minutes
            }
            
            $this->mRequestCaches[ $key ] = $value;
            
            w_assert( is_numeric( $expires ) );
            $query = $this->mDb->Prepare(
                "REPLACE DELAYED INTO
                    " . $this->mDbTableAlias . "
                (`mc_key`, `mc_value`, `mc_expires`) VALUES
                (:_key, :_value, NOW() + INTERVAL $expires SECOND);"
            );
            $query->BindTable( $this->mDbTableAlias );
            $query->Bind( '_key', $key );
            $query->Bind( '_value', $value );

            $change = $query->Execute( $sql );
            
            return true;
        }
        public function delete( $key , $aftertimeout = 0 /* N/A yet */ ) {
            global $water;
            
            if ( $aftertimeout > 0 ) {
                $water->Notice( 'Warning: SQL-based memcache doesn\'t support delayed deletes!', $key );
            }
            
            $query = $this->mDb->Prepare(
                "DELETE FROM
                    " . $this->mDbTableAlias . "
                WHERE
                    `mc_key`=:_key
                LIMIT 1"
            );
            $query->BindTable( $this->mDbTableAlias );
            $query->Bind( '_key', $key );
            $query->Execute();

            unset( $this->mRequestCaches[ $key ] );
            
            return true;
        }
        public function replace( $key , $value ) {
            $ret = $this->get( $key );
            if ( $ret !== false ) {
                $this->add( $key , $value );
            }

            return true;
        }
        protected function cleanup() {
            $query = $this->mDb->Prepare(
                "DELETE
                    *
                FROM
                    " . $this->mDbTableAlias . "
                WHERE
                    `mc_expires` < NOW()"
            );
            $query->BindTable( $this->mDbTableAlias );
            $change = $query->Execute();
            
            $this->mRequestCaches = array();
            
            return $change->AffectedRows();
        }
    }
    
    global $mc;
    global $libs;
    global $rabbit_settings;
    
    $libs->Load( 'rabbit/memcache/memcached' );
    
    switch ( $rabbit_settings[ 'memcache' ][ 'type' ] ) {
        case 'sql':
            $mc = New MemCacheSQL( $rabbit_settings[ 'memcache' ][ 'dbtable' ] );
            break;
        case 'memcached':
            $server = $rabbit_settings[ 'memcache' ][ 'hostname' ] . ':' . $rabbit_settings[ 'memcache' ][ 'port' ];
            $mc = New memcached( array(
                'servers' => array( $server ), 
                'debug' => false, 
                'compress_threshold' => 10240,
                'persistant' => true
            ) );
            break;
        case 'dummy':
        default:
            $mc = New MemCacheDummy();
            break;
    }
?>
