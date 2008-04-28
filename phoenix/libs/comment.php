<?php

    /*
        Developer: abresas
    */

    define( 'COMMENT_JOURNAL', 0 );
    define( 'COMMENT_PROFILE', 1 );
    define( 'COMMENT_IMAGE',   2 );
    define( 'COMMENT_POLL',    3 );
    
    function Comment_MakeTree( $comments, $reverse = false ) {
        $parented = array();
        if ( !is_array( $comments ) ) {
            return $parented;
        }

        foreach( $comments as $comment ) {
            if ( $reverse ) {
                array_push( $parented[ $comment->Parentid ], $comment );
            }
            else {
                $parented[ $comment->Parentid ][] = $comment;
            }
         }
        
        return $parented;
    }

    function Comment_UserIsSpamBot() {
        $finder = New CommentFinder();
        if ( $finder->UserIsSpamBot() ) {
            // email dio
            $subject = "WARNING! Comment spambot detected!";
            $message = "Text submitted: " . $this->Text . "\n\n SpamBot Ip: " . UserIp();

            mail( 'dionyziz@gmail.com', $subject, $message );

            return true;
        }

        return false;
    }

    class CommentFinder extends Finder {
        protected $mModel = 'Comment';

        public function CommentHasChildren( $comment ) {
            $query = $this->mDb->Prepare( "
                SELECT 
                    COUNT( * ) AS childcount
                FROM 
                    :comments
                WHERE
                    `comment_parentid` = :CommentId AND
                    `comment_delid` = '0'
                LIMIT 1;" 
            );

            $query->Bind( 'CommentId', $comment->Id );
            $query->BindTable( 'comments' );

            $row = $query->Execute()->FetchRow();
            if ( $row[ "childcount" ] > 0 ) {
                return true;
            }
            else {
                return false;
            }
        }
        public function UserIsSpamBot( $ip = false ) {
            if ( $ip === false ) {
                $ip = UserIp();
            }

            $query = $this->mDb->Prepare( "
                SELECT
                    COUNT( * ) AS comcount
                FROM
                    :comments
                WHERE
                    `comment_created` > ( NOW() - INTERVAL 15 SECOND ) AND
                    `comment_userip` = :UserIp
                ;
            ");
            
            $query->BindTable( 'comments' );
            $query->Bind( 'UserIp', $ip );
            
            // Execute query
            $row = $query->Execute()->FetchRow();

            if ( $row[ "comcount" ] > 0 ) {
                return true;
            }

            return false;
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';

        public function GetText() {
            return $this->Bulk->Text;
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function Delete( $theuser ) {
            $finder = New CommentFinder();
            if ( $finder->CommentHasChildren() || !$this->IsEditableBy( $theuser ) || $this->IsDeleted() ) {
                return false;
            }

            $this->Delid = 1;
            $this->Save();

            $this->User->RemoveContrib();
            $this->Item->OnCommentDelete();
        }
        public function UndoDelete( $user ) {
            if ( !$this->IsDeleted() || $this->Parent->IsDeleted() ) {
                return false;
            }

            $this->Delid = 0;
            if ( $this->Save() ) {
				$this->Item()->OnCommentCreate();
                $this->User->AddContrib();
                return true;
            }

            return false;
        }
        public function OnCreate() {
            $mc->delete( 'latestcomments' );

            $this->Item->OnCommentCreate();
            $theuser->AddContrib();

            /* EVENTS!
            if ( $this->Parent->Exists() ) {
                $libs->Load( 'notify' );

                $notify = New Notify();
                $notify->FromUserId   = $this->User->Id();
                $notify->ToUserId     = $this->Parent->User->Id();
                $notify->ItemId       = $this->Id;
                $notify->TypeId       = $this->TypeId;
                if ( !$notify->Save() ) {
                    return false;
                }
                
                // Notify_CommentRead( $theuser, $this->Parent, $this->TypeId );
            }
            */

            $event = New Event();
            $event->Typeid = EVENT_COMMENT_CREATED;
            $event->Itemid = $this->Id;
            $event->Created = $this->Created;
            $event->Userid = $this->Userid;
            $event->Save();
        }
        public function Save( $theuser = false ) {
            global $mc;
            global $user;

            if ( $theuser === false ) {
                $theuser = $user;
            }
            if ( $this->Exists() && ( !$this->IsEditableBy( $theuser ) || Comment_UserIsSpamBot() ) ) {
                return false;
            }
            $existed = $this->Exists();
            if ( !parent::Save() ) {
                die( "parent failed" );
                return false;
            }
            else if ( !$existed ) {
                $this->Item->OnCommentCreate();
                $mc->delete( 'latestcomments' );
                $theuser->AddContrib();
                if ( $this->Parent->Exists() ) {
                    $libs->Load( 'notify' );

                    $notify = New Notify();
                    $notify->FromUserId   = $this->User->Id();
                    $notify->ToUserId     = $this->Parent->User->Id();
                    $notify->ItemId       = $this->Id;
                    $notify->TypeId       = $this->TypeId;
                    if ( !$notify->Save() ) {
                        return false;
                    }
                    
                    Notify_CommentRead( $theuser, $this->Parent, $this->TypeId );
                }
            }
            return true;
        }
        public function Relations() {
            switch ( $this->Typeid ) {
                case 'COMMENT_JOURNAL':
                    $class = 'Journal';
                    break;
                case 'COMMENT_PROFILE':
                    $class = 'UserProfile';
                    break;
                case 'COMMENT_IMAGE':
                    $class = 'Image';
                    break;
                case 'COMMENT_POLL':
                    $class = 'Poll';
                    break;
            }

            $this->Item = $this->HasOne( $class, 'Itemid' );
            $this->Parent = $this->HasOne( 'Comment', 'Parentid' );
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
        public function LoadDefaults() {
            $this->Created = NowDate();
            $this->Userip = UserIp();
        }
        public function AfterConstruct() {
            if ( $this->Exists() ) {
    			$this->Since = dateDiff( $this->Created, NowDate() );
            }
        }
    }
    

	
?>
