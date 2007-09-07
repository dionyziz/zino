<?php
    
    function Notify_Create( $fromuserid , $touserid , $itemid , $typeid ) {
        global $notify;
        global $db;
        
        if ( $fromuserid == $touserid ) {
            return;
        }
        
        $fromuserid = myescape( $fromuserid );
        $touserid = myescape( $touserid );
        $itemid = myescape( $itemid );
        $typeid = myescape( $typeid );
        $date = NowDate();
        
        $sql = "INSERT INTO
                    `$notify` ( `notify_id` , `notify_created` , `notify_fromuserid` , `notify_touserid` , `notify_itemid` , `notify_typeid` , `notify_delid` )
                VALUES
                    ( '' , '$date' , '$fromuserid' , '$touserid' , '$itemid' , '$typeid' , '0' );";
        $res = $db->Query( $sql );
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

    class Notify {
        private $mId;
        private $mCreated;
        private $mFromUserid;
        private $mFromUser;
        private $mToUserid;
        private $mToUser;
        private $mItemid;
        private $mPage;
        private $mTypeid;
        private $mDelid;
        
        public function Id() {
            return $this->mId;
        }
        public function SubmitDate() {
            return $this->mCreated;
        }
        public function FromUserid() {
            return $this->mFromUserid;
        }
        public function UserFrom() {
            if ( empty( $this->mFromUser ) ) {
                $this->mFromUser = New User( $this->mFromUserid );
            }
            
            return $this->mFromUser;
        }
        public function ToUserid() {
            return $this->mToUserid;
        }
        public function UserTo() {
            if ( empty( $this->mToUser ) ) {
                $this->mToUser = New User( $this->mToUserid );
            }
            
            return $this->mToUser;
        }
        public function Itemid() {
            return $this->mItemid;
        }
        public function Typeid() {
            /*
                0: for comments on articles
                1: for comments on userprofiles
                2: for comments on images
                3: for friends
            */
            return $this->mTypeid;
        }
        public function Page() {
            if ( empty( $this->mPage ) ) {
                if ( $this->Typeid() <= 2 ) {
                    $this->mPage = New Comment( $this->Itemid() );
                }
                else {
                    $this->mPage = New User( $this->Itemid() );
                }
            }
            
            return $this->mPage;
        }
        public function Delid() {
            //delid 0 for unread, 1 for read and 2 for deleted
            return $this->mDelid;
        }
        public function Exists() {
            return $this->mDelid == 0;
        }
        public function Read() {
            global $notify;
            global $db;
            
            $sql = "UPDATE
                $notify
            SET
                `notify_delid` = '1'
            WHERE
                `notify_id` = '" . $this->mId . "'
            LIMIT 1;";
            $db->Query( $sql );
            
            $this->mDelid = 1;
        }
            
        public function Delete() {
            global $notify;
            global $db;
            
            $sql = "UPDATE
                        $notify
                    SET
                        `notify_delid` = '2'
                    WHERE
                        `notify_id` = '" . $this->mId . "'
                    LIMIT 1;";
            $db->Query( $sql );
            
            $this->mDelid = 1;
        }
        
        public function Notify( $construct ) {
            global $notify;
            global $db;
            
            if ( !is_array( $construct ) ) {
                $construct = myescape( $construct );
                $sql = "SELECT 
                            *
                        FROM 
                            `$notify`
                        WHERE 
                            `notify_id` = '$construct'
                        LIMIT 1;";
                $res = $db->Query( $sql );
                if ( !$res->Results() ) {
                    $construct = array();
                    $this->mDelid = 1;
                }
                else {
                    $construct = $res->FetchArray();
                }

            }
            $this->mId 			= $construct[ 'notify_id' ];
            $this->mCreated 	= $construct[ 'notify_created' ];
            $this->mFromUserid 	= $construct[ 'notify_fromuserid' ];
            $this->mToUserid 	= $construct[ 'notify_touserid' ];
            $this->mItemid 		= $construct[ 'notify_itemid' ];
            $this->mTypeid		= $construct[ 'notify_typeid' ];
            $this->mDelid  		= $construct[ 'notify_delid' ];
        }
    }

?>
