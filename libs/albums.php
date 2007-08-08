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
	function Albums_CountAlbumsComments( $keys ) {
		global $db;
		global $comments;
		global $images;
		
		if ( !is_array( $keys ) ) {
			$keys = array( $keys );
		}
		
		foreach ( $keys as $i => $key ) {
			$keys[ $i ] = myescape( $key );
		}
	
		$sql = "SELECT
					`image_albumid`, COUNT( `comment_id` ) AS numcomments
				FROM 
					`$images` LEFT JOIN `$comments`
						ON ( `image_id` = `comment_storyid` )
				WHERE
					`image_albumid` IN (" . implode( ", ", $keys ) . ") AND
					`comment_typeid` = '2' AND 
					`comment_delid` = '0'
				GROUP BY
					`image_albumid`
				;";
					
		$res = $db->Query( $sql );
		
		$ret = array();
        foreach ( $keys as $imagealbumid ) {
            $ret[ $imagealbumid ] = 0; // assume 0
        }
        
		while ( $row = $res->FetchArray() ) {
			$ret[ $row[ "image_albumid" ] ] = $row[ "numcomments" ];
		}
		return $ret;
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
	function Albums_RetrieveUserAlbums( $userid, $needcommentsnum = false, $needphotosnum = false ) {
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
		if ( ( $needcommentsnum || $needphotosnum ) && $res->Results() ) {
				$rows = array();
				$keys = array();
				while ( $row = $res->FetchArray() ) {
					$keys[] = $row[ 'album_id' ];
					$rows[] = $row;
				}
				
				if ( $needcommentsnum ) {
					$commentsnumdata = Albums_CountAlbumsComments( $keys );
				}
				if ( $needphotosnum ) {
					$photosnumdata = Albums_CountAlbumsPhotos( $keys );
				}
				
				foreach ( $rows as $row ) {
					if ( isset( $commentsnumdata ) ) {
						$row[ 'commentsnum' ] = $commentsnumdata[ $row[ 'album_id' ] ];
					}
					if ( isset( $photosnumdata ) ) {
						$row[ 'photosnum' ] = $photosnumdata[ $row[ 'album_id' ] ];
					}
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
	class Album {
		private $mAlbid;
		private $mAlbuserid;
		private $mAlbcreator;
		private $mAlbcreated;
		private $mAlbhost;
		private $mAlbname;
		private $mAlbmainpic;
		private $mAlbdescription;
		private $mAlbdelid;
		private $mPageviews;
		private $mPhotosNum;
		private $mCommentsNum;
		
		public function Id() {
			return $this->mAlbid;
		}
		public function Creator() {
			if ( empty( $this->mAlbcreator ) ) {
				$this->mAlbcreator = New User( $this->UserId() );
			}
			return $this->mAlbcreator;
		}
		public function UserId() {
			return $this->mAlbuserid;
		}
		public function Date() {
			return $this->mAlbcreated;
		}
		public function Host() {
			return $this->mAlbhost;
		}
		public function Name() {
			return $this->mAlbname;
		}
		public function MainImage() {
			return $this->mAlbmainpic;
		}
		public function Description() {
			return $this->mAlbdescription;
		}
		public function PhotosNum() {
			global $db;
			global $images;
			global $water;
			
			if ( $this->mPhotosNum === false ) {
				// photos of the album are not counted yet
				$sql = "SELECT
							COUNT( * )
						AS
							numphotos
						FROM 
							`$images`
						WHERE	
							`image_albumid` = '" . $this->Id() . "' AND `image_delid` = '0';";
							
				$res = $db->Query( $sql );
				$num = $res->FetchArray();
				$this->mPhotosNum = $num[ "numphotos" ];
			}
			
			return $this->mPhotosNum;
		}
		public function CommentsNum() {
			global $db;
			global $comments;
			global $images;
			global $water;
			
			if ( $this->mCommentsNum === false ) {
				// comments are not counted yet
				$sql = "SELECT
							COUNT( `comment_id` )
						AS
							numcomments
						FROM 
							`$images` LEFT JOIN `$comments`
								ON ( `image_id` = `comment_storyid` )
						WHERE
							`image_albumid` = '" . $this->Id() . "' AND
							`comment_typeid` = '2' AND 
							`comment_delid` = '0';";
							
				$res = $db->Query( $sql );
				$num = $res->FetchArray();
				$this->mCommentsNum = $num[ "numcomments" ];
			}
			
			return $this->mCommentsNum;
		}
		public function DelId() {
			return $this->mAlbdelid;
		}
		public function IsDeleted() {
			return $this->mAlbdelid > 0;
		}
		public function Exists() {
			return $this->mAlbid > 0;
		}
		public function UpdateName( $newname ) {
			global $db;
			global $albums;
			
			$sql = "UPDATE
						`$albums`
					SET
						`album_name` = '$newname'
					WHERE 
						`album_id` = '".$this->Id()."'
					LIMIT 1;";
			$db->Query( $sql );
		}
		public function UpdateMainImage( $imageid ) {
			global $db;
			global $albums;
			
			// $imageid points to an image with the id in the `merlin_albumimages` table
			$sql = "UPDATE
						`$albums`
					SET
						`album_mainimage` = '$imageid'
					WHERE
						`album_id` = '".$this->Id()."'
					LIMIT 1;";
			$db->Query( $sql );
		}
		public function UpdateDescription( $newdescription ) {
			global $db;
			global $albums;
			
			$sql = "UPDATE
						`$albums`
					SET
						`album_description` = '$newdescription'
					WHERE
						`album_id` = '".$this->Id()."'
					LIMIT 1;";
			
			$db->Query( $sql );
		}
		public function Delete() {
			global $db;
			global $albums;
            global $images;
            
			$sql = "UPDATE 
						`$albums`
					SET 
						`album_delid` = '1'
					WHERE 
						`album_id` = '".$this->Id()."'
					LIMIT 1;";
					
			$db->Query( $sql );
			
			$sql = "UPDATE
						`$images`
					SET
						`image_delid` = '1'
					WHERE
						`image_albumid` = '" . $this->Id() . "';";
			
			$db->Query( $sql );
			
			$this->mAlbdelid = 1;
		}
		public function Pageviews() {
			return $this->mPageviews;
		}
		public function AddPageview() {
			global $db;
			global $albums;
			
			++$this->mPageviews;
			
			$sql = "UPDATE `$albums` SET `album_pageviews` = '" . $this->mPageviews . "' WHERE `album_id` = '" . $this->Id() . "' LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function Album( $construct ) {
			global $db;
			global $albums;
			
			if ( !is_array( $construct ) ) {
				$construct = myescape( $construct );
				$sql = "SELECT
							*
						FROM 
							`$albums`
						WHERE
							`album_id` = '$construct'
						LIMIT 1;";
				$res = $db->Query( $sql );
				if ( !$res->Results() ) {
					$construct = array();
					$construct[ "album_delid" ] = 1;
				}
				else {
					$construct = $res->FetchArray();
				}
			}
			
			$this->mAlbid				= isset( $construct[ "album_id" ] ) ? $construct[ "album_id" ] : 0;
			$this->mAlbuserid 			= isset( $construct[ "album_userid" ] ) ? $construct[ "album_userid" ] : 0;
			$this->mAlbcreator			= isset( $construct[ "user_id" ] ) ? New User( $construct ) : "";
			$this->mAlbcreated 			= isset( $construct[ "album_created" ] ) ? $construct[ "album_created" ] : "00:00:00 0000-00-00";
			$this->mAlbhost 			= isset( $construct[ "album_submithost" ] ) ? $construct[ "album_submithost" ] : "";
			$this->mAlbname 			= isset( $construct[ "album_name" ] ) ? $construct[ "album_name" ] : "";
			$this->mAlbmainpic 			= isset( $construct[ "album_mainimage" ] ) ? $construct[ "album_mainimage" ] : 0;
			$this->mAlbdescription 		= isset( $construct[ "album_description" ] ) ? $construct[ "album_description" ] : "";
			$this->mAlbdelid 			= isset( $construct[ "album_delid" ] ) ? $construct[ "album_delid" ] : 0;
			
			$this->mPageviews			= isset( $construct[ "album_pageviews" ] ) ? $construct[ "album_pageviews" ] : 0;
			$this->mCommentsNum			= isset( $construct[ "commentsnum" ] ) ? $construct[ "commentsnum" ] : false;
			$this->mPhotosNum			= isset( $construct[ "photosnum" ] ) ? $construct[ "photosnum" ] : false;
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
