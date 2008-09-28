<?php

	function AllRelations() {
		global $friendrel;
		global $db;
		
		$sql = "SELECT
					`frel_id`,`frel_type`
				FROM 
					`$friendrel`
				;";
				
		$res = $db->Query( $sql );
		
		$ret = array();
		while( $row = $res->FetchArray() ) {
			$ret[] = New Relation( $row );
		}
		return $ret;
	}

	final class Relation extends Satori {
		protected $mId;
		protected $mCreatorId;
		protected $mCreator;
		protected $mCreated;
		protected $mCreateYear, $mCreateMonth, $mCreateDay, $mCreateHour, $mCreateMinute, $mCreateSecond;
		protected $mType;
		protected $mCreatorIp;
        
		
		public function Relation( $construct = false ) {
			global $db;
			global $friendrel;
			global $user;

			$this->mDb = $db;
			$this->mDbTable = $friendrel;
			$this->SetFields( array(
				'frel_id'	=> 'Id',
				'frel_type'	=> 'Type',
				'frel_created'	=> 'Created',
				'frel_creatorid'=> 'CreatorId',
				'frel_creatorip'=> 'CreatorIp'
			) );

			$this->Type			= "simple";
			$this->Created		= NowDate();
			$this->CreatorId	= $user->Id();
			$this->CreatorIp	= UserIp();

			$this->Satori( $construct );
		}
		protected function GetCreator() {
			global $user;

			if ( empty( $this->Creator ) ) {
				$this->Creator = New User( $this->CreatorId );
			}
			return $this->mCreator;
		}
		protected function SetCreator( $userman ) {
			global $user;

			$this->mCreator = $userman;
		}
	}
