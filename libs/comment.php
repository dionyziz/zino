<?php

	global $libs;
	
	$libs->Load( 'article' );
	$libs->Load( 'image/image' );
	$libs->Load( 'search' );
    $libs->Load( 'poll' );
	

    function Comment_UserIsSpambot( $text ) {
        return false;

        global $db;
        global $comments;

        $sql = "SELECT
                    *
                FROM
                    `$comments`
                WHERE
                    `comment_created` > ( NOW() - INTERVAL 15 SECOND ) AND
                    `comment_userip` = '" . UserIp() . "'
                ;";

        $res = $db->Query( $sql );

        echo "alert( \"" . str_replace( "\n", "", $sql ) . "\" );";
        
        if ( $res->Results() ) {
            die( "go away" );
            // email dio
            $subject = "WARNING! Comment spambot detected!";
            $message = "Text submitted: $text\n\n SpamBot Ip: " . UserIp();

            mail( 'dionyziz@gmail.com', $subject, $message );

            return true;
        }

        return false;
    }

	function Comment_FormatSearchMulti( &$comments, $searchterm ) {
		$texts = array();
		foreach ( $comments as $comment ) {
			$texts[ $comment->Id() ] = $comment->TextRaw();
		}
			
		$formattedTexts = mformatcommentsearches( $texts, $searchterm );
		
		foreach ( $comments as $comment ) {
			$comment->SetSearchText( $formattedTexts[ $comment->Id() ] );
		}
		
		return true;
	}

	class Comment {
		private $mId;
		private $mSubmitDate;
		private $mSubmitHost;
		private $mComment;
		private $mCommentRaw;
		private $mParentCommentId;
		private $mDate;
		private $mCreateYear, $mCreateMonth, $mCreateDay;
		private $mCreateHour, $mCreateMinute, $mCreateSecond;
		private $mDelId;
		private $mSinceDate;
		private $mStars;
		private $mVotes;
		private $mTypeId;
		private $mPage;
		private $mPageId;
		private $mUser;
		private $mHasChildren;
		private $mSearchText;
		
		public function IsDeleted() {
			return $this->mDelId > 0;
		}
		public function Article() {
			if ( !$this->mArticle ) {
				$this->mArticle = New Article( $this->mPageId );
			}
			return $this->mArticle;
		}
		public function Page() {
			if ( $this->mPage !== false ) {
				return $this->mPage;
			}
			else {
				switch ( $this->mTypeId ) {
					case 0:
						$this->mPage = New Article( $this->PageId() );
						break;
					case 1:
						$this->mPage = New User( $this->PageId() );
						break;
					case 2:
						$this->mPage = New Image( $this->PageId() );
						break;
                    case 3:
                        $this->mPage = New Poll( $this->PageId() );
                        break;
				}
				return $this->mPage;
			}
		}
		public function SetPage( $page ) {
			$this->mPage = $page;
		}
		public function PageId() {
			return $this->mPageId;
		}
		public function Stars() {
			return $this->mStars;
		}
		public function Votes() {
			return $this->mVotes;
		}
		public function Text() {
			return $this->mComment;
		}
		public function TextRaw() {
			return $this->mCommentRaw;
		}
		public function SearchText() {
			return $this->mSearchText;
		}
		public function SetSearchText( $text ) {
			$this->mSearchText = $text;
		}
		public function Date() {
			return $this->mDate;
		}
		public function Since() {
			return $this->mSinceDate;
		}
		public function SQLDate() {
			return $this->mSubmitDate;
		}
		public function User() {
			if( !$this->mUser ) {
                if ( $this->mUserId == 0 ) {
                    $this->mUser = New User( array() );
                }
                else {
                    $this->mUser = New User( $this->mUserId );
                }
			}
			return $this->mUser;
		}
		public function UserId() {
			return $this->mUserId;
		}
		public function Id() {
			return $this->mId;
		}
		public function Ip()  {
			return $this->mSubmitHost;
		}
		public function ParentId() {
			return $this->mParentCommentId;
		}
		public function TypeId() {
			return $this->mTypeId;
		}
		public function PageTitle() {
			if( $this->TypeId() == 0 ) {
				return $this->Page()->Title();
			}
			else {
				return $this->Page()->Username();
			}
		}
		public function Move( $newstoryid ) {
			global $user;
			
			if ( $user->IsSysOp() ) {
				$this->Update( $this->TextRaw(), $this->User()->Id(), $newstoryid );
				foreach ( $this->ChildComments( true ) as $childcomment ) {
					$childcomment->Update( $childcomment->TextRaw(), $childcomment->User()->Id(), $newstoryid );
				}
			}
			else {
				die( "Unauthorized attempt to move comment." );
			}
		}
		public function Reauthor( $newuserid ) {
			return $this->Update( $this->TextRaw(), $newuserid );
		}
		public function Update( $text, $userid = false, $storyid = false ) {
			global $db;
			global $comments;
			global $user;
            
			if ( $userid == false ) {
				$userid = $this->UserId();
			}
			if ( $storyid == false ) {
				$storyid = $this->PageId();
			}
			if ( $this->IsDeleted() ) {
				return 2;
			}
			
    		if ( $user->CanModifyStories() || ( $user->Exists() && $this->User()->Id() == $user->Id() && daysDistance( $this->SQLDate() ) < 1 ) ) {
    			if ( !$this->Exists() ) {
    				die( 'Trying to edit a non-existing comment' );
    			}
    			else if ( $this->IsDeleted() ) {
    				die( 'Trying to edit a deleted comment!' );
    			}
            }
			else {
				die( 'get outta here' );
			}
            
            $id = $this->Id();
			$textraw = myescape( $text );
			$formatted = mformatcomments( array( $text ) );
			$text = myescape( $formatted[ 0 ] );
			$userid = myescape( $userid );
			$storyid = myescape( $storyid );
			$typeid = $this->TypeId();
			
			$sql = "UPDATE `$comments` SET `comment_text` = '$text', `comment_textraw` = '$textraw', `comment_userid` = '$userid', `comment_storyid` = '$storyid' WHERE `comment_id` = '$id' AND `comment_delid` = '0';";
			
			$change = $db->Query( $sql );
			
			return $this->PageId();
		}
		public function UndoDelete() {
			global $db;
			global $comments;
			global $user;
			
			if ( !$this->IsDeleted() ) {
				return 2;
			}
			$parent = new comment( $this->ParentId() );
			if ( $parent->IsDeleted() ) {
				return 3;
			}			
			if( $user->CanModifyCategories() == false ) {
				if ( $this->User()->Id() != $user->Id() ) {
					return 4;
				}
				if ( daysDistance($this->SQLDate() ) >= 1 ) {
					return 5;
				}
			}
			
			$id = $this->Id();
			$sql = "UPDATE `$comments` SET `comment_delid` = '0' WHERE `comment_id` = '$id' AND `comment_delid` = '1' LIMIT 1;";
			$change = $db->Query( $sql );
			
			if ( $change->Impact() ) {
				$this->Page()->CommentAdded();
                $user->AddContrib();
				return 1;
			}
			else {
				return 6;
			}			
		}
		public function Kill() {
			global $db;
			global $comments;
			global $user;
			
			if ( $this->IsDeleted() ) {
				return 2;
			}
			if ( $this->HasChildren() ) {
				return 3;
			}
			
			if( $user->CanModifyCategories() == false ) {
				if ( $this->User()->Id() != $user->Id() ) {
					return 4;
				}
				if ( daysDistance($this->SQLDate() ) >= 1 ) {
					return 5;
				}
			}
			
			$id = $this->Id();
			$sql = "UPDATE `$comments` SET `comment_delid` = '1' WHERE `comment_id` = '$id' AND `comment_delid` = '0' LIMIT 1;";
			$change = $db->Query( $sql );
			
			if ( $change->Impact() ) {
				$this->Page()->CommentKilled();
                $user->RemoveContrib();
				return 1;
			}
			else {
				return 6;
			}
		}
		public function HasChildren() {
			global $db;
			global $comments;
			
			if ( isset( $this->mHasChildren ) && !empty( $this->mHasChildren ) ) {
				return $this->mHasChildren;
			}
			else {
				$sql = "SELECT `comment_id` FROM `$comments` WHERE `comment_parentid` = '" . $this->Id() . "' AND `comment_delid` = '0' LIMIT 1;";
				$res = $db->Query( $sql );
				
				if ( $res->Results() ) {
					$this->mHasChildren = true;
					return 1;
				}
				else {
					$this->mHasChildren = false;
					return 0;
				}
			}
		}
		public function ChildComments( $want_children ) {
			global $db;
			global $comments;
			
			$sql = "SELECT * FROM `$comments` WHERE `comment_parentid` = '" . $this->Id() . "' AND `comment_delid` = '0';";
			$res = $db->Query( $sql );
			
			$commentsfoo = array();
			while ( $sqlcomment = $res->FetchArray() ) {
				$comment = New Comment( $sqlcomment );
				$commentsfoo[] = $comment;
				if( $want_children ) {
					$childcomments = $comment->ChildComments( true );
					foreach( $childcomments as $childcomment ) {
						$commentsfoo[] = $childcomment;
					}
				}
			}
			
			return $commentsfoo;
		}
        public function Exists() {
            return $this->mId > 0;
        }
		private function Construct( $id ) {
			global $db;
			global $comments;
			
			$sql = "SELECT * FROM `$comments` WHERE `comment_id` = '$id' LIMIT 1;";
			$res = $db->Query( $sql );
			
			if ( !$res->Results() ) {
				return false;
			}
			
			return $res->FetchArray();
		}
		public function Comment( $construct ) {
			if ( is_array( $construct ) ) {
				$fetched_array = $construct;
			}
			else {
				$fetched_array = $this->Construct( $construct );
				if ( $fetched_array === false ) {
					return;
				}
			}
			$this->mId 				= isset( $fetched_array[ "comment_id" ] ) ? $fetched_array[ "comment_id" ] : 0;
			$this->mUserId 			= isset( $fetched_array[ "comment_userid" ] ) ? $fetched_array[ "comment_userid" ] : 0;
			$this->mSubmitDate		= isset( $fetched_array[ "comment_created" ] ) ? $fetched_array[ "comment_created" ] : '0000-00-00 00:00:00';
			$this->mSubmitHost		= isset( $fetched_array[ "comment_userip" ] ) ? $fetched_array[ "comment_userip" ] : '0.0.0.0';
			$this->mComment 		= isset( $fetched_array[ "comment_text" ] ) ? $fetched_array[ "comment_text" ] : '';
			$this->mCommentRaw		= isset( $fetched_array[ "comment_textraw" ] ) ? $fetched_array[ "comment_textraw" ] : '';
			$this->mPageId			= isset( $fetched_array[ "comment_storyid" ] ) ? ( integer )$fetched_array[ "comment_storyid" ] : 0 ;
			$this->mParentCommentId	= isset( $fetched_array[ "comment_parentid" ] ) ? $fetched_array[ "comment_parentid" ] : 0 ;
			$this->mDelId			= isset( $fetched_array[ "comment_delid" ] ) ? $fetched_array[ "comment_delid" ] : 0 ;
			$this->mUser			= isset( $fetched_array[ "user_id" ] ) ? New User( $fetched_array ) : false;
			// $this->mArticle			= isset( $fetched_array[ "article_id" ] ) ? New Article( $fetched_array ) : false;
			$this->mPage			= isset( $fetched_array[ "page" ] ) ? $fetched_array( "page" ) : false;
			
			$this->mSearchText		= "";
			
			$realstars = isset( $fetched_array[ "comment_stars" ] ) ? intval( $fetched_array[ "comment_stars" ] ) : 0;
			$realvotes = isset( $fetched_array[ "comment_votes" ] ) ? intval( $fetched_array[ "comment_votes" ] ) : 0;
			$this->mVotes = $realvotes;
			$this->mTypeId 			= isset( $fetched_array[ "comment_typeid" ] ) ? $fetched_array[ "comment_typeid" ] : 0;
			
			if( $realstars != 0 ) {
				$this->mStars = round( $realstars / $realvotes );
			}
			else {
				$this->mStars = 0;
			}
			
			if ( $this->mSubmitDate ) {
				ParseDate( $this->mSubmitDate , 
							$this->mCreateYear , $this->mCreateMonth , $this->mCreateDay ,
							$this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond );
				
				$dateTimestamp = gmmktime( $this->mCreateHour , $this->mCreateMinute , $this->mCreateSecond ,
										   $this->mCreateMonth , $this->mCreateDay , $this->mCreateYear );
				
				$this->mDate = MakeDate( $this->mSubmitDate );
				$this->mSinceDate = dateDiff( $this->mSubmitDate , NowDate() );
			}
		}
	}

	
	function MakeComment( $text, $parent, $compage, $type ) {
		global $comments;
		global $user;
		global $db;
		global $mc;
		global $libs;
		global $notify;
		
		w_assert( is_numeric( $compage ) );
		w_assert( !$user->IsAnonymous() );
		
		$libs->Load( 'chat' );
		$libs->Load( 'article' );
		$libs->Load( 'notify' );
		
		if ( $user->IsBanned() || $user->IsAnonymous() ) {
			header( "Location: index.php" );
		}

        if ( Comment_UserIsSpambot( $text ) ) {
            die( "Get out." );
            return;
        }

		
		switch ( $type ) {
			case 0: // article
				$article = New Article( $compage );
				if ( !$article->Exists() ) {
					die( 'Invalid article' );
				}
				break;
			case 1: // profile
				$theuser = New User( $compage );
				if ( !$theuser->Exists() ) {
					die( 'Invalid profile' );
				}
                break;
            case 2: // image
                $photo = New Image( $compage );
                if ( !$photo->AlbumId() ) {
                    die( 'Photo not in album or does not exist' );
                }
                break;
            case 3: // poll
                $poll = New Poll( $compage );
                if ( !$poll->Exists() ) {
                    die( 'Invalid poll' );
                }
                break;
		}
		
		$textraw = $text;
		$formatted = mformatcomments( array( $text ) );
		$text = $formatted[ 0 ];
		
		$sqlarray = array(
			'comment_id' => '',
			'comment_userid' => $user->Id(),
			'comment_created' => NowDate(),
			'comment_userip' => UserIp(),
			'comment_text' => $text,
			'comment_textraw' => $textraw,
			'comment_storyid' => $compage,
			'comment_parentid' => $parent,
			'comment_delid' => 0,
			'comment_stars' => 0,
			'comment_votes' => 0,
			'comment_typeid' => $type
		);
		
		$change = $db->Insert( $sqlarray, $comments );
		
		$sqlarray[ "comment_id" ] = $change->InsertId();
		$comment = New Comment( $sqlarray );
		$comment->Page()->CommentAdded();
		
		$uname = ( $user->IsAnonymous() ) ? 'ανώνυμο χρήστη' : '[merlin:link ?p=user&id=' . $user->Id() . '|' . $user->Username() . ']';
		if ( $type == 0 ) {
			// CCAnnounce( "Νέο σχόλιο στο άρθρο [merlin:link ?p=story&id=" . $comment->Page()->Id() . "#comment_" . $comment->Id() . "|" . myescape( $comment->Page()->Title() ) . "] από $uname" );
		}
		else if ( $type == 1 ) {
			// CCAnnounce( "Νέο σχόλιο στο προφίλ [merlin:link ?p=user&id=" . $comment->Page()->Id() . "#comment_" . $comment->Id() . "|" . myescape( $comment->Page()->Username() ) . "] από $uname" );
		}
        $mc->delete( 'latestcomments' );
		$user->AddContrib();
		$newcommentid = $change->InsertId();
		if ( $parent != 0 ) {
			$userclass = New Comment( $parent );
			$touser = $userclass->UserId();
			Notify_Create( $user->Id() , $touser , $newcommentid , $type );
            Notify_CommentRead( $user->Id(), $parent, $type );
		}

		return $newcommentid;
	}
	
	class Search_Comments extends Search {
		public function SetFilter( $key, $value ) {
			// 0 -> equal, 1 -> LIKE
			static $keymap = array(
				'body' => array( '`comment_textraw`', 1 ),
				'user' => array( '`user_id`', 0 ),
				'delid' => array( '`comment_delid`', 0 ),
				'typeid' => array( '`comment_typeid`', 0 ),
				'page' => array( '`comment_storyid`', 0 )
			);

			w_assert( isset( $keymap[ $key ] ) );
			
			$this->mFilters[] = array( $keymap[ $key ][ 0 ] , $keymap[ $key ][ 1 ] , $value );
		}
		private function SetQueryFields() {
			$this->mFields = array(
				'`comment_id`'          => 'comment_id', 
				'`comment_created`'   	=> 'comment_created',
				'`comment_parentid`' 	=> 'comment_parentid',
				'`comment_text`' 		=> 'comment_text',
				'`comment_textraw`' 	=> 'comment_textraw',
				'`comment_userip`'		=> 'comment_userip',
				'`comment_storyid`'		=> 'comment_storyid',
				'`user_id`'           	=> 'user_id',
				'`user_name`'         	=> 'user_name',
				'`user_rights`'       	=> 'user_rights',
				'`user_lastprofedit`' 	=> 'user_lastprofedit',
				'`user_icon`'			=> 'user_icon',
                '`user_signature`'      => 'user_signature',
                '`image_id`'            => 'image_id', /* user icon */
                '`image_userid`'        => 'image_userid'
			);
		}
		public function SetNegativeRequirement( $key ) {
			static $keymap = array(
				'comment_created' 	=> '`comment_created`',
				'comment_parentid' 	=> '`comment_parentid`',
				'comment_text' 		=> '`comment_text`',
				'comment_textraw' 	=> '`comment_textraw`',
				'comment_userip' 	=> '`comment_userip`',
				'comment_storyid'	=> '`comment_storyid`',
				'user_name' 		=> '`user_name`',
				'user_rights' 		=> '`user_rights`',
				'user_lastprofedit' => '`user_lastprofedit`',
				'user_icon' 		=> '`user_icon`'
			);
			
			w_assert( isset( $keymap[ $key ] ) );
			unset( $this->mFields[ $keymap[ $key ] ] );
		}
		public function SetRequirement( $key ) {
			// field_alias => array( '`field`', 'table_alias', 'table' )
			if ( $key == "page" ) {
				$this->mPageDataNeeded = true;
			}
			static $keymap = array(
				'comment_typeid' => array( '`comment_typeid`', 'comments' ),
				'article_id' => array( '`article_id`', 'articles' ),
				'revision_title' => array( '`revision_title`', 'revisions' )
			);
			
			w_assert( isset( $keymap[ $key ] ) );
			if ( !isset( $this->mFields[ $keymap[ $key ][ 0 ] ] ) ) {
				$this->mFields[ $keymap[ $key ][ 0 ] ] = $key;
			}
			
			$this->AddTable( $keymap[ $key ][ 1 ] );
		}
		private function AddTable( $table ) {
			global $articles;
			global $revisions;
		
			if ( isset( $this->mTables[ $table ] ) ) {
				return;
			}
			
			switch( $table ) {
				case 'articles':
					$this->mTables[ $table ] = array( 'name' => $articles, 'jointype' => 'INNER JOIN', 'on' => '`article_id` = `comment_storyid`' );
					break;
				case 'revisions':
					$this->mTables[ $table ] = array( 'name' => $revisions, 'jointype' => 'INNER JOIN', 'on' => '( `revision_id` = `article_headrevision` AND `revision_articleid` = `article_id` )' );
					$this->SetRequirement( 'article_id' );
					break;
				default:
					w_assert( false );
					break;
			}
		}
		public function SetSortMethod( $field , $order ) {
			static $fieldsmap = array(	
				'date' 		=> '`comment_id`'
			);
			
			w_assert( isset( $fieldsmap[ $field ] ) );
			$this->mSortField = $fieldsmap[ $field ];
			$this->SetSortOrder( $order );
		}
		public function GetParented( $reverse = false ) {
			$comments = $this->Get();
			
			$parented = array();
            if ( !is_array( $comments ) ) {
                return $parented;
            }

			foreach( $comments as $comment ) {
				if ( !isset( $parented[ $comment->ParentId() ] ) || empty( $parented[ $comment->ParentId() ] ) ) {
					$parented[ $comment->ParentId() ] = array( $comment );
				}
				else {
					if ( $reverse ) {
						array_push( $parented[ $comment->ParentId() ], $comment );
					}
					else {
						array_unshift( $parented[ $comment->ParentId() ], $comment );
					}
				}
			}
			
			return $parented;
		}
		private function GetPageData( $rows ) {
			global $articles;
			global $users;
			global $images;
			global $db;
			
			$articlesids = array();
			$profilesids = array();
			$imagesids = array();
			
			while ( $row = array_shift( $rows ) ) {
				switch ( $row[ 'comment_typeid' ] ) {
					case 0:
						$articlesids[ $row[ 'comment_storyid' ] ] = true;
						break;
					case 1:
						$profilesids[ $row[ 'comment_storyid' ] ] = true;
						break;
					case 2:
						$imagesids[ $row[ 'comment_storyid' ] ] = true;
						break;
				}
			}
			
			$articlesdata = array();
			$profilesdata = array();
			$imagesdata = array();
			
			if ( count( $articlesids ) ) {
				$sql = "SELECT * FROM `$articles` WHERE `article_id` IN (" . implode( ", ", array_keys( $articlesids ) ) . ");";
				$res = $db->Query( $sql );
				foreach ( $res as $row ) {
					$articlesdata[ $row[ 'article_id' ] ] = new Article( $row );
				}
			}
			if ( count( $profilesids ) ) {
				$sql = "SELECT * FROM `$users` WHERE `user_id` IN (" . implode( ", ", array_keys( $profilesids ) ) . ");";
				$res = $db->Query( $sql );
				foreach ( $res as $row ) {
					$profilesdata[ $row[ 'user_id' ] ] = new User( $row );
				}
			}
			if ( count( $imagesids ) ) {
				$sql = "SELECT * FROM `$images` WHERE `image_id` IN (" . implode( ", ", array_keys( $imagesids ) ) . ");";
				$res = $db->Query( $sql );
				foreach ( $res as $row ) {
					$imagesdata[ $row[ 'image_id' ] ] = new Image( $row );
		        }
			}
            
            return array( 
                'articlesdata'  => $articlesdata, 
                'profilesdata'  => $profilesdata, 
                'imagesdata'    => $imagesdata 
            );
		}

		protected function Instantiate( $res ) {
            $rows = array();
            while ( $row = $res->FetchArray() ) {
                $rows[] = $row;
            }

            if ( $this->mPageDataNeeded ) {
    			$pagedata = $this->GetPageData( $rows );
                
    			foreach ( $rows as $row ) {
	    			switch ( $row[ 'comment_typeid' ] ) {
		    			case 0:
			    			$row[ 'page' ] = $pagedata[ 'articlesdata' ][ $row[ 'comment_storyid' ] ];
				    		break;
    					case 1:
	    					$row[ 'page' ] = $pagedata[ 'profilesdata' ][ $row[ 'comment_storyid' ] ];
		    				break;
			    		case 2:
				    		$row[ 'page' ] = $pagedata[ 'imagesdata' ][ $row[ 'comment_storyid' ] ];
					    	break;
	    			}
		    	}
            }

            $comments = array(); 
            foreach ( $rows as $row ) {
			    $comments[] = new Comment( $row );
            }
			
			return $comments;
		}
		public function Search_Comments() { // constructor
			global $comments;
			global $users;
            global $images;
			global $articles;
			global $revisions;
			
			$this->mRelations = array();
			$this->mIndex = 'comments';
			$this->mTables = array(
				'comments' => array( 'name' => $comments ),
				'users' => array( 'name' => $users , 'jointype' => 'LEFT JOIN' , 'on' => '`user_id` = `comment_userid`' ),
                'images' => array( 'name' => $images , 'jointype' => 'LEFT JOIN' , 'on' => '`image_id` = `user_icon`' )
			);
			$this->mPageDataNeeded = false;
			
			$this->SetQueryFields();
			$this->Search(); // parent constructor
		}
	}
	
	class Search_Comments_Latest extends Search_Comments {
		public function Search_Comments_Latest() {
			$this->Search_Comments(); // parent constructor
			$this->SetSortMethod( 'date', 'DESC' );
			// $this->SetRequirement( 'revision_title' );
			$this->SetRequirement( 'comment_typeid' );
			$this->SetNegativeRequirement( 'comment_parentid' );
			$this->SetNegativeRequirement( 'comment_text' );
			$this->SetNegativeRequirement( 'comment_textraw' );
			$this->SetNegativeRequirement( 'comment_userip' );
			$this->SetLimit( 10 );
			$this->SetFilter( 'delid', 0 );
		}
		protected function GetCached() {
			global $mc;
			
			$ret = ''; // $mc->get( 'latestcomments' );
			if ( is_array( $ret ) ) {
				return $ret;
			}
			return false;
		}
		protected function SaveCache( $ret ) {
			global $mc;
			
			$mc->add( 'latestcomments' , $ret );
		}
	}
	
	
?>
