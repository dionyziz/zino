<?php

	class ShoutboxFinder extends Finder {
		protected $mModel = 'Shout';
				
		// We use this as it is here or global function? --d3nnn1z
		public function Count() {
			$query = $this->mDb->Prepare(
			'SELECT
				COUNT(*) AS newscount
			FROM
				:shoutbox;
			');
			$query->BindTable( 'shoutbox' );
			$res = $query->Execute();
			$row = $res->FetchArray();
			return ( int )$row[ 'newcount' ];
		}

		public function FindLatest( $offset = 0, $limit = 20 ) {
			$prototype = New Shout();
	        return $this->FindByPrototype( $prototype, $offset, $limit, $orderby = array( 'Id', 'DESC' ) );
		}
	}

	class Shout extends Satori {
		protected $mDbTableAlias = 'shoutbox';
		
		public function Relations() {
		    $this->User = $this->HasOne( 'User', 'Userid' );
		}
		
		public function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
			$this->Created = NowDate();
		}
		
		public function OnCreate() {
			global $user;
						
			++$user->Count->Shouts;
			$user->Count->Save();
		}
		
		public function OnDelete() {
			global $user;
			
			--$user->Count->Shouts;
			$user->Count->Save();
		}
		
		public function IsEditableBy( $user ) {
			return $user->HasPermission( PERMISSION_SHOUTBOX_EDIT_ALL ) || ( $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) && $this->Userid == $user->Id ); 
		}
		
		public function OnBeforeCreate() {
			global $user;
			
			if ( !$this->IsEditableBy( $user ) ) {
                return false;
            }
		}
		
		public function OnBeforeUpdate() {
			global $user;
			
			if ( !$this->IsEditableBy( $user ) ) {
                return false;
            }
			
		}
	}
	
?>
