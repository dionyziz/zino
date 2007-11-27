<?php
    
    function Notify_Create( $fromuserid , $touserid , $itemid , $typeid ) {
        $notify = New Notify();
        $notify->FromUserId   = $fromuserid;
        $notify->ToUserId     = $touserid;
        $notify->ItemId       = $itemid;
        $notify->TypeId       = $typeid;
        $notify->Save();
    }

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
                $sql = "SELECT
                    *
                FROM
                    `$notify`
                WHERE 
                    `notify_touserid` = '$userid'
                AND
                    `notify_delid`='0'
                ORDER BY
                    `notify_created`
                DESC
                LIMIT ".$offset." , ".$length.";";

        }
        else {
                $sql = "SELECT
                    *
                FROM
                    `$notify`
                WHERE 
                    `notify_touserid` = '$userid'
                AND
                    ( `notify_delid`='1' OR `notify_delid`='0' )
                ORDER BY
                    `notify_created`
                DESC
                LIMIT ".$offset." , ".$length.";";

        }
        
        $res = $db->Query( $sql );

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

        $sql = "UPDATE
                   `$notify`
                SET
                    `notify_delid` = '1'
                WHERE
                    `notify_touserid` = '$userid' AND
                    `notify_delid` = '1' AND
                    `notify_itemid` = '$commentid' AND
                    `notify_typeid` = '$typeid'
                LIMIT 1;";

        $change = $db->Query( $sql );

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
            
            /* TypeId
                0: for comments on articles
                1: for comments on userprofiles
                2: for comments on images
                3: for friends
            */
        
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
