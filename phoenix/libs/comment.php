<?php

    /*
        Developer: abresas
    */

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
				Comments_MakeParented( $parented, $comments, $comment->Id );
			}
		}
	}
    
    function Comments_Near( $comments, $comment, $reverse = true ) {
        $parents = Comments_GetImmediateChildren( $comments, 0 );
        $page_num = 0;
        $page_total = 0;
        $page_parents = array();

        $target_parentid = ( $comment->Headparentid > 0 ) ? $comment->Headparentid : $comment->Id;
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

        return array( $page_num + 1, $parented );
    }

	function Comments_OnPage( $comments, $page, $reverse = true ) {
        global $water;
        --$page; /* start from 0 */

		$parents = Comments_GetImmediateChildren( $comments, 0 );
		$page_total = 0;
		$page_num = 0;
		$parented = array();
		$parented[ 0 ] = array();
		foreach ( $parents as $parent ) {
			if ( $page_num == $page ) {
				if ( $reverse ) {
					array_unshift( $parented[ 0 ], $parent );
				}
				else {
					$parented[ 0 ][] = $parent;
				}
				Comments_MakeParented( $parented, $comments, $parent->Id, $reverse );
			}
			$page_total += 1 + Comments_CountChildren( $comments, $parent->Id );
			if ( $page_total >= COMMENT_PAGE_LIMIT ) {
				$page_total = 0;
				$page_num++;
			}
		}

		return $parented;
	}

    /*
        return parented structure of $comments
        $parented[ $pid ] contains an array of Comment instances
        where all comments in the array have parentid = $pid
    */
    function Comment_MakeTree( $comments, $reverse = true ) {
		global $water;
		$water->Trace( "comments to be parented", $comments );

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
        public function FindNear( $entity, $comment, $reverse = true, $offset = 0, $limit = 10000 ) {
            $prototype = New Comment();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;

            return Comments_Near( $this->FindByPrototype( $prototype. $offset, $limit ), $comment );
        }
        public function FindByPage( $entity, $page, $reverse = true, $offset = 0, $limit = 10000 ) {
            $prototype = New Comment();
            $prototype->Typeid = Type_FromObject( $entity );
            $prototype->Itemid = $entity->Id;

            return Comments_OnPage( $this->FindByPrototype( $prototype, $offset, $limit ), $page, $reverse );
        }
    }

    class Comment extends Satori {
        protected $mDbTableAlias = 'comments';
		private $mSince;

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
        public function OnBeforeDelete( $theuser ) {
            $finder = New CommentFinder();
            if ( $finder->CommentHasChildren() || !$this->IsEditableBy( $theuser ) || $this->IsDeleted() ) {
                return false;
            }

            $this->Delid = 1;
            $this->Save();

            $this->User->OnCommentDelete();
			if ( is_object( $this>Item ) ) {
	            $this->Item->OnCommentDelete();
			}

            return false;
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
            // global $mc;
            global $libs;

            $libs->Load( 'event' );

            // $mc->delete( 'latestcomments' );

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
        public function OnBeforeCreate() {
            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
        }
        public function Save( $theuser = false ) {
            global $user;

            if ( !is_object( $theuser ) ) {
                $theuser = $user;
            }

            // use this when done with testing
            // if ( ( $this->Exists() && !$this->IsEditableBy( $theuser ) ) || Comment_UserIsSpamBot( $this->Text ) ) {
            if ( $this->Exists() && !$this->IsEditableBy( $theuser ) ) {
                return false;
            }
            return parent::Save();
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
