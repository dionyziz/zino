<?php

	class Search_Images extends Search {
		public function SetSortMethod( $field , $order ) {
			static $fieldsmap = array(	
				'date' 		=> '`image_id`'
			);
			
			w_assert( isset( $fieldsmap[ $field ] ) );
			$this->mSortField = $fieldsmap[ $field ];
			$this->SetSortOrder( $order );
		}
        public function SetNegativeFilter( $key, $value ) {
            static $keymap = array(
                'albumid' => array( '`image_albumid`', 0 )
            );
            
            w_assert( isset( $keymap[ $key ] ) );
            
            $this->mNegativeFilters[] = array( $keymap[ $key ][ 0 ], $keymap[ $key ][ 1 ], $value );
        }
		public function SetFilter( $key, $value ) {
			// 0 -> equal, 1 -> LIKE
			static $keymap = array(
				'user' => array( '`image_userid`', 0 ),
				'delid' => array( '`image_delid`', 0 ),
				'mime' => array( '`image_mime`', 0 ),
				'name' => array( '`image_name`', 1 ),
                'albumid' => array( '`image_albumid`', 0 )
			);

			w_assert( isset( $keymap[ $key ] ) );
			
			$this->mFilters[] = array( $keymap[ $key ][ 0 ] , $keymap[ $key ][ 1 ] , $value );
		}
		private function SetQueryFields() {
			$this->mFields = array(
				'`image_id`'			=> 'image_id',
				'`image_userid`'		=> 'image_userid',
				'`image_created`'		=> 'image_created',
				'`image_userip`'		=> 'image_userip',
				'`image_name`'			=> 'image_name',
				'`image_mime`'			=> 'image_mime',
				'`image_width`'			=> 'image_width',
				'`image_height`' 		=> 'image_height',
				'`image_size`'			=> 'image_size',
				'`image_delid`'			=> 'image_delid',
                '`image_numcomments`'   => 'image_numcomments',
                '`image_description`'   => 'image_description',
                '`image_albumid`'       => 'image_albumid',
				'`user_id`'           	=> 'user_id',
				'`user_name`'         	=> 'user_name',
				'`user_rights`'       	=> 'user_rights',
				'`user_lastprofedit`' 	=> 'user_lastprofedit',
				'`user_icon`'			=> 'user_icon',
                '`user_signature`'      => 'user_signature'
			);
		}
		public function Search_Images() {
			global $images;
			global $users;
            
			$this->mRelations = array();
			$this->mIndex = 'images';
			$this->mTables = array(
				'images' => array( 'name' => $images ),
				'users' => array( 'name' => $users , 'jointype' => 'LEFT JOIN' , 'on' => '`user_id` = `image_userid`' )
			);
			
			$this->SetQueryFields();
			$this->Search(); // parent constructor
		}
		protected function Instantiate( $res ) {
			global $blk;
			
			$ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[] = New Image( $row );
            }

			return $ret;
		}
	}
	
	class Search_Images_Latest extends Search_Images {
		public function Search_Images_Latest( $userid , $onlyalbums = true ) {
			$this->Search_Images(); // parent constructor
            $this->SetFilter( 'delid' , 0 );
			if ( $userid != 0 ) {
				$this->SetFilter( 'user' , $userid );
			}
			if ( $onlyalbums ) {
				$this->SetNegativeFilter( 'albumid' , 0 );
			}
			$this->SetSortMethod( 'date', 'DESC' );
			$this->SetLimit( 10 );
		}
	}

?>
