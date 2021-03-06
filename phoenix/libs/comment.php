<?php
    /*
        Developer: abresas, petros
    */
    global $libs;

    define( 'COMMENT_PAGE_LIMIT', 50 );
	define( 'COMMENT_MITOSIS_MIN', 30 );

    function Comment_RegenerateMemcache( $entity ) {
        global $mc;
        global $water;
        
        $water->Profile( "Memcache generation" );
		
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

    function Comment_LoadLibraryByType( $typeid ) {
        global $libs;

        switch ( $typeid ) {
            case TYPE_USERPROFILE:
                $libs->Load( 'user/profile' );
                break;
            case TYPE_POLL:
                $libs->Load( 'poll/poll' );
                break;
            case TYPE_JOURNAL:
                $libs->Load( 'journal/journal' );
                break;
            case TYPE_SCHOOL:
                $libs->Load( 'school/school' );
                break;
            case TYPE_IMAGE:
                $libs->Load( 'image/image' );
                break;
        }
    }

    class CommentCollection extends Collection {
        public function PreloadUserAvatars() {
            $avatarids = array();
            foreach ( $this as $comment ) {
                $avatarids[] = $comment->User->Avatarid;
            }
            $finder = New ImageFinder();
            $avatars = $finder->FindByIds( $avatarids );
            $avatarsById = array();
            foreach ( $avatars as $avatar ) {
                $avatarsById[ $avatar->Id ] = $avatar;
            }
            foreach ( $this as $i => $comment ) {
                if ( !isset( $avatarsById[ $comment->User->Avatarid ] ) ) {
                    continue;
                }
                $comment->User->CopyRelationFrom( 'Avatar', $avatarsById[ $comment->User->Avatarid ] );
                $this[ $i ] = $comment;
            }
        }
        public function PreloadBulk() {
            $bulkids = array();
            foreach ( $this as $comment ) {
                $bulkids[] = $comment->Bulkid;
            }
            $bulks = Bulk::FindById( $bulkids );
            foreach ( $this as $i => $comment ) {
                $comment->Text = $bulks[ $comment->Bulkid ];
                $this[ $i ] = $comment;
            }
        }
        public function PreloadItems() {
            $itemidsByType = array();
            foreach ( $this as $comment ) {
                $itemidsByType[ $comment->Typeid ][] = $comment->Itemid;
            }

            $finder = New CommentFinder();

            $itemsByType = array();
            global $water;
            foreach ( $itemidsByType as $type => $itemids ) {
                $itemids = $itemidsByType[ $type ];
                $water->Trace( 'Find items of type ' . $type );
                $items = $finder->FindItemsByType( $type, $itemids );
                foreach ( $items as $item ) {
                    $itemsByType[ $type ][ $item->Id ] = $item;
                }
            }

            global $water;

            foreach ( $this as $i => $comment ) {
                if ( !isset( $itemsByType[ $comment->Typeid][ $comment->Itemid ] ) ) {
                    $water->Trace( 'Comment preload items miss ' . $comment->Typeid . ' ' . $comment->Itemid );
                    continue;
                }
                $comment->CopyRelationFrom( 'Item', $itemsByType[ $comment->Typeid ][ $comment->Itemid ] );
                $this[ $i ] = $comment;
            }
        }
        public function ToArrayById() {
            $data = array();
            foreach ( $this as $comment ) {
                $data[ $comment->Id ] = $comment;
            }
            return $data;
        }
    }

    class CommentFinder extends Finder {
        protected $mModel = 'Comment';
        protected $mCollectionClass = 'CommentCollection';

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
    

            return array( count( $paged ), $comments );
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

            return array( count( $paged ), $cur_page + 1, $comments ); 
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
            $libs->Load( 'bulk' );
            
            $query = $this->mDb->Prepare( "
                SELECT
                    *
                FROM
                    :comments 
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
            $items = array();
            while ( $row = $res->FetchArray() ) {
                $comment = New Comment( $row );
                $items[] = $comment;
            }
    
            $collection = New CommentCollection( $items, count( $items ) );
            $collection->PreloadRelation( 'User' );
            $collection->PreloadUserAvatars();
            $collection->PreloadBulk();
            $collection->PreloadItems();

            $comments = $collection->ToArrayById();
            krsort( $comments );

            return $comments;
        }
        public function FindItemsByType( $type, $itemids ) {
            Comment_LoadLibraryByType( $type );

            $class = Type_GetClass( $type );
            $obj = New $class();
            $table = $obj->GetDbTable()->Alias;
            $primaryKeyFields = $obj->GetPrimaryKeyFields();
            $field = $primaryKeyFields[ 0 ];

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
            
            $query->Bind( 'itemids', $itemids );

            $res = $query->Execute();
            $items = array();
            while ( $row = $res->FetchArray() ) {
                // die( "type: " . $type . " class: " . $class );
                $item = New $class( $row );
                if ( $type == TYPE_USERPROFILE ) {
                    $item->CopyAvatarFrom( New Image( $row ) );
                }
                $items[ $item->Id ] = $item;
            }

            return $items;
        }
        public function FindByTypeidAndItemid( $typeid, $itemid, $offset = 0, $limit = 100000 ) {
            $query = $this->mDb->Prepare( "
                SELECT
                    *
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
            $query->Bind( 'typeid', $typeid );
            $query->Bind( 'itemid', $itemid );
            $query->Bind( 'delid', 0 );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $coms = array();
            while ( $row = $res->FetchArray() ) {
                $coms[ $row[ 'comment_id' ] ] = $row;
            }

            return $coms;
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
            global $libs;
            
            $libs->Load( 'image/image' );
            $libs->Load( 'bulk' );
            
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
				if ( isset( $comments[ $commentid ] ) ) {
					$comment = $comments[ $commentid ];
					$comment->Text = $bulks[ $comment->Bulkid ];
					$ret[] = $comment;
				}
            }

            return $ret;
        }
        public function FindParentIds ( $commentids ) { //Returns an array in the following format [ commentid ] => parentid
            if ( empty( $commentids ) ) {
                return array();
            }
            $query = $this->mDb->Prepare( "
                SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    :comments 
                WHERE
                    `comment_id` IN :commentids " );

            $query->BindTable( 'comments' );

            $query->Bind( 'commentids', $commentids );
            $res = $query->Execute();
            $res = $res->MakeArray();
            foreach ( $res as $comment ) { 
                $ret[ $comment[ "comment_id" ] ] = ( int )$comment[ "comment_parentid" ];
            }
            return $ret;
        }
        public function FindByIds( $ids ) {
            return parent::FindByIds( $ids );
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';
        private $mText = false;

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
                    if ( strlen( $value ) > 1024 * 64 ) { // more than 64k; drop it
                        $value = '';
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
                $adminaction = New AdminAction();
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
            
            $libs->Load( 'rabbit/event' );
            FireEvent( 'CommentDeleted', $this );
            
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
            
            Comment_LoadLibraryByType( $this->Typeid );
            
            w_assert( is_object( $this->Item ), 'Comment->Item not an object' );
            if ( $this->Typeid == TYPE_IMAGE ) {
                $this->Item->OnCommentCreate();
            }

            Comment_RegenerateMemcache( $this->Item );        //Old method
            //Mitosis( $this->Id, $this->Parentid, $this->Item );

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
            $libs->Load( 'bulk' );
            
            if ( $user->id != $this->userid && $this->Delid == 0 ) {
                $adminaction = New AdminAction();
                $adminaction->saveAdminAction( $user->id , UserIp() , OPERATION_EDIT, TYPE_COMMENT, $this->id );
            }
            
            Bulk::Store( $this->mText, $this->Bulkid );
        }
        public function OnBeforeCreate() {
            global $libs;
            
            $libs->Load( 'bulk' );
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
	
	function Mitosis( $commentid, $parentid, $entity ) { //Tries to divide the page when a new comment is posted.
		global $mc;                                      //If it cannot it just edits the memcache.
		
		
        $paged = $mc->get( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ) );    //Load current pagination from memcache
        if ( $paged === false ) {
            Comment_RegenerateMemcache( $entity );
            return;
        }
        
        
		if ( $parentid == 0 ) {                     //If parentid = 0 then the comment is for sure at the first page
			$page = $paged[ 0 ];
            array_unshift( $paged[ 0 ], $commentid ); //Insert new comment in current pagination
		}
		else {
            $pagenum = -1;                        //page counter
			foreach( $paged as $page ) {    //If parentid != 0 a page search must be done
                ++$pagenum;
                $key = array_search( $parentid, $page );             
                if ( $key !== false ) {                                    //If comment is found then
                    array_splice( $paged[ $pagenum ], $key + 1, 0, $commentid ); //insert new comment in current pagination and break
                    break;             //After breaking $page contains an array of commentids in the page we are interested in
                }
            }
		}
        
        $totalcomments = count( $page );
		if ( $totalcomments < COMMENT_MITOSIS_MIN * 2 ) { //This is just an optimization to avoid searching
			$mc->set( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ), $paged );
			//Not enough comments
			return;
		}
        
        $finder = New CommentFinder();
		$parentids = $finder->FindParentIds( $page );       //Retrieve parentids of commentids in the current page
		
		$i = -1;
		$threads = array();
		foreach ( $page as $commentid ) {
			if ( $parentids[ $commentid ] == 0 ) {
				++$i;
				$threads[ $i ] = 1;
			}
			else {
				++$threads[ $i ];
			}
		}
		
		$CurrentComments = 0;
		$n = count( $threads );
		$mindiaf = $totalcomments / 2; // infinity
		$index = false;
		for ( $i = 0; $i < $n; ++$i ) {
			$CurrentComments += $threads[ $i ];
			$diaf = abs( $totalcomments / 2 - $CurrentComments );
			if ( $diaf < $mindiaf ) {
				$mindiaf = $diaf;
				$index = $i;
				$mincurrentcomments = $CurrentComments;
			}
			else {
				break;
			}
		}
		
		if ( $mincurrentcomments < COMMENT_MITOSIS_MIN || $totalcomments - $mincurrentcomments < COMMENT_MITOSIS_MIN ) {
			$mc->set( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ), $paged );
			//Division below standards
			return;
		}
		
		
		$firsthalf = array();
		$secondhalf = array();
		for ( $i = 0; $i < $mincurrentcomments; ++$i ) {
			$firsthalf[] = $page[ $i ];
		}
		for ( $i = $mincurrentcomments + 1; $i <= $totalcomments; ++$i ) {
			$secondhalf[] = $page[ $i ];
		}
		
		array_splice( $paged, $pagenum, 1, array(
			$pagenum => $firsthalf,
			$pagenum + 1 => $secondhalf,
		) );
		
        $mc->delete( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ) );
        $mc->set( 'comtree_' . $entity->Id . '_' . Type_FromObject( $entity ), $paged );        
	}

?>
