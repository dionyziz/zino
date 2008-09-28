<?php
	/*
		Developer: Dionyziz
	*/
	
	/*
		Usage:
		$stories_bodies = $blk->Get( $bulkids );
	*/
	
	final class Bulk { // singleton
		private $mFetched; // array for caching bulk data in the current request
		private $mBulkTable;
		
		public function Bulk() {
			global $bulk;
			
			w_assert( !empty( $bulk ) );
			$this->mBulkTable = $bulk; // store in local scope
			$bulk = false; // shadow
			// we start with an empty cache
			$this->mFetched = array();
		}
		public function Get( $ids ) {
			// function to get bulk data from some given ids
			// passing an array of ids speeds up the operation
			$ret = array();
			if ( !is_array( $ids ) ) { // if the programmer is passing only one id, make it an array for data consistency
				$ids = array( $ids );
				$was_array = false;
			}
			else {
				$was_array = true;
			}
			foreach ( $ids as $id ) { // make sure they are numeric and SQL-safe
				w_assert( is_numeric( $id ) );
			}
			$keyids = array_flip( $ids ); // create a table where the keys of the ids are the values and vice versa
										  // to use with the next function (axel: perhaps refer to PHP manual?)
			// $already will be an array whose values will be the keys of $ids that have already been fetched previously
			$already = array_intersect_key( $keyids , $this->mFetched ); // (axel: perhaps refer to PHP manual?)
			foreach ( $already as $gotit ) { // fill in the return array from the cached data
				$ret[ $ids[ $gotit ] ] = $this->mFetched[ $ids[ $gotit ] ];
				unset( $ids[ $gotit ] );
			}
			if ( count( $ids ) ) { // if there are more keys requested that haven't been cached, fetch them
                // + operator between arrays -- merges arrays preserving keys
				$ret = $ret + $this->Fetch( $ids ); // return the combination of the cached ones and the fetched ones
			}
			if ( $was_array ) {
				return $ret; // array( id => value , id => value , ... )
			}
			return array_shift( $ret );
		}
		private function Fetch( $ids ) {
			// function that actually fetched the bulk data based on ids
			global $db;
			
			$ret = array();
			$sql = "SELECT
						`bulk_id`, `bulk_text`
					FROM
						`" . $this->mBulkTable . "`
					WHERE
						`bulk_id` IN (" . implode( ', ' , $ids ) . ")";
			$res = $db->Query( $sql );
			while ( $row = $res->FetchArray() ) {
				$ret[ $row[ 'bulk_id' ] ] = $row[ 'bulk_text' ];
			}
			return $ret;
		}
		public function Add( $text ) {
			// function to add bulk data into the database
			global $db;
			
			$insert = array( 'bulk_text' => $text );
			$change = $db->Insert( $insert , $this->mBulkTable );
			
			return $change->InsertId();
		}
	}
	
	global $blk; // singleton
	$blk = New Bulk();
?>
