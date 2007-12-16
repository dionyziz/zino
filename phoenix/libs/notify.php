<?php
   
    /*
    function Notify_Create( $fromuserid , $touserid , $itemid , $typeid ) {
        $notify = New Notify();
        $notify->FromUserId   = $fromuserid;
        $notify->ToUserId     = $touserid;
        $notify->ItemId       = $itemid;
        $notify->TypeId       = $typeid;
        $notify->Save();
    }
    */

    function Notify_GetByUser( $userid , $offset , $length , $newest = false ) {
        global $notify;
        global $db;
        

        if ( !ValidId( $userid ) ) {
            return;
        }
        $userid = myescape( $userid );
        if ( $offset <= 0 ) {
            $offset = 1;
        }
        $offset = $offset * $length - $length;
        if ( $newest ) {
			// Prepared query
			$query = $db->Prepare("
				SELECT
                    *
                FROM
                    `$notify`
                WHERE 
                    `notify_touserid` = :NotifyToUserId
                AND
                    `notify_delid`= :NotifyDelId
                ORDER BY
                    `notify_created` DESC
                LIMIT 
					:Offset , :Length
				;
			");
			
			// Assign query values
			$query->Bind( 'NotifyToUserId', $userid );
			$query->Bind( 'NotifyToDelId', 0 );
			$query->Bind( 'Offset', $offset );
			$query->Bind( 'Length', $length );
        }
        else {
			// Prepared query
			$query = $db->Prepare("
				SELECT
                    *
                FROM
                    `$notify`
                WHERE 
                    `notify_touserid` = :NotifyToUserId
                AND
                    ( `notify_delid`= :One OR `notify_delid`= :Zero )
                ORDER BY
                    `notify_created` DESC
                LIMIT :Offset , :Length
				;
			");
			
			// Assign query values
			$query->Bind( 'NotifyToUserId', $userid );
			$query->Bind( 'One', 1 );
			$query->Bind( 'Zero', 0 );
			$query->Bind( 'Offset', $offset );
			$query->Bind( 'Length', $length );
        }
        // Execute query
        $res = $query->Execute();

        $ret = array();
        while( $row = $res->FetchArray() ) {
            $ret[] = New Notify( $row );
        }

        return $ret;
    }

    // call this when a user reads a comment that was read 
    // without clicking the link of the notification
    function Notify_CommentRead( $userid, $commentid, $typeid ) {
        global $notify;
        global $db;
		
		// Prepared query
		$query = $db->Prepare("
			UPDATE
	              `$notify`
            SET
                `notify_delid` = :NotifyDelId
            WHERE
                `notify_touserid`	= :NotifyToUserId AND
                `notify_delid`		= :NotifyDelId AND
                `notify_itemid`		= :NotifyItemId AND
                `notify_typeid` 	= :NotifyTypeId
            LIMIT :Limit
			;
		");
		
		// Assign query values
		$query->Bind( 'NotifyDelId', 1 );
		$query->Bind( 'NotifyToUserId', $userid );
		$query->Bind( 'NotifyItemId', $commentid );
		$query->Bind( 'NotifyTypeId', $typeid );
		$query->Bind( 'Limit', 1 );
		
		// Execute query
        $change = $query->Execute();

        return $change->Impact();
    }

    class Notify extends Satori {
        protected $mId;
        protected $mCreated;
        protected $mFromUserId;
        protected $mFromUser;
        protected $mToUserId;
        protected $mToUser;
        protected $mItemId;
        protected $mTypeId;
        protected $mDelId;
            
        // delid 0 for unread, 1 for read and 2 for deleted

        public function GetFromUser() {
            if ( $this->mFromUser === false ) {
                $this->mFromUser = New User( $this->mFromUserId );
            }
            
            return $this->mFromUser;
        }
        public function GetToUser() {
            if ( $this->mToUser === false ) {
                $this->mToUser = New User( $this->mToUserId );
            }

            return $this->mToUser;
        }
        public function GetPage() {
            if ( $this->mPage === false ) {
                if ( $this->TypeId <= 3 ) {
                    $this->mPage = New Comment( $this->ItemId );
                }
                else {
                    $this->mPage = New User( $this->ItemId );
                }
            }

            return $this->mPage;
        }
        public function IsRead() {
            return $this->DelId > 0;
        }
        public function IsDeleted() {
            return $this->DelId > 1;
        }
        public function Read() {
            $this->DelId = 1;
            $this->Save();
        }
        public function Delete() {
            $this->DelId = 2;
            $this->Save();
        }
        protected function Save() {
            if ( $xc_settings[ "readonly" ] > $user->Rights() || $this->FromUserId == $this->ToUserId ) {
                return false;
            }
            return parent::Save();
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
        public function Notify( $construct = false ) {
            global $db;
            global $notify;

            $this->mDb      = $db;
            $this->mDbTable = $notify;

            $this->SetFields( array(
                'notify_id'         => 'Id',
                'notify_created'    => 'Created',
                'notify_fromuserid' => 'FromUserId',
                'notify_touserid'   => 'ToUserId',
                'notify_itemid'     => 'ItemId',
                'notify_typeid'     => 'TypeId',
                'notify_delid'      => 'DelId'
            ) );

            $this->FromUser = false;
            $this->ToUser   = false;
        }
    }

?>
