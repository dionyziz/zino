<?php

	function Places_List() {
		global $places;
		global $db;
		
		$sql = "SELECT 
					`place_name`,`place_id`
				FROM
					`$places`
				WHERE
					`place_delid` = '0'
				ORDER BY
					`place_name` ASC";
		
		$res = $db->Query( $sql );
		
		$ret = array();
		while( $row = $res->FetchArray() ) {
			$ret[] = New Place( $row );
		}
		
		return $ret;
	}
	
	final class Place extends Satori {
		protected $mName;
		protected $mX;
		protected $mY;
		protected $mUpdateUserId;
		protected $mUpdateDate;
		protected $mUpdateIp;
		protected $mDelId;
		
        // no privcheck after this point
		public function Delete() {
			global $user;
            global $users;
			global $db;
			
            $this->DelId = 1;
			$change = $this->Save();
            
			if ( $change->Impact() ) {
				// Prepared query
				$db->Prepare("
					UPDATE 
                        `$users` 
                    SET 
                        `user_place` = :UserPlaceZero
                    WHERE 
                        `user_place` = :UserPlace
					;
				");
				
				// Assign query values
				$db->Bind( 'UserPlaceZero', 0 );
				$db->Bind( 'UserPlace', $this->Id );
				
				// Execute query
				$db->Execute();
			}
            return $change;
		}
		public function Place( $construct = false ) {
			global $db;
			global $places;
			global $user;
            
            $this->mDb = $db;
            $this->mDbTable = $places;
            $this->SetFields( array(
                'place_id'           => 'Id',
                'place_name'         => 'Name',
                'place_x'            => 'X',
                'place_y'            => 'Y',
                'place_updateuserid' => 'UpdateUserId',
                'place_updatedate'   => 'UpdateDate',
                'place_updateip'     => 'UpdateIp',
                'place_delid'        => 'DelId'
            ) );
            // defaults
            $this->UpdateUserId = $user->Id();
            $this->UpdateDate   = NowDate();
            $this->UpdateIp     = UserIp();
            $this->DelId        = 0;
            $this->mX           = 0;
            $this->mY           = 0;
            
            $this->Satori( $construct );
		}
	}
?>
