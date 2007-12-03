<?php

    function Shoutbox_Count() {
        global $db;
        global $shoutbox;
        
		$sql = "SELECT 
					COUNT( * ) 
				AS 
					`newscount`
				FROM 
					`$shoutbox`
				WHERE 
					`shout_delid` = '0';";
		$res = $db->Query( $sql );
		$num = $res->FetchArray( $res );
		$num = $num[ "newscount" ];
		
		return $num;
    }

    function Shoutbox_Latest( $offset , $length = 20 ) {
        global $db;
        global $user;
        global $shoutbox;
        global $users;
        global $images;
        global $water;
        
		//$water->Trace( 'offset: '.$offset );
        //$water->assert( is_int( $offset ) && is_int( $length ) && $offset > 0 && $length > 0 );
        
		$more = "";
        if ( !$user->CanModifyCategories() ) {
            // TODO: this makes it unmemcacheable; 
            // let's ommit this? or let's always include this and filter later? --dionyziz.
            $more = "OR `shout_delid`='2'"; 
        }
		//$length = 20;
		$offset = $offset*$length - $length;
		$water->Trace( "offset is: ".$offset." and length is ".$length );
        $sql = "SELECT 
                    * 
                FROM 
                    `$shoutbox` INNER JOIN `$users` 
                        ON `user_id` = `shout_userid`
                    LEFT JOIN `$images`
                        ON `user_icon` = `image_id`
                WHERE 
                    `shout_delid` = '0'
                    $more 
                ORDER BY 
                    `shout_id` DESC
                LIMIT $offset, $length;";
            
        $res = $db->Query( $sql );
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[] = New Shout( $row );
        }

        return $ret;
    }
	
	function Shoutbox_IncreaseNumSmallNews( $user ) {
		global $db;
		global $users;
		
		$sql = "UPDATE `$users` SET `user_numsmallnews` = `user_numsmallnews` + 1 WHERE `user_id` = '" . $user->Id() . "' LIMIT 1;";
		$change = $db->Query( $sql );
		
		return $change->Impact();
	}
	
	function Shoutbox_DecreaseNumSmallNews( $user ) {
		global $db;
		global $users;
		
		$sql = "UPDATE `$users` SET `user_numsmallnews` = `user_numsmallnews` - 1 WHERE `user_id` = '" . $user->Id() . "' LIMIT 1;";
		$change = $db->Query( $sql );
		
		return $change->Impact();
	}
    
    class Shout extends Satori {
        protected $mId;
        protected $mText;
        protected $mDate;
        protected $mUser;
        protected $mUserId;
        protected $mUserIp;
        protected $mDelId;
        
        public function User() {
            if ( $this->mUser === false ) {
                $this->mUser = New User( $this->UserId );
            }
            return $this->mUser;
        }
        public function EditableBy( $user ) {
            return $user->CanModifyCategories() || ( $user->CanModifyStories() && $this->UserId == $user->Id() );
        }
        public function Save() {
            global $user;

            if ( !$this->EditableBy( $user ) ) {
                return false;
            }

            return parent::Save();
        }
        public function LoadDefaults() {    
            $this->SQLDate = NowDate();
            $this->UserIp = UserIp();
        }
        public function UndoDelete() {
            $this->DelId = 0;
            $this->Save();

            Shoutbox_IncreaseNumSmallNews( $this->User );
        }
        public function Delete() {
            $this->DelId = 0;
            $this->Save();

			Shoutbox_DecreaseNumSmallNews( $this->User );
        }
        public function Shout( $construct = false ) {
            global $db;
            global $shoutbox;

            $this->mDb      = $db;
            $this->mDbTable = $shoutbox;

            $this->SetFields( array(
                'shout_id'      => 'Id',
                'shout_date'    => 'Date',
                'shout_text'    => 'Text',
                'shout_userid'  => 'UserId',
                'shout_userip'  => 'UserIp',
                'shout_delid'   => 'DelId'
            ) );

            $this->Satori( $construct );

            $this->User = isset( $construct[ 'user_id' ] ) ? New User( $construct ) : false;
        }
    }
    
?>
