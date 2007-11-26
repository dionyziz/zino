<?php

	function Albums_CreateAlbum( $albumname , $albumdescription ) {
		global $db;
		global $albums;
		global $user;
		
		$userid = $user->Id();
		$date = NowDate();
		$userip = UserIp();
		if ( strlen( $albumname > 100 ) ) {
			$albumname = utf8_substr( $albumname , 0 , 100 );
		}
		$albumname = myescape( $albumname );
		if ( strlen( $albumdescription > 200 ) ) {
				$albumdescription = utf8_substr( $albumdescription , 0 , 200 );
		}
		$albumdescription = myescape( $albumdescription );
		$sql = "INSERT INTO 
					`$albums` ( `album_id` , `album_userid` , `album_created` , `album_submithost` , `album_name` ,  `album_mainimage` , `album_description` , `album_delid` )
				VALUES 
					( '' , '$userid' , '$date' , '$userip' , '$albumname' , '0' , '$albumdescription' , '0' );";
		$res = $db->Query( $sql );
		
		return $res->InsertId();
	}
	function Albums_CountAlbumsPhotos( $keys ) {
		global $db;
		global $images;
		
		if ( !is_array( $keys ) ) {
			$keys = array( $keys );
		}
		
		foreach( $keys as $i => $key ) {
			$keys[ $i ] = myescape( $key );
		}
		
		$sql = "SELECT
					`image_albumid`, COUNT( * ) AS numphotos
				FROM 
					`$images`
				WHERE	
					`image_albumid` IN (" . implode( ", ", $keys ) . ") AND
					`image_delid` = '0'
				GROUP BY
					`image_albumid`;";
					
		$res = $db->Query( $sql );
		
		$ret = array();
		while ( $row = $res->FetchArray() ) {
			$ret[ $row[ 'image_albumid' ] ] = $row[ 'numphotos' ];
		}
		
		return $ret;
	}
	function Albums_RetrieveUserAlbums( $userid, $needphotosnum = false ) {
		global $db;
		global $albums;
		
		$userid = myescape( $userid );
		$sql = "SELECT 
					*
				FROM `$albums`
				WHERE
					`album_userid` = '$userid' AND `album_delid` = '0';";
					
		$res = $db->Query( $sql );
		
		$ret = array();
		if ( $needphotosnum  && $res->Results() ) {
				$rows = array();
				$keys = array();
				while ( $row = $res->FetchArray() ) {
					$keys[] = $row[ 'album_id' ];
					$rows[] = $row;
				}
				
				$photosnumdata = Albums_CountAlbumsPhotos( $keys );
				
                foreach ( $rows as $row ) {
					$row[ 'photosnum' ] = $photosnumdata[ $row[ 'album_id' ] ];
					$ret[] = New Album( $row );
				}
		}
		
		else {
			$ret = array();
			while( $row = $res->FetchArray() ) {
				$ret[] = New Album( $row );
			}
		}
		
		return $ret;
	}

    class Album extends Satori {
        protected $mId;
        protected $mUserId;
        protected $mUser;
        protected $mCreated;
        protected $mHost;
        protected $mName;
        protected $mMainImage;
        protected $mDescription;
        protected $mDelId;
        protected $mPageviews;
        protected $mPhotosNum;
        protected $mCommentsNum;

        public function GetUser() {
            if ( $user === false || !$user instanceof User ) {
                $this->mUser = New User( $this->UserId );
            }

            return $this->mUser;
        }
		// TODO: make this a field on the database
        public function GetPhotosNum() {
			global $images;
			global $water;
			
			if ( $this->mPhotosNum === false ) {
				$sql = "SELECT
							COUNT( * )
						AS
							numphotos
						FROM 
							`" . $this->mImageTable . "`
						WHERE	
							`image_albumid` = '" . $this->Id . "' AND `image_delid` = '0';";
							
				$res = $this->mDb->Query( $sql );
				$num = $res->FetchArray();
				$this->mPhotosNum = $num[ "numphotos" ];
			}
			
			return $this->mPhotosNum;
		}
		public function IsDeleted() {
			return $this->DelId > 0;
		}
		public function Delete() {
            $this->DelId = 1;
            $this->Save();

			$sql = "UPDATE
						`" . $this->mImageTable . "`
					SET
						`image_delid` = '1'
					WHERE
						`image_albumid` = '" . $this->Id . "';";
			
			$this->mDb->Query( $sql );
		}
        public function CommentAdded() {
			++$this->mNumComments;
		    $this->Save();	
        }
        public function CommentDeleted() {
			--$this->mNumComments;
		    $this->Save();	
        }
        public function ImageDeleted( $image ) {
            $this->mNumComments -= $image->NumComments();
            $this->Save();
        }
        public function LoadDefaults() {
            $this->Created = NowDate();
        }
		public function Album( $construct ) {
			global $db;
			global $albums;
            global $images;

            $this->mDb          = $db;
            $this->mDbTable     = $albums;
            $this->mImageTable  = $images;
			
            $this->SetFields( array(
                'album_id'          => 'Id',
                'album_userid'      => 'UserId',
                'album_created'     => 'Created',
                'album_description' => 'Description',
                'album_host'        => 'Host',
                'album_name'        => 'Name',
                'album_mainimage'   => 'MainImage',
                'album_delid'       => 'DelId',
                'album_pageviews'   => 'Pageviews',
                'album_numcomments' => 'CommentsNum'
            ) );
            
            $this->Satori( $construct );
			
			$this->User			= isset( $construct[ "user_id" ] )      ? New User( $construct )    : "";
			$this->PhotosNum    = isset( $construct[ "photosnum" ] )    ? $construct[ "photosnum" ] : false; // TODO: database field!
		}
    }

	function Albums_RetrieveImages( $albumid , $offset , $length = 16 ) {
		global $db;
		global $images;
		
		if ( $offset != 0 ) {
			$offset = $offset * $length - $length;
		}
		
		$albumid = myescape( $albumid );
		$sql = "SELECT
					 * 
				FROM 
					`$images` 
				WHERE 
					`image_albumid` = '$albumid' AND `image_delid` = '0'
				LIMIT 
					" . $offset . " , " . $length . "
				;";
					
		$res = $db->Query( $sql );
		
		$ret = array();
		while( $row = $res->FetchArray() ) {
			$ret[] = New Image( $row );
		}
		
		return $ret;
	}
	
?>
