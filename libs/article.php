<?php
	global $libs;
	
	$libs->Load( 'bulk' );
	$libs->Load( 'category' );
	$libs->Load( 'search' );
	
	function Article_FormatMulti( &$articles ) {	
		$texts = array();
		$emoticons = array();
		foreach ( $articles as $article ) {
			$texts[ $article->Id() ] = $article->TextRaw();
			$emoticons[ $article->Id() ] = $article->ShowEmoticons();
		}
			
		$formattedTexts = mformatstories( $texts, $emoticons );
		
		foreach ( $articles as $article ) {
			$article->SetTextFormatted( $formattedTexts[ $article->Id() ] );
		}
		
		return true;
	}
	
	function Article_FormatSmallMulti( &$articles ) {	
		$texts = array();
		
		foreach ( $articles as $article ) {
			$texts[ $article->Id() ] = $article->TextRaw();
		}
			
		$formattedTexts = mformatsmallstories( $texts );
		
		foreach ( $articles as $article ) {
			$article->SetSmallStory( $formattedTexts[ $article->Id() ] );
		}
		
		return true;
	}
	
    function Article_ById( $articleids ) {
        global $db;
        global $articles;
        global $revisions;
        global $categories;
        global $images;
        
        if ( is_array( $articleids ) ) {
            if ( !count( $articleids ) ) {
                return array();
            }
            $wasarray = true;
        }
        else {
            $articleids = array( $articleids );
            $wasarray = false;
        }
        foreach ( $articleids as $i => $articleid ) {
            $articleids[ $i ] = ( integer )$articleid;
        }
        
        $sql = "SELECT
                    `article_id`, `article_creatorid`, `article_headrevision`, `article_created`, `article_numcomments`, `article_numviews`, `revision_textid`, 
                    `revision_title`, `revision_updated`, `revision_categoryid`, `revision_iconid`, 
                    `category_id`, `category_name`, `category_icon`,
                    `$images`.`image_id`, `$images`.`image_userid`,
                    `cimages`.`image_id` AS c_image_id, `cimages`.`image_userid` AS c_image_userid
                FROM 
                    `$articles` INNER JOIN `$revisions` 
                        ON ( `article_id` = `revision_articleid` AND
                             `article_headrevision` = `revision_id` )
                    LEFT JOIN `$categories`
                        ON ( `revision_categoryid` = `category_id` )
                    LEFT JOIN `$images`
                        ON ( `revision_iconid` = `$images`.`image_id` )
                    LEFT JOIN `$images` AS cimages
                        ON ( `category_icon` = cimages.`image_id` )
                WHERE
                    `article_id` IN (" . implode(',', $articleids) . ") AND
                    `article_delid` = '0';";
        $res = $db->Query( $sql );
        
        $rows = array();
        while ( $row = $res->FetchArray() ) {
            $rows[ $row[ 'article_id' ] ] = New Article( $row );
        }
        
        if ( $wasarray ) {
            return $rows;
        }
        if ( count( $row ) ) {
            return array_shift( $row );
        }
    }
    
	function MakeArticle( $title, $text, $icon, $emoticons, $categoryid ) {
		global $db;
		global $articles;
		global $revisions;
		global $blk;
		global $user;
		
		if ( !$user->CanModifyStories() ) {
			return -1;
		}
		
		$sqlarray = array(
			'article_id' => '',
			'article_creatorid' => $user->Id(),
			'article_headrevision' => 1,
			'article_created' => NowDate(),
			'article_delid' => 0
		);
		
		$change = $db->Insert( $sqlarray, $articles );
		if ( !$change->Impact() ) {
			return -2;
		}
		
		$articleid = $change->InsertId();
		$textid = $blk->Add( $text );
		
		$creatorid = $user->Id();
		
		$sqlarray = array(
			'revision_articleid' => $articleid,
			'revision_id' => 1,
			'revision_title' => $title,
			'revision_textid' => $textid,
			'revision_updated' => NowDate(),
			'revision_creatorid' => $user->Id(),
			'revision_creatorip' => UserIp(),
			'revision_minor' => 'no',
			'revision_iconid' => $icon,
			'revision_categoryid' => $categoryid,
			'revision_showemoticons' => $emoticons
		);
		
		$change = $db->Insert( $sqlarray, $revisions );
		if ( !$change->Impact() ) {
			return -2;
		}
		
		return $articleid;
	}
	
	// I'm afraid this is too slow.. maybe it needs some optimizing
	function Article_ArticlesEditors( $keys ) {
		global $db;
		global $mc;
		global $water;
		global $revisions;
		global $users;
		
		if ( !is_array( $keys ) ) {
			$keys = array( $keys );
		}
		
        $newkeys = array();
		$mckeys = array();
		foreach ( $keys as $i => $key ) {
			$newkeys[ $key ] = true;
			$mckeys[] = "articleeditors:" . $key;
		}
		
		$mcret = $mc->get_multi( $mckeys );
		
		$water->Trace( "memcache results" , $mcret );
		
		$ret = array();
		
		foreach ( $mcret as $mckey => $mcvalue ) {
			if ( $mcvalue !== false ) {
				$realkey = substr( $mckey, strlen( 'articleeditors:' ) );
                w_assert( isset( $newkeys[ $realkey ] ) );
				unset( $newkeys[ $realkey ] );
				$ret[ $realkey ] = $mcvalue;
			}
		}
		
		if ( count( $newkeys ) ) {	
			$sql = "SELECT 
						`revision_articleid`,
						`user_id` ,
						`user_name` ,
						`user_rights` ,
						`user_lastprofedit`,
						`user_icon`
					FROM
						`$revisions` CROSS JOIN `$users`
							ON `revision_creatorid` = `user_id`
					WHERE 
						`revision_articleid` IN (" . implode( ", ", array_keys( $newkeys ) ) . ")
                        AND `revision_minor` = 'no';";
			$res = $db->Query( $sql );
			
			while ( $row = $res->FetchArray() ) {
                if ( !isset( $ret[ $row[ 'revision_articleid' ] ] ) ) {
                    $ret[ $row[ 'revision_articleid' ] ] = array();
                }
				$ret[ $row[ "revision_articleid" ] ][ $row[ 'user_id' ] ] = New User( $row );
			}
			
			foreach( $ret as $article => $editors ) {
				$mc->add( "articleeditors:" . $article, $editors );
			}
		}
        
		return $ret;
	}
	
	final class Article {
		private $mId;
		private $mCreatorId;
		private $mCreator;
		private $mHeadRevision;
		private $mCreated;
		private $mTitle;
		private $mText;
		private $mTextFormatted;
		private $mTextId;
		private $mLastUpdate;
		private $mUpdateYear, $mUpdateMonth, $mUpdateDay, $mUpdateHour, $mUpdateMinute, $mUpdateSecond;
		private $mIcon;
		private $mIconId;
		private $mShowEmoticons;
		private $mCategoryId;
		private $mCategory;
		private $mNumComments;
		private $mEditors;
		private $mPageviews;
		private $mSmallStory;
		private $mRevision;
		private $mComment;
        
		public function Id() {
			return $this->mId;
		}
		public function TextId() {
			return $this->mTextId;
		}
		public function Text() {
			global $mc;
			
			if ( $this->mTextFormatted === false && empty( $this->mText ) ) {
				$key = 'articleformatted:' . $this->Id();
				$this->mTextFormatted = $mc->get( $key );
			}
			
			if ( $this->mTextFormatted === false ) {
				$formatted = mformatstories( array( $this->TextRaw() ), $this->ShowEmoticons() );
				$this->mTextFormatted = $formatted[ 0 ];
					
				$mc->add( 'articleformatted:' . $this->Id() , $this->mTextFormatted );
			}
			
			return $this->mTextFormatted;
		}
		public function TextRaw() {
			global $blk;
			
			if ( $this->mText === false ) {
				$this->mText = $blk->Get( $this->mTextId );
			}
			return $this->mText;
		}
		public function SmallStory() {
            global $water;
            
			if ( $this->mSmallStory === false ) {
                $water->Notice( 'Inefficient call to formatting engine' );
				$this->mSmallStory = array_shift( mformatsmallstories( array( $this->TextRaw() ) ) );
			}
			return $this->mSmallStory;
		}
		public function SetText( $raw ) {
			$this->mText = $raw;
		}
		public function SetTextFormatted( $text ) {
			$this->mTextFormatted = $text;
		}
		public function SetSmallStory( $text ) {
			$this->mSmallStory = $text;
		}
		public function CategoryId() {
			return $this->mCategoryId;
		}
		public function SetCategoryId( $newcategoryid ) {
			$this->mCategoryId = $newcategoryid;
			$this->mCategory = false; // invalidate
		}
		public function Category() {
			if ( $this->mCategory === false ) {
				$this->mCategory = New Category( $this->CategoryId() );
			}
			return $this->mCategory;
		}
		public function Title() {
			return htmlspecialchars( $this->mTitle );
		}
		public function SetTitle( $newtitle ) {
			$this->mTitle = $newtitle;
		}
		public function SubmitDate() {
			return $this->mCreated;
		}
		public function SetSubmitDate( $newdate ) {
			$this->mCreated = $newdate;
		}
		public function Creator() {
			if ( empty( $this->mCreator ) ) {
				$this->mCreator = New User( $this->CreatorId() );
			}
			return $this->mCreator;
		}
		public function CreatorId() {
			return $this->mCreatorId;
		}
		public function ShowEmoticons() {
			return $this->mShowEmoticons ? 'yes' : 'no';
		}
		public function SetShowEmoticons( $newstate ) {
			w_assert( is_bool( $newstate ) );
			$this->mShowEmoticons = $newstate;
		}
		public function DelId() {
			return $this->mDelId;
		}
        public function Icon() {
            return $this->mIcon;
        }
		public function IconId() {
			return $this->mIconId;
		}
		public function SetIconId( $newiconid ) {
			if ( !is_numeric( $newiconid ) ) {
                $newiconid = 0;
            }
			$this->mIconId = $newiconid;
		}
		public function Kill() {
			global $db;
			global $articles;
			global $user;
			
			if ( !$this->CanModify( $user ) ) {
				return 2;
			}
			
			$sql = "UPDATE `$articles` SET `article_delid` = '1' WHERE `article_id` = '" . $this->Id() . "' LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function Update( $title, $text, $icon, $emoticons, $categoryid , $minor, $comment = false ) {
			global $db;
			global $revisions;
			global $articles;
			global $blk;
			global $user;
			global $water;
			global $mc;
			
			$minor = ( $minor ) ? "yes" : "no";
						
			if ( $this->Text() != $text ) {
				$textid = $blk->Add( $text );
			}
			else {
				$textid = $this->TextId();
			}
			
			$sqlarray = array(
				'revision_articleid' => $this->Id(),
				'revision_id' => $this->RevisionId() + 1,
				'revision_title' => $title,
				'revision_textid' => $textid,
				'revision_updated' => NowDate(),
				'revision_creatorid' => $user->Id(),
				'revision_creatorip' => UserIp(),
				'revision_minor' => $minor,
				'revision_iconid' => $icon,
				'revision_categoryid' => $categoryid,
				'revision_showemoticons' => $emoticons ? 'yes' : 'no',
				'revision_comment' => $comment
			);
			
			$change = $db->Insert( $sqlarray, $revisions );
			
			if ( !$change->Impact() ) {
				return -2;
			}

			++$this->mHeadRevision;
			
			$sql = "UPDATE `$articles` SET `article_headrevision` = '" . $this->RevisionId() . "' WHERE `article_id` = '" . $this->Id() . "' LIMIT 1;";
			$db->Query( $sql );
					
			$key = 'articleformatted:' . $this->Id();
			$mc->delete( $key );
			
			return $this->Id();
		}
		public function Revert() { // usage: $article = New Article( $id, $revision ); $article->Revert();
			global $db;
			global $revisions;
			global $articles;
			global $user;
			global $water;
			global $mc;
			
			$minor = "yes";
			
			if ( !ValidId( $this->mRevision ) || $this->mRevision == $this->mHeadRevision ) {
				return -3;
			}
			$sqlarray = array(
				'revision_articleid' => $this->Id(),
				'revision_id' => $this->RevisionId() + 1,
				'revision_title' => $this->Title(),
				'revision_textid' => $this->TextId(),
				'revision_updated' => NowDate(),
				'revision_creatorid' => $user->Id(),
				'revision_creatorip' => UserIp(),
				'revision_minor' => $minor,
				'revision_iconid' => $this->IconId(),
				'revision_categoryid' => $this->Category()->Id(),
				'revision_showemoticons' => $this->ShowEmoticons(),
				'revision_comment' => "Reverted to v" . $this->mRevision
			);
			
			$change = $db->Insert( $sqlarray, $revisions );
			
			if ( !$change->Impact() ) {
				return -2;
			}

			++$this->mHeadRevision;
			
			$sql = "UPDATE `$articles` SET `article_headrevision` = '" . $this->RevisionId() . "' WHERE `article_id` = '" . $this->Id() . "' LIMIT 1;";
			$db->Query( $sql );
			
			$key = 'articleformatted:' . $this->Id();
			$mc->delete( $key );
			
			return $this->Id();
		}
		public function Editors() {
			global $db;
			global $revisions;
			global $users;
			global $water;
			global $mc;
			global $images;
            
			if ( $this->mEditors === false ) {
				$key = "articleeditors:" . $this->Id();
				$ret = $mc->get( $key );
				if ( empty( $ret ) ) {
					$articleid = $this->Id();
					$sql = "SELECT 
								`user_id` ,
								`user_name` ,
								`user_rights` ,
								`user_lastprofedit`,
								`user_icon`,
                                `image_id`,
                                `image_userid`
							FROM
								`$revisions` CROSS JOIN `$users`
									ON `revision_creatorid` = `user_id`
                                LEFT JOIN `$images`
                                    ON `user_icon` = `image_id`
							WHERE 
								`revision_articleid` = '$articleid' AND 
								`revision_minor` = 'no'
							GROUP BY
								`revision_creatorid`
							;";
					$res = $db->Query( $sql );
					$ret = array();
					while ( $sqleditor = $res->FetchArray() ) {
						$ret[] = New User( $sqleditor );
					}
					$mc->add( $key, $ret );
				}
				$this->mEditors = $ret;
			}
			
			return $this->mEditors;
		}
		public function EditorAdd( $theuser ) {
			global $db;
			global $revisions;
			global $articles;
			global $users;
			global $user;
			global $mc;

			if ( !( $theuser->Exists() ) ) {
				return -3; //invalid username
			}
			
			$EditorIds = Array();
			foreach( $this->Editors() as $editor ) {
				$EditorIds[] = $editor->Id();
			}
			if ( in_array( $theuser->Id(), $EditorIds ) ) {
				return -4; //already an editor
			}
			
			$minor = "no";
			
			$sqlarray = array(
				'revision_articleid' => $this->Id(),
				'revision_id' => $this->RevisionId() + 1,
				'revision_title' => $this->Title(),
				'revision_textid' => $this->TextId(),
				'revision_updated' => NowDate(),
				'revision_creatorid' => $theuser->Id(),
				'revision_creatorip' => UserIp(),
				'revision_minor' => $minor,
				'revision_iconid' => $this->IconId(),
				'revision_categoryid' => $this->Category()->Id(),
				'revision_showemoticons' => $this->ShowEmoticons(),
				'revision_comment' => $user->Username() . " added editor: " . $theuser->Username()
			);
			
			$change = $db->Insert( $sqlarray, $revisions );
			
			if ( !$change->Impact() ) {
				return -2;
			}

			++$this->mHeadRevision;
			
			$sql = "UPDATE `$articles` SET `article_headrevision` = '" . $this->RevisionId() . "' WHERE `article_id` = '" . $this->Id() . "' LIMIT 1;";
			$db->Query( $sql );
			
			$key = 'articleformatted:' . $this->Id();
			$mc->delete( $key );
			$key = "articleeditors:" . $this->Id();
			$mc->delete( $key );
			
			return $this->Id();
		}
		public function CanModify( $journalist ) {
			global $db;
			global $revisions;
			global $water;
						
			if ( $journalist->CanModifyCategories() ) {
				return true;
			}
			if ( !$journalist->CanModifyStories() ) {
				return false;
			}
			
			$articleid = $this->Id();
			$sql = "SELECT
						*
					FROM 
						`$revisions`
					WHERE
						`revision_articleid` = '$articleid'
						AND `revision_creatorid` = '" . $journalist->Id() . "';";
			$res = $db->Query( $sql );
			
			if ( $res->Results() ) {
				return true;
			}
			return false;
		}
		public function Pageviews() {
			global $user;
			global $pageviews;
			global $db;
			
			return $this->mPageviews;
		}
		public function SetPageviews( $newpageviews ) {
			$this->mPageviews = $newpageviews;
		}
		public function AddPageview( $bywhom = 0 ) {
			global $db;
			global $pageviews;
			global $user;
			
			if ( $bywhom == 0 ) {
				$bywhom = $user->Id();
				if ( $bywhom == 0 ) {
					return;
				}
			}
			
            $sql = "UPDATE `merlin_articles` SET `article_numviews` = `article_numviews` + 1 WHERE `article_id` = '" . $this->Id() . "' LIMIT 1;";
            $change = $db->Query( $sql );
            
            if ( $change->Impact() ) {
    			++$this->mPageviews;

                return true;
            }
            
            return false;
		}
		public function NumComments() {
			return $this->mNumComments;
		}
		public function RevisionId() { //could have been called HeadRevision()
			return $this->mHeadRevision;
		}
		public function CommentAdded() {
			// What happens when a comment is made in the article
			global $articles;
			global $db;
			
			++$this->mNumComments;
			
			$sql = "UPDATE
						`$articles` 
					SET 
						`article_numcomments` = `article_numcomments` + 1 
					WHERE 
						`article_id` = '" . $this->Id() . "' 
					LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function CommentKilled() {
			// What happens when a comment is deleted in the article
			global $articles;
			global $db;
			
			--$this->mNumComments;
			
			$sql = "UPDATE
						`$articles` 
					SET 
						`article_numcomments` = `article_numcomments` - 1 
					WHERE 
						`article_id` = '" . $this->Id() . "' 
					LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		private function Construct( $construct ) {
			global $db;
			global $articles;
			global $revisions;
			global $categories;
			global $images;
            
			if ( !ValidId( $this->mRevision ) ) {
				$revisionid = '`article_headrevision`';
				$this->mRevision = $this->mHeadRevision;
			}
			else {
				$revisionid = $this->mRevision;
			}
			
			$sql = "SELECT
						`article_id`, `article_creatorid`, `article_headrevision`, `article_created`, `article_numcomments`, `article_numviews`, `revision_textid`, 

						`revision_title`, `revision_updated`, `revision_categoryid`, `revision_iconid`, `revision_showemoticons`, `revision_comment`,
						`category_id`, `category_name`, `category_icon`,
                        `$images`.`image_id`, `$images`.`image_userid`,
                        `cimages`.`image_id` AS c_image_id, `cimages`.`image_userid` AS c_image_userid
					FROM 
						`$articles` INNER JOIN `$revisions` 
							ON ( `article_id` = `revision_articleid` AND
								 `revision_id` = $revisionid )
						LEFT JOIN `$categories`
							ON ( `revision_categoryid` = `category_id` )
                        LEFT JOIN `$images`
                            ON ( `revision_iconid` = `$images`.`image_id` )
                        LEFT JOIN `$images` AS cimages
                            ON ( `category_icon` = cimages.`image_id` )
					WHERE
						`article_id` = '$construct' AND
						`article_delid` = '0'
					;";
					
			$res = $db->Query( $sql );
			if ( $res->Results() ) {
				return $res->FetchArray();
			}
            return false;
		}
		public function Exists() {
			return $this->mId > 0;
		}
		public function Article( $construct, $revisionid = false ) {
			global $blk;
            
			$this->mId = 0;
			$array = $construct;
			if ( is_int( $construct ) ) { // construct by articleid
				$this->mRevision = $revisionid;
				$array = $this->Construct( $construct );
				if( !is_array( $array ) ) {
					return false;
				}
			}
			$this->mId				= isset( $array[ "article_id" ] ) ? $array[ "article_id" ] : 0;
			$this->mHeadRevision	= isset( $array[ "article_headrevision" ] ) ? $array[ "article_headrevision" ] : 0;
			$this->mCreated			= isset( $array[ "article_created" ] ) ? $array[ "article_created" ] : "0000-00-00 00:00:00";
			$this->mTitle			= isset( $array[ "revision_title" ] ) ? $array[ "revision_title" ] : "";
			$this->mTextId			= isset( $array[ "revision_textid" ] ) ? $array[ "revision_textid" ] : 0;
			$this->mLastUpdate		= isset( $array[ "revision_updated" ] ) ? $array[ "revision_updated" ] : "0000-00-00 00:00:00";
			$this->mIconId			= isset( $array[ "revision_iconid" ] ) ? $array[ "revision_iconid" ] : 0;
            if ( isset( $array[ 'image_id' ] ) && $this->mIconId > 0 ) {
                $this->mIcon = New Image( $array );
            }
			$this->mShowEmoticons	= isset( $array[ "revision_showemoticons" ] ) ? $array[ "revision_showemoticons" ] == "yes" : true;
			$this->mCategoryId		= isset( $array[ "revision_categoryid" ] ) ? $array[ "revision_categoryid" ] : 0;
			$this->mNumComments		= isset( $array[ 'article_numcomments' ] ) ? $array[ 'article_numcomments' ] : 0;
            $this->mPageviews       = isset( $array[ 'article_numviews' ]    ) ? $array[ 'article_numviews'    ] : 0;
			$this->mText			= isset( $array[ "bulk_text" ] ) ? $array[ "bulk_text" ] : false;
			$this->mComment			= isset( $array[ 'revision_comment' ] ) ? $array[ 'revision_comment' ] : false;
            
            if ( isset( $array[ 'category_id' ] ) ) {
                $catarray = array(
                    'category_id'   => $array[ 'category_id' ],
                    'category_name' => $array[ 'category_name' ],
                    'category_icon' => $array[ 'category_icon' ],
                    'image_id'      => $array[ 'c_image_id' ],
                    'image_userid'  => $array[ 'c_image_userid' ]
                );
                $this->mCategory = New Category( $catarray );
            }
            else {
                $this->mCategory = false;
            }
            $this->mCreatorId		= isset( $array[ 'article_creatorid' ] ) ? $array[ 'article_creatorid' ] : 0;
			$this->mCreator			= isset( $array[ 'user_id' ] ) ? New User( $array ) : "";
			$this->mEditors			= isset( $array[ 'editors' ] ) ? $array[ 'editors' ] : false;
			$this->mTextFormatted 	= false;
			$this->mSmallStory		= false;
            
			ParseDate( $this->mLastUpdate ,
						$this->mUpdateYear, $this->mUpdateMonth, $this->mUpdateDay ,
						$this->mUpdateHour, $this->mUpdateMinute, $this->mUpdateSecond );
		}
	}
	
	final class Search_Articles extends Search {
		private $mBulkNeeded;
		private $mPageviewsNeeded;
		private $mEditorsNeeded;
		
		public function SetNegativeFilter( $key, $value ) {
			static $keymap = array(
				'category' => array( 'headrevision.`revision_categoryid`', 0 )
			);
			
			w_assert( isset( $keymap[ $key ] ) );
			
			$this->mNegativeFilters[] = array( $keymap[ $key ][ 0 ], $keymap[ $key ][ 1 ], $value );
		}
		public function SetFilter( $key, $value ) {
            switch ( $key ) {
                case 'content':
                case 'body': // need to join bulk
    				$this->AddTable( 'bulk' );
                    break;
                case 'title':
                case 'editor':
                case 'revision_minor':
                    $this->AddTable( 'allrevisions' );
                    break;
            }
            
			static $keymap = array(
				'body' => array( 'bulk.`bulk_text`', 1 ),
				'title' => array( 'allrevisions.`revision_title`', 1 ),
				'editor' => array( 'allrevisions.`revision_creatorid`', 0 ),
				'creator' => array( '`article_creatorid`', 0 ),
				'delid' => array( '`article_delid`', 0 ),
				'typeid' => array( '`article_typeid`', 0 ),
				'category' => array( 'headrevision.`revision_categoryid`', 0 ),
				'comment_delid' => array( '`comment_delid`', 0 ),
				'comment_typeid' => array( '`comment_typeid`', 0 ),
				'revision_minor' => array( 'allrevisions.`revision_minor`', 0 ),
				'content' => array( array( 'headrevision.`revision_title`', 'bulk.`bulk_text`' ), 1 )
			);
			
			w_assert( isset( $keymap[ $key ] ) );
			
			$this->mFilters[] = array( $keymap[ $key ][ 0 ] , $keymap[ $key ][ 1 ] , $value );
		}
		public function SetRequirement( $key ) {
			if ( $key == 'text' ) {
				$this->mBulkNeeded = true;
				return;
			}
			else if ( $key == 'pageviews' ) {
				$this->mPageviewsNeeded = true;
				return;
			}
			else if ( $key == 'editors' ) {
				$this->mEditorsNeeded = true;
				return;
			}
		
			static $keymap = array(
				'userid' => array( '`user_id`', 'user_id', 'users' ),
				'username' => array( '`user_name`', 'user_name', 'users' ),
			);
			
			w_assert( isset( $keymap[ $key ] ) );
			if ( !isset( $this->mFields[ $keymap[ $key ][ 0 ] ] ) ) {
				$this->mFields[ $keymap[ $key ][ 0 ] ] = $keymap[ $key ][ 1 ];
			}
			
			$this->AddTable( $keymap[ $key ][ 2 ] ); // if table is not in query tables, add it
		}
		private function AddTable( $table ) {
			global $comments;
			global $users;
            global $revisions;
            
			if ( isset( $this->mTables[ $table ] ) ) {
				return;
			}
		
			switch( $table ) {
				case 'users':
					$this->mTables[ $table ] = array( 'name' => $users, 'jointype' => 'INNER JOIN', 'on' => '`user_id` = `article_creatorid`' );
					break;
				case 'bulk':
					$this->mTables[ $table ] = array( 'name' => 'merlin_bulk', 'as' => 'bulk', 'jointype' => 'INNER JOIN', 'on' => '`bulk_id` = headrevision.`revision_textid`' );
					break;
                case 'allrevisions':
    				$this->mTables[ $table ] = array( 'name' => $revisions, 'jointype' => 'INNER JOIN', 'as' => 'allrevisions', 'on' => 'allrevisions.`revision_articleid` = `article_id`' );
        			$this->SetGroupByField( "article_id" );
                    break;
				default:
					w_assert( false );
					break;
			}
		}
		private function SetQueryFields() {
			$this->mFields = array(
				'`article_id`'							=> 'article_id',
				'`article_numcomments`'					=> 'article_numcomments',
                '`article_numviews`'                    => 'article_numviews',
				'headrevision.`revision_creatorid`'  	=> 'revision_creatorid',
				'`article_headrevision`'				=> 'article_headrevision',
				'`article_created`'     				=> 'article_created',
				'headrevision.`revision_title`'      	=> 'revision_title',
				'headrevision.`revision_updated`'   	=> 'revision_updated',
				'headrevision.`revision_textid`'     	=> 'revision_textid',
				'headrevision.`revision_categoryid`'	=> 'revision_categoryid',
				'headrevision.`revision_iconid`'		=> 'revision_iconid',
				'headrevision.`revision_minor`'			=> 'revision_minor',
                '`image_id`'                            => 'image_id',
                '`image_userid`'                        => 'image_userid'
				// '`bulk_text`'				=> 'bulk_text'
			);
		}
		public function SetSortMethod( $field , $order ) {
			global $comments;
			
			static $fieldsmap = array( 
				'popularity' => 'article_numcomments',
				'date' 		 => 'article_id'
			);
			
			w_assert( isset( $fieldsmap[ $field ] ) );

			$this->mSortField = $fieldsmap[ $field ];
			$this->SetSortOrder( $order );
		}
		public function Search_Articles() { // constructor
			global $articles;
			global $revisions;
			global $images;
            
			$this->mRelations = array();
			
			$this->mIndex = 'articles';
			$this->mTables = array(
				'articles' => array( 'name' => $articles ),
				'headrevision' => array( 'name' => $revisions, 'jointype' => 'INNER JOIN', 'as' => 'headrevision', 'on' => '( headrevision.`revision_articleid` = `article_id` AND headrevision.`revision_id` = `article_headrevision` )' ),
                'images' => array( 'name' => $images, 'jointype' => 'LEFT JOIN', 'on' => 'headrevision.`revision_iconid` = `image_id`' )
			);
            
			$this->SetQueryFields();
			$this->SetSortMethod( 'date', 'DESC' );
			$this->mBulkNeeded = false;
			$this->mPageviewsNeeded = false;
			$this->mEditorsNeeded = false;
			$this->Search(); // parent constructor
		}
		
		protected function Instantiate( $res ) {
			global $blk;
			global $pageviewer;
			
			$ret = array();
			if ( $this->mBulkNeeded || $this->mPageviewsNeeded || $this->mEditorsNeeded ) {
				$rows = array();
				$bulkids = array();
				$articleids = array();
				while( $row = $res->FetchArray() ) {
					$bulkids[] = $row[ 'revision_textid' ];
					$articleids[] = $row[ 'article_id' ];
					$rows[] = $row;
				}
				
				if ( $this->mBulkNeeded ) {	
					$bulkdata = $blk->Get( $bulkids );
				}
				if ( $this->mPageviewsNeeded ) {
					$pageviewdata = $pageviewer->Get( 'article', $articleids );
				}
				if ( $this->mEditorsNeeded ) {
					$editorsdata  = Article_ArticlesEditors( $articleids );
				}
				
				while( $row = array_shift( $rows ) ) {
					if ( isset( $bulkdata ) ) {
						$row[ 'bulk_text' ] = $bulkdata[ $row[ 'revision_textid' ] ];
					}
					if ( isset( $pageviewdata ) && isset( $pageviewdata[ $row[ 'article_id' ] ] ) ) {
						$row[ 'pageviews' ] = $pageviewdata[ $row[ 'article_id' ] ];
					}
					if ( isset( $editorsdata ) ) {
                        if ( !isset( $editorsdata[ $row[ 'article_id' ] ] ) ) {
                            die( "Not set!<br /><b>" . $row[ 'article_id' ] . "</b><br /><br />" . implode( ', ', array_keys( $editorsdata ) ) );
                        }
						$row[ 'editors' ] = $editorsdata[ $row[ 'article_id' ] ];
					}
					$ret[] = New Article( $row );
				}
			}	
			else {
				while ( $row = $res->FetchArray() ) {
					$ret[] = New Article( $row );
				}
			}

			return $ret;
		}
	}
	
	function Revisions_ByArticleId( $id, $offset = false ) {
		//list revisions
		global $db;
		global $articles;
		global $revisions;
		global $users;

		if ( !ValidId( $id ) ) {
			return false;
		}
		
		if ( !ValidId( $offset ) ) {
			$offset = 0;
		}

		$sql = "SELECT 
					`revision_id` , `revision_title` , `revision_updated` , `revision_minor`, `user_name`, `revision_comment`
				FROM 
					`$articles` INNER JOIN `$revisions` 
						ON ( `article_id` = `revision_articleid` )
					LEFT JOIN `$users`
						ON ( `revision_creatorid` = `user_id` )

				WHERE 
					`article_id` = '$id'
				ORDER BY `revision_id` DESC 
				LIMIT $offset, 10;";
		
			$res = $db->Query( $sql );
			if ( $res->Results() ) {
		        $rows = array();
		        while ( $row = $res->FetchArray() ) {
					$rows[] = $row;
		        }

				return $rows;
			}
            return false;
	}
?>
