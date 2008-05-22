<?php

    /*
        Developer: abresas
    */

    define( 'COMMENT_JOURNAL', 0 );
    define( 'COMMENT_PROFILE', 1 );
    define( 'COMMENT_IMAGE',   2 );
    define( 'COMMENT_POLL',    3 );

    define( 'COMMENT_PAGE_LIMIT', 50 );

    function Comment_CountChildren( $commments, $id ) {
        if ( empty( $comments[ $id ] ) ) {
            return 1;
        }

        $count = 1;
        foreach ( $comments[ $id ] as $child ) {
            $count += Comment_CountChildren( $comments, $child );
        }

        return $count;
    }

    /*
    returns all comments on same page with $comment
    */
    function Comments_Near( $comments, $comment, $reverse = true ) {
        /* 
        get parented structure
        */
        $comments = Comment_MakeTree( $comments, $reverse );

        $headparent_id = $comment->Headparent->Id;
        $headparent_page = 0;
        $pages = array();
        $page = 0;

        foreach ( $comments[ 0 ] as $parent ) { // for every "head parent" (orfan comment)
            if ( $parent->Id == $headparent_id ) { // if it is the head parent of the specified comment
                $headparent_page = $page; // mark the page it is in
            }
            $pages[ $page ][] = $parent->Id; // add its id to the current page
            $page_count += Comment_CountChildren( $comments, $parent->Id ); // count its children
            if ( $page_count > COMMENT_PAGE_LIMIT ) { // if the page has enough children
                $page_count = 0;
                ++$page; // use the next page from now on
            }
        }

        /* 
        return all headparents on the same page
        and their children
        */
        $ret = array();
        foreach ( $pages[ $headparent_page ] as $parent ) {
            $ret[ 0 ][] = $parent;
            $ret[ $parent ][] = $comments[ $parent ];
        }

        return $ret;
    }

    /* 
    return all comments on page $pageno
    */
    function Comments_OnPage( $comments, $pageno, $reverse = true ) {
        $comments = Comment_MakeTree( $comments, $reverse );

        $ret = array();
        $curpage = 0;
        foreach ( $comments[ 0 ] as $parent ) { // for every "head parent" (orfan comment)
            if ( $curpage == $pageno ) { // if we are on the specified page
                $ret[ 0 ][] = $parent; // add the headparent
                $ret[ $parent->Id ][] = $comments[ $parent->Id ]; // and its children to the array to be returned
            }
            $page_count += Comment_CountChildren( $comments, $parent->Id ); // count its children
            if ( $page_count > COMMENT_PAGE_LIMIT ) { // if the page has enough children
                $page_count = 0;
                ++$curpage; // use the next page from now on
            }
        }

        return $ret;
    }
    
    /*
        return parented structure of $comments
        $parented[ $pid ] contains an array of Comment instances
        where all comments in the array have parentid = $pid
    */
    function Comment_MakeTree( $comments, $reverse = true ) {
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

    function Comment_UserIsSpamBot( $finder = false ) { // change finder for testcase
        if ( $finder === false ) {
            $finder = New CommentFinder();
        }
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
            $row = $query->Execute()->FetchArray();

            if ( $row[ "comcount" ] > 0 ) {
                return true;
            }

            return false;
        }
        public function FindLatest( $offset = 0, $limit = 25 ) {
            $prototype = New Comment();
            $prototype->Delid = 0;
            return $this->FindByPrototype( $prototype, $offset, $limit, $orderby = array( 'Id', 'DESC' ) );
        }
        public function FindNear( $entity, $comment, $reverse = true ) {
            $prototype = New Comment();
            $prototype->Typeid = Comments_TypeFromEntity( $entity );
            $prototype->Pageid = $entity->Id;

            return Comments_Near( $this->FindByPrototype( $prototype ), $comment );
        }
        public function FindByPage( $entity, $page, $reverse = true ) {
            $prototype = New Comment();
            $prototype->Typeid = Comments_TypeFromEntity( $entity );
            $prototype->Pageid = $entity->Id;

            return Comments_OnPage( $this->FindByPrototype( $prototype ), $page );
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';
		private $mSince;

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

            $this->User->OnCommentDelete();
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
            global $mc;
            global $libs;

            $libs->Load( 'event' );

            $mc->delete( 'latestcomments' );

            if ( method_exists( $this->Item, 'OnCommentCreate' ) ) {
                $this->Item->OnCommentCreate();
            }

            $this->User->OnCommentCreate();

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
        public function Save() {
            global $user;

            if ( !$this->User->Exists() ) {
                throw new Exception( 'Non existing user on Comment::User' );
            }
            if ( ( $this->Exists() && !$this->IsEditableBy( $theuser ) ) || Comment_UserIsSpamBot() ) {
                return false;
            }
            return parent::Save();
        }
        public function Relations() {
            switch ( $this->Typeid ) {
                case COMMENT_JOURNAL:
                    $class = 'Journal';
                    break;
                case COMMENT_PROFILE:
                    $class = 'UserProfile';
                    break;
                case COMMENT_IMAGE:
                    $class = 'Image';
                    break;
                case COMMENT_POLL:
                    $class = 'Poll';
                    break;
                default:
                    throw new Exception( 'Invalid comment typeid: ' . $this->Typeid );
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
    			$this->mSince = dateDiff( $this->Created, NowDate() );
            }
        }
		public function GetSince() {
			return $this->mSince;
		}
    }
    

	
?>
