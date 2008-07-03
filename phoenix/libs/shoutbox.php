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
			return ( int )$row[ 'newscount' ];
		}

		public function FindLatest( $offset = 0, $limit = 20 ) {
            global $libs;
            $libs->Load( 'image/image' );

            $query = $this->mDb->Prepare( "
                SELECT
                    *
                FROM
                    :shoutbox
                    LEFT JOIN :users
                        ON `shout_userid` = `user_id`
                    LEFT JOIN :images
                        ON `user_avatarid` = `image_id`
                WHERE
                    `shout_delid` = '0'
                ORDER BY
                    `shout_id` DESC
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'shoutbox', 'users', 'images' );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $shouts = array();
            $bulkids = array();
            while ( $row = $res->FetchArray) {
                $shout = New Shout( $row );
                $user = New User( $row );
                $user->CopyAvatarFrom( New Image( $row ) );
                $shout->CopyUserFrom( $user );
                $shouts[] = $shout;
                $bulkids[] = $shout->Bulkid;
            }

            $finder = New BulkFinder();
            $bulks = $finder->FindById( $bulkids );

            $ret = array();
            while ( $shout = array_shift( $shouts ) ) {
                $shout->CopyBulkFrom( $bulks[ $shout->Bulkid ] );
                $ret[] = $shout;
            }

            return $ret;
		}
	}

	class Shout extends Satori {
		protected $mDbTableAlias = 'shoutbox';
		private $mSince;
		
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function CopyBulkFrom( $value ) {
            $this->mRelations[ 'Bulk' ]->CopyFrom( $value );
        }
		public function Relations() {
		    $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
		}
		
		public function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
			$this->Created = NowDate();
		}
		
        public function OnBeforeCreate() {
			global $user;

            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
        }

        public function SetText( $text ) {
            $this->Bulk->Text = $text;
        }

        public function GetText() {
            return $this->Bulk->Text;
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
			if(  $user->HasPermission( PERMISSION_SHOUTBOX_EDIT_ALL ) || ( $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) && $this->Userid == $user->Id ) ) {
				return true;
			}
			else {
				throw new Exception( "No permissions to edit shout" );
			}
		}
		
        public function OnConstruct() {
            if ( $this->Exists() ) {
    			$this->mSince = dateDiff( $this->Created, NowDate() );
            }
        }
		public function GetSince() {
			return $this->mSince;
		}
	}
	
?>
