<?php
    function MakeShout( $text ) {
        global $shoutbox;
        global $user;
        global $db;
        
        $nowdate = NowDate();
        $ip = UserIp();
		$formatted = mformatshouts( array( $text ) );
		$realformatted = myescape( $formatted[ 0 ] );
		$text = myescape( $text );
		
        $sql = "INSERT INTO
                    `$shoutbox`
                ( `shout_id` , `shout_userid` , `shout_text` , `shout_textformatted`, `shout_created` , `shout_userip` )
                VALUES( '' , '" . $user->Id() . "' , '$text' , '$realformatted' , '$nowdate' , '$ip' );";
        
		$change = $db->Query( $sql );
		
		if ( $change->Impact() ) {
			return Shoutbox_IncreaseNumSmallNews( $user );
		}	
    }
    function CountShouts() {
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
    function LatestShouts( $offset , $length = 20 ) {
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
	
    final class Shout {
        private $mText;
        private $mDate;
        private $mUser;
        private $mUserId;
        private $mHost;
        private $mSubmitDate;
        private $mId;
        private $mDelId;
        private $mDelUserId;
        private $mDelReason;
        
        public function Id() {
            return $this->mId;
        }
        public function UserId() {
            return $this->mUserId;
        }
        public function Date() {
            return $this->mSubmitDate;
        }
        public function SQLDate() {
            return $this->mDate;
        }
        public function Text() {
            return $this->mText;
        }
		public function TextFormatted() {
			return $this->mTextFormatted;
		}
        public function User() {
            if ( $this->mUser === false ) {
                $this->mUser = New User( $this->UserId() );
            }
            return $this->mUser;
        }
        public function Update( $text ) {
            global $shoutbox;
            global $user;
            global $db;
            
            if ( !( $user->CanModifyCategories() || ( $user->CanModifyStories() && $this->UserId() == $user->Id() ) ) ) {
                return;
            }
            
            $nowdate = NowDate();
            $ip = UserIp();
            $textraw = myescape( $text );
			$formatted = mformatshouts( array( $text ) );
			$realformatted = myescape( $formatted[ 0 ] );
			$text = myescape( $formatted[ 0 ] );
			
            $id = $this->Id();
            
            $sql = "UPDATE
                        `$shoutbox`
                    SET
                        `shout_text`='$textraw',
						`shout_textformatted`='$text'
                    WHERE
                        `shout_id`='$id'
                    LIMIT 1;";
            
            $db->Query( $sql );
        }
		
		public function UndoDelete() {
			global $db;
			global $user;
			global $shoutbox;
			
			$sql = "UPDATE
						`$shoutbox`
					SET
						`shout_delid` = '0', `shout_delreason` = '', `shout_deluserid` = '0'
					WHERE
						`shout_id` = '" . $this->Id() . "'
					LIMIT 1;";
					
			$change = $db->Query( $sql );
			
			if ( $change->Impact() ) {
				Shoutbox_IncreaseNumSmallNews( $this->User() );
			}
		}
		
		public function Delete( $reason = '' ) {
			global $shoutbox;
	        global $user;
			global $db;
	    
	        $id = myescape( $this->Id() );
	        $reason = myescape( $reason );

	        $delid = 1;

	        if ($reason != '') {
	            $delid = 2;
	        }
	    
	        $sql = "UPDATE
	                    `$shoutbox`
	                SET
	                    `shout_delid`='$delid', `shout_delreason`='$reason', `shout_deluserid`='" . $user->Username() . "'
	                WHERE
	                    `shout_id`='$id'
	                LIMIT 1;";

	        $change = $db->Query( $sql );
			
			if ( $change->Impact() ) {
				Shoutbox_DecreaseNumSmallNews( $this->User() );
			}
		}
        public function DelId() {
            return $this->mDelId;
        }
        public function DelUserId() {
            return $this->mDelUserId;
        }
        public function DelReason() {
            return $this->mDelReason;
        }
        private function Construct( $id ) {
            global $shoutbox;
            global $db;
            
            $id = myescape( $id );
            $sql = "SELECT * FROM `$shoutbox` WHERE `shout_id` = '$id' LIMIT 1;";
            
            return $db->Query( $sql )->FetchArray();
        }
        public function Shout( $construct ) {
            if ( is_array( $construct ) ) {
                $fetched_array = $construct;
            }
            else {
                $fetched_array = $this->Construct( $construct );
            }
            $this->mText 	  		= isset( $fetched_array[ 'shout_text' ] 			) ? $fetched_array[ 'shout_text' ] : "";
			$this->mTextFormatted 	= isset( $fetched_array[ 'shout_textformatted' ] 	) ? $fetched_array[ 'shout_textformatted' ] : "";
            $this->mUserId 	  		=	isset( $fetched_array[ 'shout_userid' ] 		) ? $fetched_array[ 'shout_userid' ] : "";
            $this->mDate 	  		= isset( $fetched_array[ 'shout_created' ] 			) ? $fetched_array[ 'shout_created' ] : "0000-00-00 00:00:00";
            $this->mHost 	  		= isset( $fetched_array[ 'shout_userip' ] 			) ? $fetched_array[ 'shout_userip' ] : "";
            $this->mId 		  		= isset( $fetched_array[ 'shout_id' ] 				) ? $fetched_array[ 'shout_id' ] : 0;
            $this->mDelId 	  		= isset( $fetched_array[ 'shout_delid' ] 			) ? $fetched_array[ 'shout_delid' ] : "";
            $this->mDelUserId 		= isset( $fetched_array[ 'shout_deluserid' ] 		) ? $fetched_array[ 'shout_deluserid' ] : "";
            $this->mDelReason 		= isset( $fetched_array[ 'shout_delreason' ] 		) ? $fetched_array[ 'shout_delreason' ] : "";
            $this->mUserId	  		= isset( $fetched_array[ 'shout_userid' ] 			) ? $fetched_array[ 'shout_userid' ] : "";
            
            if ( isset( $fetched_array[ 'user_id' ] ) ) {
                $this->mUser = New User( $fetched_array );
            }
            else {
                $this->mUser = false;
            }
            
            $this->mSubmitDate = MakeDate( $this->mDate );
        }
    }
?>
