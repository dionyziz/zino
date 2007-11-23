<?php

	/*
		Userspace Class
		Developer: abresas
	*/
	
	global $libs;
    
	$libs->Load( 'bulk' );
	$libs->Load( 'search' );
	
	function Userspace_FormatSearchMulti( &$userspaces, $searchterm ) {
		$texts = array();
		foreach ( $userspaces as $userspace ) {
			$texts[ $userspace->Id() ] = $userspace->TextRaw();
		}
			
		$formattedTexts = mformatcommentsearches( $texts, $searchterm );
		
		foreach ( $userspaces as $userspace ) {
			$userspace->SetSearchText( $formattedTexts[ $userspace->Id() ] );
		}
		
		return true;
	}
    
	final class Userspace {
		private $mId;
		private $mRevisionId;
		private $mTextId;
		private $mTextRaw;
		private $mTextFormatted;
		private $mSearchText;
		private $mDate;
		private $mUserId;
		private $mUser;
		
		public function Id() {
			return $this->mId;
		}
		public function TextId() {
			return $this->mTextId;
		}
		public function Text() {
			global $user;
			
			if ( $this->mTextFormatted === false ) {
				$formatted = mformatstories( array( $this->mTextRaw ), true );
				$this->mTextFormatted = $formatted[ 0 ];
			}
			return $this->mTextFormatted;
		}
		public function TextRaw() {
			return $this->mTextRaw;
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
		public function RevisionId() {
			return $this->mRevisionId;
		}
		public function UserId() {
			return $this->mUserId;
		}
		public function User() {
			return $this->mUser;
		}
		private function SetRevisionId( $revid = '' ) {
			global $db;
			global $userspaces;
			
			if ( empty( $revid ) ) {
				++$this->mRevisionId;
			}
			else {
				$this->mRevisionId = $revid;
			}
			
			$sql = "UPDATE 
						`$userspaces` 
					SET 
						`article_headrevision` = '" . $this->mRevisionId . "'
					WHERE 
						`article_id` = '" . $this->Id() . "' 
					LIMIT 1;";
			
			$change = $db->Query( $sql );
			
			return $change->Impact();
		}
		public function Update( $text ) {
			global $db;
			global $usrevisions;
			global $blk;
			
			$textid = $blk->Add( $text );
			
			$sqlarray = array(
				'revision_articleid' => $this->Id(),
				'revision_id' => $this->RevisionId() + 1,
				'revision_textid' => $textid,
				'revision_updated' => NowDate()
			);
			
			$change = $db->Insert( $sqlarray, $usrevisions );
			
			if ( $change->Impact() ) {
				return $this->SetRevisionId();
			}
		}
		public function Create( $text ) {
			global $db;
			global $userspaces;
			global $usrevisions;
			global $users;
			global $user;
			
			$sqlarray = array(
				'article_id' 			=> '',
				'article_creatorid' 	=> $this->UserId(),
				'article_headrevision' 	=> 1,
				'article_typeid' 		=> 2,
				'article_delid' 		=> 0
			);
			
			$change = $db->Insert( $sqlarray, $userspaces );
			
			if ( !$change->Impact() ) {
				return -1;
			}
			
			$spaceid = $change->InsertId();
			$sqlarray = array(
				'revision_articleid' 	=> $spaceid,
				'revision_id'			=> 1,
				'revision_textid'		=> 0,
				'revision_updated'		=> NowDate()				
			);
			
			$change = $db->Insert( $sqlarray, $usrevisions );
			
			if ( $change->Impact() ) {
				$sql = "UPDATE `$users` SET `user_blogid` = '$spaceid' WHERE `user_id` = '" . $this->UserId() . "';";
				
				return $db->Query( $sql )->Impact();
			}
		}
		
		public function Kill() {
			global $db;
			global $userspaces;
			global $usrevisions;
			
			$sql = "UPDATE `$userspaces` SET `article_delid` = '1' WHERE `article_id` = '" . $this->Id() . "'";
			$change = $db->Query( $sql );
			
			if ( $change->Impact() ) {
				$sql = "UPDATE `$users` SET `user_blogid` = '0' WHERE `user_id` = '" . $this->UserId() . "'";
				$db->Query( $sql );
				
				return true;
			}
			
			return false;
		}
		private function Construct( $user ) {
			global $db;
			global $userspaces;
			global $usrevisions;
			
			if ( !is_object( $user ) ) {
				$userid = $user;
			}
			else {
				$userid = $user->Id();
			}
			
			$sql = "SELECT 
						`article_id` AS uspace_id,
						`article_creatorid` AS uspace_userid,
						`revision_id` AS usrevision_id,
						`revision_textid` AS usrevision_textid,
						`revision_updated` AS usrevision_updated
					FROM 
						`$userspaces` INNER JOIN `$usrevisions`
							ON ( `article_id` = `revision_articleid` AND `article_headrevision` = `revision_id` )
					WHERE
						`article_creatorid` = '$userid' AND
						`article_delid` = '0' AND
						`article_typeid` = '2'
					LIMIT 1;";
			
			$res = $db->Query( $sql );
			if ( !$res->Results() ) {
				return array();
			}
			return $res->FetchArray();
		}
		public function Userspace( $construct ) {
			global $blk;
			
			if ( is_object( $construct ) ) {
				$this->mUser = $construct;
				$this->mUserId = $this->mUser->Id();
				$fetched_array = $this->Construct( $construct );
			}
			else if ( !is_array( $construct ) ) {
				$fetched_array = $this->Construct( $construct );
			}
			else {
				$fetched_array = $construct;
			}
			
			$this->mId			= isset( $fetched_array[ 'uspace_id' ] ) 			? $fetched_array[ 'uspace_id' ] 		: 0;
			$this->mRevisionId	= isset( $fetched_array[ 'usrevision_id' ] )		? $fetched_array[ 'usrevision_id' ]		: 0;
			$this->mTextId		= isset( $fetched_array[ 'usrevision_textid' ] )	? $fetched_array[ 'usrevision_textid' ] : 0;
			$this->mTextRaw		= isset( $fetched_array[ 'bulk_text' ] ) 			? $fetched_array[ 'bulk_text' ] 		: $blk->Get( $this->mTextId ); 
			$this->mTextFormatted = false;
			$this->mSearchText	= '';
			$this->mDate		= isset( $fetched_array[ 'usrevision_updated' ] ) 	? $fetched_array[ 'usrevision_updated' ]: '0000-00-00 00:00:00';
			if ( !isset( $this->mUserId ) ) {
				$this->mUserId		= is_array( $construct ) ? $fetched_array[ 'uspace_userid' ] : $construct; // keep it this way - we need to know the userid even when the space is not created (for creating it)
			
				$this->mUser	= isset( $fetched_array[ 'user_id' ] ) ? New User( $fetched_array ) : New User( $this->mUserId );
			}
		}
	}
	
	final class Search_Userspaces extends Search {
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
			if ( $key == 'content' || $key == 'body' ) { // need to join bulk
				$this->AddTable( 'bulk' );
			}
			
			static $keymap = array(
				'body' => array( 'bulk.`bulk_text`', 1 ),
				'title' => array( 'allrevisions.`revision_title`', 1 ),
				'editor' => array( 'allrevisions.`revision_creatorid`', 0 ),
				'creator' => array( '`article_creatorid`', 0 ),
				'delid' => array( '`article_delid`', 0 ),
				'typeid' => array( '`article_typeid`', 0 ),
				'category' => array( 'allrevisions.`revision_categoryid`', 0 ),
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
				'body' => array( '`bulk_text`', 'bulk_text', 'bulk' )
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
				default:
					w_assert( false );
					break;
			}
		}
		private function SetQueryFields() {
			$this->mFields = array(
				'`article_id`'							=> 'article_id',
				'`article_numcomments`'					=> 'article_numcomments',
				'headrevision.`revision_creatorid`'  	=> 'revision_creatorid',
				'`article_headrevision`'				=> 'article_headrevision',
				'`article_created`'     				=> 'article_created',
				'headrevision.`revision_title`'      	=> 'revision_title',
				'headrevision.`revision_updated`'   	=> 'revision_updated',
				'headrevision.`revision_textid`'     	=> 'revision_textid',
				'headrevision.`revision_categoryid`'	=> 'revision_categoryid',
				'headrevision.`revision_iconid`'		=> 'revision_iconid',
				'headrevision.`revision_minor`'			=> 'revision_minor'
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
		public function Search_Userspaces( ) { // constructor
			global $articles;
			global $revisions;
			
			$this->mRelations = array();
			
			$this->mIndex = 'articles';
			$this->mTables = array(
				'articles' => array( 'name' => $articles ),
				'revisions' => array( 'name' => $revisions, 'jointype' => 'INNER JOIN', 'as' => 'allrevisions', 'on' => 'allrevisions.`revision_articleid` = `article_id`' ),
				'headrevision' => array( 'name' => $revisions, 'jointype' => 'INNER JOIN', 'as' => 'headrevision', 'on' => '( headrevision.`revision_articleid` = `article_id` AND headrevision.`revision_id` = `article_headrevision` )' )
			);
			$this->SetGroupByField( "article_id" );

			$this->SetQueryFields();
			$this->SetSortMethod( 'date', 'DESC' );
			$this->mBulkNeeded = false;
			$this->mPageviewsNeeded = false;
			$this->mEditorsNeeded = false;
			$this->Search(); // parent constructor
			
			$this->SetFilter( 'typeid', 2 );
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
					if ( isset( $pageviewdata ) ) {
						$row[ 'pageviews' ] = $pageviewdata[ $row[ 'article_id' ] ];
					}
					if ( isset( $editorsdata ) ) {
						$row[ 'editors' ] = $editorsdata[ $row[ 'article_id' ] ];
					}
					$ret[] = New Userspace( $row );
				}
			}	
			else {
				while ( $row = $res->FetchArray() ) {
					$ret[] = New Userspace( $row );
				}
			}

			return $ret;
		}
	}
    
?>