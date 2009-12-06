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

        public function FindByChannel( $channelid = 0, $limit = 20 ) {
            global $libs;
            
			if ( is_int( $channelid ) ) {
				$channelids = array( $channelid );
			}
			else {
                $channelids = $channelid;
				w_assert( is_array( $channelids ) );
				w_assert( !empty( $channelids ) );
			}
			
            $libs->Load( 'image/image' );
            $libs->Load( 'bulk' );

            $shouts = array();
            $bulkids = array();
            $row = array();

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
                    AND `shout_channelid` IN :channelids
                ORDER BY
                    `shout_id` DESC
                LIMIT
                    :limit;" );
            $query->BindTable( 'shoutbox', 'users', 'images', 'chatsequences' );
            $query->Bind( 'limit', $limit );
            foreach ( $channelids as $channelid ) {
                $query->Bind( 'channelids', $channelid );
                $res = $query->Execute();
                while ( $row = $res->FetchArray() ) {
                    $rows[] = $row;
                }
            }
            
            while ( $row = array_shift( $rows ) ) {
                $shout = New Shout( $row );
                $user = New User( $row );
                $user->CopyAvatarFrom( New Image( $row ) );
                $shout->CopyUserFrom( $user );
                $shouts[] = $shout;
                $bulkids[] = $shout->Bulkid;
            }

            $bulks = Bulk::FindById( $bulkids );

            $ret = array();
            while ( $shout = array_shift( $shouts ) ) {
                $shout->Text = $bulks[ $shout->Bulkid ];
                $ret[] = $shout;
            }

            return $ret;
        }
    }

    class Shout extends Satori {
        protected $mDbTableAlias = 'shoutbox';
        private $mText = false;
        
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
        
        protected function OnBeforeCreate() {
            global $libs;
            
            $libs->Load( 'bulk' );
            $libs->Load( 'chat/channel' );

            $this->Bulkid = Bulk::Store( $this->mText );
            $this->Channelposition = Channel_SequencePosition( $this->Channelid );
        }

        protected function OnBeforeUpdate() {
            global $libs;
            
            $libs->Load( 'bulk' );
            Bulk::Store( $this->mText, $this->mBulkId );
        }

        public function __get( $key ) {
            global $libs;
            
            switch ( $key ) {
                case 'Text':
                    if ( $this->mText === false ) {
                        $libs->Load( 'bulk' );
                        $this->mText = Bulk::FindById( $this->Bulkid );
                    }
                    return $this->mText;
                default:
                    return parent::__get( $key );
            }
        }

        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Text':
                    $this->mText = $value;
                    return;
                default:
                    return parent::__set( $key, $value );
            }
        }

        public function OnCreate() {
            global $user;
            global $libs;
            
            $libs->Load( 'user/count' );
            
            ++$user->Count->Shouts;
            $user->Count->Save();

            Sequence_Increment( SEQUENCE_SHOUT );
            
            $libs->Load( 'rabbit/event' );
            
            FireEvent( 'ShoutCreated', $this );
        }
        
        public function OnDelete() {
            global $user;
            global $libs;
            
            $libs->Load( 'user/count' );
            
            --$user->Count->Shouts;
            $user->Count->Save();

            Sequence_Increment( SEQUENCE_SHOUT );
        }

        public function OnUpdate() {
            Sequence_Increment( SEQUENCE_SHOUT );
        }
        
        public function IsEditableBy( $user ) {
            if(  $user->HasPermission( PERMISSION_SHOUTBOX_EDIT_ALL ) || ( $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) && $this->Userid == $user->Id ) ) {
                return true;
            }
            else {
                throw New Exception( "No permissions to edit shout" );
            }
        }
        
    }
    
?>
