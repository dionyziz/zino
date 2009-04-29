<?php
    /*
        Developer: abresas
    */
    global $libs;

    $libs->Load( 'poll/poll' );

    define( 'COMMENT_PAGE_LIMIT', 50 );

    function Comment_RegenerateMemcache( $entity ) {
        global $mc;
        global $water;

        $water->Profile( "Memcache generation" );
		
        //$mc->delete( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ) );

        $finder = New CommentFinder();
        $children = $finder->FindByEntity( $entity );

        $paged = array();
        $paged[ 0 ] = array();
        $cur_page = 0;
        $stack = array( 0 );
        while ( !empty( $stack ) ) {
            $parent = array_pop( $stack );
            if ( !is_array( $parent ) ) {
                $parentid = 0;
            }
            else {
                $parentid = $parent[ 'comment_id' ];

                if ( $parent[ 'comment_parentid' ] == 0 ) { // top parent found!
                    if ( count( $paged[ $cur_page ] ) >= COMMENT_PAGE_LIMIT ) {
                        ++$cur_page;
                        $paged[ $cur_page ] = array();
                    }
                }

                $paged[ $cur_page ][] = (int)$parent[ 'comment_id' ];
            }

            if ( !isset( $children[ $parentid ] ) ) {
                continue;
            }
            foreach ( $children[ $parentid ] as $comment ) {
                    $stack[] = $comment;
            }
            
            /*
            foreach ( $comments as $key => $comment ) {
                if ( $comment[ 'comment_parentid' ] == $parentid ) {
                    array_push( $stack, $comment );
                    unset( $comments[ $key ] );
                }
            }
            */
        }

        $mc->set( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ), $paged );

        $water->ProfileEnd();

        return $paged;
    }

    function Comment_GetMemcached( $entity ) {
        global $mc;

        $paged = $mc->get( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ) );
        if ( $paged === false ) {
            $paged = Comment_RegenerateMemcache( $entity );
        }

        return $paged;
    }

    class CommentFinder extends Finder {
        protected $mModel = 'Comment';

        public function FindByPage( $entity, $page ) {
            global $user;

            if ( $page <= 0 ) {
                $page = 1;
            }

            --$page; // start from 0

            /*
            if ( $user->Id == 658 ) {
                die( "paged: " . var_dump( $paged ) );
            }
            */

            $paged = Comment_GetMemcached( $entity );

            $commentids = $paged[ $page ];
            $comments = $this->FindData( $commentids );
    
            $ret = array();
            foreach ( $commentids as $key => $id ) {
                $ret[ $key ] = $comments[ $id ];
            }

            return array( count( $paged ), $ret );
        }
        public function FindNear( $entity, Comment $comment, $offset = 0, $limit = 100000 ) {
            global $mc;

            $paged = Comment_GetMemcached( $entity );
            $cur_page = -1;
			
			$id = $comment->Id;

            foreach ( $paged as $page => $commentids ) { /* slow? at least not if the comment is on the first pages */
                foreach ( $commentids as $commentid ) {
                    if ( $commentid == $id ) {
                        $cur_page = $page;
                        break;
                    }
                }
                if ( $cur_page >= 0 ) {
                    break;
                }
            }

            if ( $cur_page === -1 ) {
                return false;
            }

            $commentids = $paged[ $cur_page ];
            $comments = $this->FindData( $commentids );

            $ret = array(); //makes sure the order is preserved according to what will be displayed
            foreach ( $commentids as $key => $id ) {
                $ret[ $key ] = $comments[ $id ];
            }

            return array( count( $paged ), $cur_page + 1, $ret ); 
        }
        public function Count() {
            $query = $this->mDb->Prepare(
            'SELECT
                COUNT(*) AS count
            FROM
                :comments;
            ');
            $query->BindTable( 'comments' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            return ( integer )$row[ 'count' ];
        }
        public function DeleteByEntity( $entity ) {
            $query = $this->mDb->Prepare( '
                UPDATE
                    :comments
                SET
                    `comment_delid` = :delid
                WHERE
                    `comment_typeid` = :typeid AND
                    `comment_itemid` = :itemid
                ;' );

            $query->BindTable( 'comments' );
            $query->Bind( 'delid', 1 );
            $query->Bind( 'typeid', Type_FromObject( $entity ) );
            $query->Bind( 'itemid', $entity->Id );

            return $query->Execute()->Impact();
        }
        public function CommentHasChildren( $comment ) {
            $query = $this->mDb->Prepare(
                "SELECT 
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

            $row = $query->Execute()->FetchArray();
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
            global $libs;
            
            $libs->Load( 'image/image' );

            $query = $this->mDb->Prepare( "
                SELECT
                    *
                FROM
                    :comments 
                    LEFT JOIN :users
                        ON `comment_userid` = `user_id`
                    LEFT JOIN :images
                        ON `user_avatarid` = `image_id`
                WHERE
                    `comment_delid` = '0'
                ORDER BY
                    `comment_id` DESC
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'comments', 'users', 'images' );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $bytype = array();
            $bulkids = array();
            while ( $row = $res->FetchArray() ) {
                $comment = New Comment( $row );
                $user = New User( $row );
                $user->CopyAvatarFrom( New Image( $row ) );
                $comment->CopyUserFrom( $user );
                $bytype[ $comment->Typeid ][] = $comment;
                $bulkids[] = $comment->Bulkid;
            }

            $bulks = Bulk::FindById( $bulkids );

            $ret = array();
            foreach ( $bytype as $type => $comments ) {
                $comments = $this->FindItemsByType( $type, $comments );
                foreach ( $comments as $comment ) {
                    $comment->Text = $bulks[ $comment->Bulkid ];
                    $ret[ $comment->Id ] = $comment;
                }
            }

            krsort( $ret );

            return $ret;
        }
        public function FindItemsByType( $type, $comments ) {
            $byitemids = array();
            foreach ( $comments as $comment ) {
                $byitemids[ $comment->Itemid ][] = $comment;
            }

            $class = Type_GetClass( $type );
            $obj = New $class();
            $table = $obj->DbTable->Alias;
            $field = $obj->PrimaryKeyFields[ 0 ];

            if ( $type != TYPE_USERPROFILE ) {
                $query = $this->mDb->Prepare( "
                    SELECT
                        *
                    FROM
                        $table
                    WHERE
                        $field IN :itemids
                    ;" );
                    
                $query->BindTable( $table );
            }
            else {
                $query = $this->mDb->Prepare( "
                    SELECT
                        *
                    FROM
                        :users
                        LEFT JOIN :images ON 
                            `user_avatarid` = `image_id`
                    WHERE
                        `user_id` IN :itemids
                    ;" );

                $query->BindTable( 'users', 'images' );
            }
            
            $query->Bind( 'itemids', array_keys( $byitemids ) );

            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $comments = $byitemids[ $row[ $field ] ];
                foreach ( $comments as $comment ) {
                    // die( "type: " . $type . " class: " . $class );
                    $obj = New $class( $row );
                    if ( $type == TYPE_USERPROFILE ) {
                        $obj->CopyAvatarFrom( New Image( $row ) );
                    }
                    $comment->CopyItemFrom( $obj );
                    $ret[] = $comment;
                }
            }

            return $ret;
        }
        public function FindByEntity( $entity, $offset = 0, $limit = 100000 ) {
            $query = $this->mDb->Prepare( "
                SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    :comments
                WHERE
                    `comment_typeid` = :typeid AND
                    `comment_itemid` = :itemid AND
                    `comment_delid` = :delid
                ORDER BY
                    `comment_id` ASC
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'comments' );
            $query->Bind( 'typeid', Type_FromObject( $entity ) );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'delid', 0 );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $children = array();
            while ( $row = $res->FetchArray() ) {
                $children[ $row[ 'comment_parentid' ] ][] = $row;
            }

            return $children;
        }
        public function FindData( $commentids, $offset = 0, $limit = 100000 ) {
            if ( empty( $commentids ) ) {
                return array();
            }

            $query = $this->mDb->Prepare( "
                SELECT
                    * 
                FROM
                    :comments 
                    LEFT JOIN :users ON `comment_userid` = `user_id`
                    LEFT JOIN :images ON `user_avatarid` = `image_id`
                WHERE
                    `comment_id` IN :commentids
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'comments', 'users', 'images' );
            $query->Bind( 'commentids', $commentids );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
            $comments = array();
            $bulkids = array();
            while ( $row = $res->FetchArray() ) {
                $comment = New Comment( $row );
                $user = New User( $row );
                $user->CopyAvatarFrom( New Image( $row ) );
                $comment->CopyUserFrom( $user );
                $comments[ $comment->Id ] = $comment;
                $bulkids[] = $comment->Bulkid;
            }

            $bulks = Bulk::FindById( $bulkids );

            $ret = array();
            foreach ( $commentids as $commentid ) {
				$comment = $comments[ $commentid ];
                $comment->Text = $bulks[ $comment->Bulkid ];
                $ret[ $commentid ] = $comment;
            }
			foreach ( $ret as $commentid => $value ) {
				echo "key= $commentid and value= $value->Id \n";
			}

            return $ret;
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';
        private $mText = false;

        public function __get( $key ) {
            switch ( $key ) {
                case 'Text':
                    if ( $this->mText === false ) {
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
                    if ( strlen( $value ) > 1024 * 64 ) { // more than 64k; crop it
                        $value = substr( $value, 0, 1024 * 64 );
                    }
                    $this->mText = $value;
                    return;
                default:
                    return parent::__set( $key, $value );
            }
        }
        public function GetText( $length ) {
            global $libs;
            
            $libs->Load( 'wysiwyg' );
            
            return WYSIWYG_PresentAndSubstr( $this->Text, $length );
        }
        public function CopyItemFrom( $value ) {
            $this->mRelations[ 'Item' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function IsEditableBy( $user ) {
            return $this->Userid = $user->Id || $user->HasPermission( PERMISSION_COMMENT_EDIT_ALL ); 
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        protected function OnBeforeDelete() {
            global $user;
            global $libs;            
			
            $libs->Load( 'adminpanel/adminaction' );
            
			
            if ( $user->id != $this->userid ) {
                $adminaction = new AdminAction();
                $adminaction->saveAdminAction( $user->id , UserIp() , OPERATION_DELETE , TYPE_COMMENT, $this->id );
            }
            
            $this->Delid = 1;
            $this->Save();

            $this->User->OnCommentDelete();

            w_assert( is_object( $this->Item ), 'Comment->Item is not an object' );
            $this->Item->OnCommentDelete();

            $this->OnDelete();

            return false;
        }
        protected function OnDelete() {
            global $libs;
            global $mc;
            $libs->Load( 'event' );
            
            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            Comment_RegenerateMemcache( $this->Item );
        }
        public function UndoDelete( $user ) {
            if ( !$this->IsDeleted() || $this->Parent->IsDeleted() ) {
                return false;
            }

            $this->Delid = 0;
            if ( $this->Save() ) {
                $this->Item->OnCommentCreate();
                $this->User->AddContrib();
                return true;
            }

            return false;
        }
        public function OnCreate() {
            global $mc;
            global $libs;

            $libs->Load( 'event' );

            w_assert( is_object( $this->User ), 'Comment->User not an object' );
            $this->User->OnCommentCreate();

            w_assert( is_object( $this->Item ), 'Comment->Item not an object' );
            if ( $this->Typeid == TYPE_USERPROFILE ) {
                $this->Item->Profile->OnCommentCreate();
            }
            else {
                $this->Item->OnCommentCreate();
            }

            $event = New Event();
            $event->Typeid = EVENT_COMMENT_CREATED;
            $event->Itemid = $this->Id;
            $event->Created = $this->Created;
            $event->Userid = $this->Userid;
            $event->Save();

            Comment_RegenerateMemcache( $this->Item );

            $finder = New NotificationFinder();
            $finder->DeleteByCommentAndUser( $this->Parent, $this->User );

            Sequence_Increment( SEQUENCE_COMMENT );
            
            $libs->Load( 'rabbit/event' );
            
            FireEvent( 'CommentCreated', $this );
        }
        public function OnUpdate() {
            /* 
             * TODO: check if parentid has changed
             * if it has changed: regenerate and edit old and new parent numchildren
             * if not: do not regenerate
             */
            
            // Comment_RegenerateMemcache( $this->Item );
            Sequence_Increment( SEQUENCE_COMMENT );            
        }
        public function OnBeforeUpdate() {
            global $user;
            global $libs;
            
            $libs->Load( 'adminpanel/adminaction' );
                       
            if ( $user->id != $this->userid && $this->Delid == 0 ) {
                $adminaction = new AdminAction();
                $adminaction->saveAdminAction( $user->id , UserIp() , OPERATION_EDIT, TYPE_COMMENT, $this->id );
            }
            
            Bulk::Store( $this->mText, $this->Bulkid );
        }
        public function OnBeforeCreate() {
            if ( !in_array( $this->Typeid, array( TYPE_POLL, TYPE_IMAGE, TYPE_USERPROFILE, TYPE_JOURNAL, TYPE_SCHOOL ) ) ) {
                throw New Exception( 'Comment is not within the allowed types' );
            }
            $this->Bulkid = Bulk::Store( $this->mText );
        }
        public function Relations() {
            if ( $this->Exists() ) {
                $this->Item = $this->HasOne( Type_GetClass( $this->Typeid ), 'Itemid' );
            }
            $this->Parent = $this->HasOne( 'Comment', 'Parentid' );
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        public function LoadDefaults() {
            global $user;

            $this->Created = NowDate();
            $this->Userip = UserIp();
            $this->Userid = $user->Id;
        }
    }
	
	function Mitosis( $commentid, $parentid ) {
		$paged = $mc->get( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ) );
        if ( $paged === false ) {
            $paged = Comment_RegenerateMemcache( $entity );
			return;
        }
        $finder = New CommentFinder();
		if ( $parentid == 0 ) {
			$page = 0;
			$comments = $finder->FindData( $paged[ $page ] );
		}
		else {
			$speccomment = New Comment( $parentid );
			$info = $finder->FindNear( $entity, $speccomment );
			$page = $info[ 1 ];
			$comments = $info[ 2 ];
		}
		
		
		
		$parented = array();
		$rootcomments = array();
		foreach ( $comments as $comment ) {
			$parented[ $comment->Parentid ][] = $comment;
			if ( $comment->Parentid == 0 ) {
				$rootcomments[] = $comment->Id;
			}
		}
		/* will be gone soon
		$queue = array();
		$rootid = 0;
		$threads = array();
		while ( !empty( $queue ) || !empty( $rootcomments ) ) {
			if ( empty( $queue ) ) {
				$rootid = array_pop( $rootcomments );
				array_push( $queue, $rootid );
				$threads[ $rootid ] = 1;
			}
			$id = array_pop( $queue );
			if ( isset( $parented[ $id ] ) ) {
				$threads[ $rootid ] += count( $parented[ $id ] );
				foreach ( $parented[ $id ] as $id => $child ) {
					array_push( $queue, $child->Id );
				}
			}
		}
		
		
		$TotalComments = count( $paged[ $page ] );
		$CrrentComments = 0;
		$MinDiaf=20;
		for( $i = 0; $i < $n; $i++ ) {
			$CurrentComments += A[i];
			$diaf = abs( $TotalComments/2 - $CurrentComments );
			if( $diaf < $MinDiaf ) {
				$MinDiaf = $diaf;
				$index = $i;
			}
			else {
				break;
			}
		}*/
	}

?>
