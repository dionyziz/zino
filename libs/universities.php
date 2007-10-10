<?php
	/*
		Developer: Izual
		Backend for the universities task
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
		if ( isset( $condition ) ) {
			$sql = "SELECT
						*
					FROM 
						`$universities`
					WHERE " . $condition . " AND `uni_delid` = '0';";
		}
		else {
			$sql = "SELECT
						*
					FROM 	
						`$universities`
					WHERE `uni_delid` = '0'";
		}
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
		protected $mPlace;
		protected $mDelId;
		
		protected function GetPlace() {
			$this->mPlace = new Place( $this->mPlaceId );
			
			return $this->mPlace;
		}
		
		public function Delete() {
			$this->mDelId = 1;
			$this->Save();
		}
		
		public function LoadDefaults() {
			$this->mDelId = 0;
			$this->Date = NowDate();
		}
		public function Exists() {	
			return $this->mId != 0;
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