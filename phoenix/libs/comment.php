<?php

    /*
        Developer: abresas
    */

    global $libs;

    $libs->Load( 'poll/poll' );

    define( 'COMMENT_PAGE_LIMIT', 50 );
	
    function Comments_CountChildren( $comments, $id ) {
		$count = 0;
		foreach ( $comments as $comment ) {
			if ( $comment[ 'comment_parentid' ] == $id ) {
				++$count;
				$count += Comments_CountChildren( $comments, $comment[ 'comment_id' ] );
			}
		}
		return $count;
	}
	
	function Comments_GetImmediateChildren( $comments, $id ) {
		$children = array();
		foreach ( $comments as $comment ) {
			if ( $comment[ 'comment_parentid' ] == $id ) {
				$children[] = $comment;
			}
		}

		return $children;
	}

    function Comments_CountPages( $comments, $parents ) {
        $total_pages = 1;
        $page_total = 0; 
        foreach ( $parents as $parent ) {
            $page_total += 1 + Comments_CountChildren( $comments, $parent->Id );
            if ( $page_total >= COMMENT_PAGE_LIMIT ) {
                $page_total = 0;
                $total_pages++;
            }
        }

        return $total_pages;
    }

	function Comments_MakeParented( &$parented, $comments, $id, $reverse = true ) {
		foreach ( $comments as $comment ) {
			if ( $comment[ 'comment_parentid' ] == $id ) {
				if ( !isset( $parented[ $id ] ) || !is_array( $parented[ $id ] ) ) {
					$parented[ $id ] = array();
				}
				if ( $reverse ) {
					array_unshift( $parented[ $id ], $comment );
				}
				else {
					$parented[ $id ][] = $comment;
				}
				Comments_MakeParented( $parented, $comments, $comment[ 'comment_id' ], $reverse );
			}
		}
	}
    
    function Comments_Near( $comments, $comment, $reverse = true ) {
        $parents = Comments_GetImmediateChildren( $comments, 0 );
        $page_num = 0;
        $page_total = 0;
        $page_parents = array();

        $target_parentid = ( $comment->Parentid > 0 ) ? $comment->Parentid : $comment->Id;
        $found_comment = false;

        foreach ( $parents as $parent ) {
            $page_parents[] = $parent;

            /* Count children and search for $comment */
            $proc = array( $parent[ 'comment_id' ] );
            $count = 1;
            while ( !empty( $proc ) ) {
                $id = array_pop( $proc );
                if ( $id == $comment->Id ) {
                    $found_comment = true;
                }
                foreach ( $comments as $c ) {
                    if ( $c[ 'comment_parentid' ] == $id ) {
                        ++$count;
                        array_push( $proc, $c[ 'comment_id' ] );
                    }
                }
            }
            /* End of counting */

            $page_total += $count;
            if ( $page_total >= COMMENT_PAGE_LIMIT ) {
                if ( $found_comment ) {
                    break;
                }
                $page_total = 0;
                $page_parents = array();
                ++$page_num;
            }
        }

        /* create parented structure */
        $parented = array();
        $parented[ 0 ] = array();
        foreach ( $page_parents as $parent ) {
            if ( $reverse ) {
                array_unshift( $parented[ 0 ], $parent );
            }
            else {
                $parented[ 0 ][] = $parent;
            }
            Comments_MakeParented( $parented, $comments, $parent[ 'comment_id' ], $reverse );
        }

        return array( Comments_CountPages( $comments, $parents ), $page_num + 1, $parented );
    }

	function Comments_OnPage( $comments, $page, $reverse = true ) {
        global $water;

        --$page; /* start from 0 */

        $comments_dump = array();
        foreach ( $comments as $comment ) {
            $comments_dump[ $comment[ 'comment_id' ] ] = $comment[ 'comment_parentid' ];
        }

        $water->Trace( 'comments on page', $comments_dump );

		$parents = Comments_GetImmediateChildren( $comments, 0 );
        $parents_dump = array();
        foreach ( $parents as $comment ) {
            $parents_dump[] = $comment[ 'comment_id' ];
        }

        $water->Trace( 'comment parents on page', $parents_dump );

		$page_total = 0;
		$page_num = 0;
        $page_nums = array();
        $page_children = array();
		$parented = array();
		$parented[ 0 ] = array();
        if ( $reverse ) {
            $parents = array_reverse( $parents );
        }
        foreach ( $parents as $parent ) {
            if ( $page_num == $page ) {
                $page_children[ $parent[ 'comment_id' ] ] = Comments_CountChildren( $comments, $parent[ 'comment_id' ] );
                $parented[ 0 ][] = $parent;
                Comments_MakeParented( $parented, $comments, $parent[ 'comment_id' ], $reverse );
            }
            $page_total += 1 + Comments_CountChildren( $comments, $parent[ 'comment_id' ] );
            if ( $page_total >= COMMENT_PAGE_LIMIT ) {
                $page_nums[] = $page_total;
                $page_total = 0;
                $page_num++;
                if ( $page_num > $page ) {
                    break;
                }
            }
        }

        $page_nums[] = $page_total;

        $water->Trace( "comment page nums", $page_nums );

		return array( Comments_CountPages( $comments, $parents ), $parented );
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
			if ( !is_array( $parented[ $comment->Parentid ] ) ) {
				$parented[ $comment->Parentid ] = array();
			}
            if ( $reverse ) {
                array_unshift( $parented[ $comment->Parentid ], $comment );
            }
            else {
                $parented[ $comment->Parentid ][] = $comment;
            }
         }
        
        return $parented;
    }

    function Comment_UserIsSpamBot( $text, $finder = false ) { // change finder for testcase
        if ( !is_object( $finder ) ) {
            $finder = New CommentFinder();
        }
        if ( $finder->UserIsSpamBot() ) {
            // email dio
            $subject = "WARNING! Comment spambot detected!";
            $message = "Text submitted: " . $text . "\n\n SpamBot Ip: " . UserIp();

            mail( 'dionyziz@gmail.com', $subject, $message );

            return true;
        }

        return false;
    }

    class CommentFinder extends Finder {
        protected $mModel = 'Comment';

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
            $prototype = New Comment();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id; //3 stands for Userprofile

            $query = $this->mDb->Query( '
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

            $finder = New BulkFinder();
            $bulks = $finder->FindById( $bulkids );

            $ret = array();
            foreach ( $bytype as $type => $comments ) {
                $comments = $this->FindItemsByType( $type, $comments );
                foreach ( $comments as $comment ) {
                    $comment->CopyBulkFrom( $bulks[ $comment->Bulkid ] );
                    $ret[ $comment->Id ] = $comment;
                }
            }

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
                    $comment->CopyItemFrom( New $class( $row ) );
                    $ret[] = $comment;
                }
            }

            return $ret;
        }
        public function FindNear( $entity, Comment $comment, $reverse = true, $offset = 0, $limit = 100000 ) {
            w_assert( is_object( $entity ) );

            $query = $this->mDb->Prepare( "
                SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    :comments
                WHERE
                    `comment_typeid` = :typeid AND
                    `comment_itemid` = :itemid AND
                    `comment_delid` = :delid
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'comments' );
            $query->Bind( 'typeid', Type_FromObject( $entity ) );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'delid', 0 );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $comments = array();
            while ( $row = $res->FetchArray() ) {
                $comments[] = $row;
            }

            $info = Comments_Near( $comments, $comment );
            $num_pages = $info[ 0 ];
            $cur_page = $info[ 1 ];
            $parented = $info[ 2 ];

            $comments = $this->FindParentedData( $parented );
            
            return array( $num_pages, $cur_page, $ret );
        }
        public function FindByPage( $entity, $page, $reverse = true, $offset = 0, $limit = 100000 ) {
            if ( $page <= 0 ) {
                $page = 1;
            }

            $query = $this->mDb->Prepare( "
                SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    :comments
                WHERE
                    `comment_typeid` = :typeid AND
                    `comment_itemid` = :itemid AND
                    `comment_delid` = :delid
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'comments' );
            $query->Bind( 'typeid', Type_FromObject( $entity ) );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'delid', 0 );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $comments = array();
            while ( $row = $res->FetchArray() ) {
                $comments[] = $row;
            }

            $info = Comments_OnPage( $comments, $page, $reverse );
            $num_pages = $info[ 0 ];
            $parented = $info[ 1 ];

            $comments = $this->FindParentedData( $parented );

            return array( $num_pages, $comments );
        }
        public function FindParentedData( $parented ) {
            /* get comment ids from parented */
            $commentids = array();
            foreach ( $parented as $parentid => $children ) {
                foreach ( $children as $child ) {
                    $commentids[] = $child[ 'comment_id' ];
                }
            }

            /* fetch data for all comments */
            $comments = $this->FindData( $commentids );
    
            /* parentify fetched data */
            $ret = array();
            foreach ( $parented as $parentid => $children ) {
                $ret[ $parentid ] = array();
                foreach ( $children as $child ) {
                    $ret[ $parentid ][] = $comments[ $child[ 'comment_id' ] ];
                }
            }
        }
        public function FindData( $comments, $offset = 0, $limit = 100000 ) {
            if ( empty( $comments ) ) {
                return array();
            }

            $query = $this->mDb->Prepare( "
                SELECT
                    * 
                FROM
                    :comments LEFT JOIN :users 
                        ON `comment_userid` = `user_id`
                WHERE
                    `comment_id` IN :commentids
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'comments', 'users' );
            $query->Bind( 'commentids', $comments );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $comment = New Comment( $row );
                $comment->CopyUserFrom( New User( $row ) );
                $ret[ $row[ 'comment_id' ] ] = $comment;
            }

            return $ret;
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';
		private $mSince;

        public function CopyItemFrom( $value ) {
            $this->mRelations[ 'Item' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function CopyBulkFrom( $value ) {
            $this->mRelations[ 'Bulk' ]->CopyFrom( $value );
        }
        public function IsEditableBy( $user ) {
            return $this->Userid = $user->Id || $user->HasPermission( PERMISSION_COMMENT_EDIT_ALL ); 
        }
        public function GetText( $length = false ) {
            $text = $this->Bulk->Text;

            if ( $length == false ) {
                return $text;
            }
            else {
                $text = htmlspecialchars_decode( strip_tags( $text ) );
                $text = mb_substr( $text, 0, $length );
                return htmlspecialchars( $text );
            }
        }
        public function SetText( $value ) {
            $this->Bulk->Text = $value;
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        protected function OnBeforeDelete() {
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
            $libs->Load( 'event' );
            
            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );
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
            // global $mc;
            global $libs;

            $libs->Load( 'event' );

            // $mc->delete( 'latestcomments' );

            w_assert( is_object( $this->User ), 'Comment->User not an object' );
            $this->User->OnCommentCreate();

            w_assert( is_object( $this->Item ), 'Comment->Item not an object' );
            $this->Item->OnCommentCreate();

            $event = New Event();
            $event->Typeid = EVENT_COMMENT_CREATED;
            $event->Itemid = $this->Id;
            $event->Created = $this->Created;
            $event->Userid = $this->Userid;
            $event->Save();
        }
        public function OnBeforeUpdate() {
            $this->Bulk->Save();
        }
        public function OnBeforeCreate() {
            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
        }
        public function Relations() {
			if ( $this->Exists() ) {
	            $this->Item = $this->HasOne( Type_GetClass( $this->Typeid ), 'Itemid' );
			}
            $this->Parent = $this->HasOne( 'Comment', 'Parentid' );
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
        public function LoadDefaults() {
			global $user;

            $this->Created = NowDate();
            $this->Userip = UserIp();
			$this->Userid = $user->Id;
        }
        public function OnConstruct() {
            if ( $this->Exists() ) {
    			$this->mSince = dateDiff( $this->Created, NowDate() );
            }
        }
		public function GetSince() {
			return $this->mSince;
		}
    }
    

	
?>
