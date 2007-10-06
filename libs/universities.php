<?php
	/*
		Developer: Izual
		Backend for the universities task, masked
	*/
	function Uni_Retrieve( $placeid = 0 , $typeid = false ) {
		global $db;
		global $universities;
		
		if ( $placeid == 0 && $typeid ) {
			$condition = "`uni_typeid` = '" . myescape( $typeid ) . "'"; 
		}
		
		if ( $placeid != 0 && !$typeid ) {
			$condition = "`uni_placeid` = '" . myescape( $placeid ) . "'";
		}
		
		if ( $placeid != 0 && $typeid ) {
			$condition = "`uni_typeid` = '" . myescape( $typeid ) . "' AND `uni_placeid` = '" . myescape( $placeid ) . "'";
		}
		
		$sql = "SELECT
					*
				FROM 
					`$universities`
				WHERE " . $condition . ";";
				
		$res = $db->Query( $sql );
		
		$ret = array();
		
		while( $row = $res->FetchArray() ) {
			$ret[] = new Uni( $row );
		}
		
		return $ret;
	}
	final class Uni extends Satori {
		protected $mId;
		protected $mName;
		protected $mTypeId;
		protected $mPlaceId;
		protected $mDelId;
		
		public function Delete() {
			$this->mDelId = 1;
			$this->Save();
		}
		
		public function LoadDefaults() {
			$this->mDelId = 0;
			$this->Date = NowDate();
		}
		
		public function Save() {
			global $user;
			global $universities;
			global $db;
			global $water;
			
			if ( $this->Exists() ) {
				$sql = "UPDATE
							`$universities`
						SET 
							`uni_name` = '" . myescape( $this->mName ) . "',
							`uni_typeid` = '" . myescape( $this->mTypeId ) . "', 
							`uni_placeid` = '" . myescape( $this->mPlaceId) . "',
							`uni_delid` =  '" . myescape( $this->mDelId ) . "'
						WHERE
							`uni_id` = '" . $this->mId . "';";
			
				$change = $db->Query( $sql );
				
				return $change;
			}
			
			$this->mDbTable = $universities;
			$this->mDb = $db;
			
			$this->SetFields( array( 
				'uni_id' => 'Id',
				'uni_name' => 'Name',
				'uni_typeid' => 'TypeId',
				'uni_placeid' => 'PlaceId',
				'uni_delid' => 'DelId'
			) );
			//if not updating then insert
			$this->Satori( $construct );
			
			if ( empty( $this->Date ) ) { 
				$this->Date = NowDate();
			}
			
			$sqlarray = array( 
				'uni_name' => $this->mName,
				'uni_typeid' => $this->mTypeId,
				'uni_placeid' => $this->mPlaceId,
				'uni_createdate' => $this->Date,
				'uni_createip' => UserIp(),
				'uni_delid' => 0
			);
			
			$change = $db->Insert( $sqlarray , $universities );
			
			if ( $change === false ) {
				return false;
			}
			$this->mId = $change->InsertId();
			
			return $change;
		}
		public function Uni( $construct = false ) {
			global $universities;
			global $db;
			
			if ( !is_array( $construct ) ) {
				$construct = myescape( $construct );
				
				$sql = "SELECT 
							* 
						FROM 
							`$universities`
						WHERE
							`uni_id` = '" . $construct . "'
						LIMIT 1;";
						
				$res = $db->Query( $sql );
				if ( $res->Results() ) {
					$construct = $res->FetchArray();
				}
				else {
					$construst = array();
				}
			}
			$this->mId = isset( $construct[ 'uni_id' ] ) ? $this->mId = $construct[ 'uni_id' ] : 0;
			$this->mName = isset( $construct[ 'uni_name' ] ) ? $this->mName = $construct[ 'uni_name' ] : "";
			$this->mTypeId = isset( $construct[ 'uni_typeid' ] ) ? $this->mTypeId = $construct[ 'uni_typeid' ] : false;
			$this->mPlaceId = isset( $construct[ 'uni_placeid' ] ) ? $this->mPlaceId = $construct[ 'uni_placeid' ] : 0;
			$this->mDelId = isset( $construct[ 'uni_delid' ] ) ? $this->mDelId = $construct[ 'uni_delid' ] : false;
			
		}
	}