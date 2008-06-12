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
			if ( $comment->Parentid == $id ) {
				++$count;
				$count += Comments_CountChildren( $comments, $comment->Id );
			}
		}
		return $count;
	}
	
	function Comments_GetImmediateChildren( $comments, $id ) {
		$children = array();
		foreach ( $comments as $comment ) {
			if ( $comment->Parentid == $id ) {
				$children[] = $comment;
			}
		}

		return $children;
	}

    function Comments_CountPages( $comments, $parents ) {
        $total_pages = 0;
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
			if ( $comment->Parentid == $id ) {
				if ( !isset( $parented[ $id ] ) || !is_array( $parented[ $id ] ) ) {
					$parented[ $id ] = array();
				}
				if ( $reverse ) {
					array_unshift( $parented[ $id ], $comment );
				}
				else {
					$parented[ $id ][] = $comment;
				}
				Comments_MakeParented( $parented, $comments, $comment->Id, $reverse );
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
            $proc = array( $parent->Id );
            $count = 1;
            while ( !empty( $proc ) ) {
                $id = array_pop( $proc );
                if ( $id == $comment->Id ) {
                    $found_comment = true;
                }
                foreach ( $comments as $c ) {
                    if ( $c->Parentid == $id ) {
                        ++$count;
                        array_push( $proc, $c->Id );
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
            Comments_MakeParented( $parented, $comments, $parent->Id, $reverse );
        }

        return array( Comments_CountPages( $comments, $parents ), $page_num + 1, $parented );
    }

	function Comments_OnPage( $comments, $page, $reverse = true ) {
        --$page; /* start from 0 */

        $comments_dump = array();
        foreach ( $comments as $comment ) {
            $comments_dump[ $comment->Id ] = $comment->Parentid;
        }

		$parents = Comments_GetImmediateChildren( $comments, 0 );
        $parents_dump = array();
        foreach ( $parents as $comment ) {
            $parents_dump[] = $comment->Id;
        }

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
                $page_children[ $parent->Id ] = Comments_CountChildren( $comments, $parent->Id );
                $parented[ 0 ][] = $parent;
                Comments_MakeParented( $parented, $comments, $parent->Id, $reverse );
            }
            $page_total += 1 + Comments_CountChildren( $comments, $parent->Id );
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
            $prototype = New Comment();
            $prototype->Delid = 0;
            return $this->FindByPrototype( $prototype, $offset, $limit, $orderby = array( 'Id', 'DESC' ) );
        }
        public function FindNear( $entity, $comment, $reverse = true, $offset = 0, $limit = 100000 ) {
            $prototype = New Comment();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;
            $prototype->Delid = 0;

            return Comments_Near( $this->FindByPrototype( $prototype, $offset, $limit ), $comment );
        }
        public function FindByPage( $entity, $page, $reverse = true, $offset = 0, $limit = 100000 ) {
            $prototype = New Comment();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = ( $prototype->Typeid == 3 )?$entity->Userid:$entity->Id; //3 stands for Userprofile
            $prototype->Delid = 0;

            return Comments_OnPage( $this->FindByPrototype( $prototype, $offset, $limit ), $page, $reverse );
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';
		private $mSince;

        public function IsEditableBy( $user ) {
            return $this->Userid = $user->Id || $user->HasPermission( PERMISSION_COMMENT_EDIT_ALL ); 
        }
        public function GetText( $length = false ) {
            $text = $this->Bulk->Text;

            if ( $length == false ) {
                return $text;
            }
            else {
                $text = preg_replace( "#<[^>]*?>#", "", $text ); // strip all tags
                return utf8_substr( $text, 0, $length );
            }
        }
        public function SetText( $value ) {
            $this->Bulk->Text = $value;
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        protected function OnBeforeDelete() {
            global $user;

            $finder = New CommentFinder();
            if ( $finder->CommentHasChildren( $this ) || !$this->IsEditableBy( $user ) || $this->IsDeleted() ) {
                return false;
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
            $libs->Load( 'notify' );

            $finder = New NotificationFinder();
            $notif = $finder->FindByComment( $this );

            if ( !is_object( $notif ) ) {
                return;
            }
            
            $notif->Delete();
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
