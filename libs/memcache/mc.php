<?php
	/* 
		Developer: Dionyziz
	*/
	
    interface MemCache {
        public function get( $key );
        public function get_multi( $keys );
        public function add( $key, $value, $expires = 0 );
        public function delete( $key, $aftertimeout = 0 );
        public function replace( $key, $value );
    }
    
	final class MemCacheDummy implements MemCache {
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
		public function delete( $key , $aftertimeout = 0 ) {
		}
		public function replace( $key , $value ) {
		}
	}
	
	final class MemCacheSQL implements MemCache {
		private $mRequestCaches;
		
		public function MemCacheSQL() {
			$this->mRequestCaches = array();
		}
		public function get( $key ) {
			$multi = array( $key );
			$ret = $this->get_multi( $multi );
            
			return array_shift( $ret );
		}
		public function get_multi( $keys ) {
			global $memcachesql;
			global $db;
			
			$ret = array();
			foreach ($keys as $i => $key) {
				if ( isset( $this->mRequestCaches[ $key ] ) ) { 
					$ret[ $key ] = $this->mRequestCaches[ $key ];
					unset( $keys[ $i ] );
				}
				else {
					$ret[ $key ] = false;
					$keys[ $i ] = '\'' . myescape( $key ) . '\'';
				}
			}
			
			if ( count( $keys ) ) {
				$sql = "SELECT
							`mc_key`, `mc_value`
						FROM
							`$memcachesql`
						WHERE
							`mc_key` IN (" . implode( ',', $keys ) . ")
						AND
							`mc_expires` >= NOW();";
				$res = $db->Query( $sql );
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
			global $memcachesql;
			global $db;
			
			if ( $expires == 0 ) {
				$expires = 30 * 60; // by default expire in 30 minutes
			}
			
			$this->mRequestCaches[ $key ] = $value;
			
			$key = myescape( $key );
			$value = myescape( serialize( $value ) );
			w_assert( is_numeric( $expires ) );
			$sql = "REPLACE DELAYED INTO
						`$memcachesql`
					(`mc_key`, `mc_value`, `mc_expires`) VALUES
					('$key', '$value', NOW() + INTERVAL $expires SECOND);";
			$change = $db->Query( $sql );
			
			return true;
		}
		public function delete( $key , $aftertimeout = 0 /* N/A yet */ ) {
			global $memcachesql;
			global $db;
			global $water;
			
			if ( $aftertimeout > 0 ) {
				$water->Notice( 'Warning: SQL-based memcache doesn\'t support delayed deletes!', $key );
			}
			
			$key = myescape( $key );
			$sql = "DELETE FROM
						`$memcachesql`
					WHERE
						`mc_key`='$key'
					LIMIT 1";
			$db->Query( $sql );

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
			global $db;
			
			$sql = "DELETE
						*
					FROM
						`$memcachesql`
					WHERE
						`mc_expires` < NOW()";
			
			$change = $db->Query( $sql );
			
			$this->mRequestCaches = array();
			
			return $change->AffectedRows();
		}
	}
	
	global $mc;
	global $libs;
    global $xc_settings;
    
    $libs->Load('memcache/memcached');
    
	// until we get a proper memcache daemon
    if ( $xc_settings[ 'memcache' ] == 'sql' ) {
    	$mc = New MemCacheSQL();
    }
    else if ( $xc_settings[ 'memcache' ] == 'memcached' ) {
        /* $mc = New MemCacheDummy(); */
        $mc = New memcached(array(
                'servers' => array('127.0.0.1:11211'), 
                'debug' => false, 
                'compress_threshold' => 10240,
                'persistant' => true));
    }
?>
