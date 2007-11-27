<?php

    /* TODO: use new search */

	class Search_Comments extends Search {
		public function SetFilter( $key, $value ) {
			// 0 -> equal, 1 -> LIKE
			static $keymap = array(
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
