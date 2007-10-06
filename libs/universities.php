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
		
		public function Uni( $construct = false ) {
			global $universities;
			global $db;
			
			$this->mDb = $db;
			$this->mDbTable = $universities;
			
			$this->SetFields( array(
				'uni_id' => 'Id',
				'uni_name' => 'Name',
				'uni_typeid' => 'TypeId',
				'uni_placeid' => 'PlaceId'
			) );
			$this->Satori( $construct );
		}
	}