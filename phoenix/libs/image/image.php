<?php
	/*
	images media structure for images 
	includes a class for the media and storing is made to the HD and not to the db
	Developer: Izual
	*/
	
	global $libs;
	
	$libs->Load( 'search' );
	$libs->Load( 'albums' );
	
	function Image_Added( $user ) {
		global $users;
        global $latestimages;
		global $images;
        global $user;
		global $db;
		
        $numimages = $user->CountImages() - 1;
		// Prepared query
		$db->Prepare("
			UPDATE 
				`$users` 
			SET 
				`user_numimages` = `user_numimages` + 1 
			WHERE `user_id` = :UserId
			LIMIT :Limit
			;
		");
		
		// Assign values to query
		$db->Bind( 'UserId', $user->Id() );
		$db->Bind( 'Limit' , 1 );
		
		// Execute query
		$change = $db->Execute();
       
        if ( !$change->Impact() ) {
            return false;
        }

        if ( $numimages > 0 ) {
			// Prepared query
			$db->Prepare("
				SELECT 
					max( `image_id` ) AS maximg 
				FROM `$images` 
				WHERE `image_userid` = :ImageUserId
				LIMIT :Limit
				;
			");
			
			// Assign values to query
			$db->Bind( 'ImageUserId', $user->Id() );
			$db->Bind( 'Limit', 1 );
			
            // Execute query 
            $res = $db->Execute()->FetchArray();
            $maximg = $res[ 'maximg' ];
			
			$db->Prepare("
				UPDATE 
					`$latestimages` 
				SET 
					`latest_imageid`  = :LatestImageId
				WHERE `latest_userid` = :LatestUserId
				;
			");
            
			// Assign values to query
			$db->Bind( 'LatestImageId', $res[ 'maximg' ] );
			$db->Bind( 'LatestUserId', $user->Id() );
			
			// Execute query
            $change = $db->Execute();
        }
        else {
			$db->Prepare("
				DELETE FROM 
					`$latestimages` 
				WHERE 
					`latest_userid` = :LatestUserId
				;
			");
			
			// Assign values to query
			$db->Bind( 'LatestUserId', $user->Id() );
            
			// Execute query
            $change = $db->Execute();
        }
		
		return $change->Impact();
	}
	function Image_Removed( $user ) {
		global $users;
		global $user;
		global $db;
		
		// Prepared query
		$db->Prepare("
		UPDATE 
			`$users` 
		SET 
			`user_numimages` = `user_numimages` - 1 
		WHERE `user_id` = :UserId 
		LIMIT :Limit
		;
		");
		
		// Assign values to query
		$db->Bind( 'UserId', $user->Id() );
		$db->Bind( 'Limit', 1);
		
		// Execute query
		$change = $db->Execute();
		
		return $change->Impact();
	}
	
    function Image_ListById( $imageids ) {
        global $db;
        global $images;
        
        if ( is_array( $imageids ) ) {
            if ( !count( $imageids ) ) {
                return array();
            }
            $wasarray = true;
        }
        else {
            $imageids = array( $imageids );
            $wasarray = false;
        }
        foreach ( $imageids as $i => $imageid ) {
            $imageids[ $i ] = ( integer )$imageid;
        }
        
		// TODO: Bind must take an array parameter for implode implementation 
        $sql = "SELECT
                    *
                FROM
                    `$images`
                WHERE
                    `image_id` IN (" . implode(',', $imageids) . ");";
        $res = $db->Query( $sql );
        
        $rows = array();
        while ( $row = $res->FetchArray() ) {
            $rows[ $row[ 'image_id' ] ] = New Image( $row );
        }
        
        if ( $wasarray ) {
            return $rows;
        }
        if ( count( $rows ) ) {
            return array_shift( $rows );
        }
        return array();
    }
	
    function Image_Count() {
		global $images;
		global $db;
		
		// Prepared query
		$db->Prepare("
			SELECT 
				COUNT( * )
			AS 
				imagesnum
			FROM 
				`$images`
			WHERE
				`image_delid` = :ImageDelId
			;
		");
		
		// Assign query values
		$db->Bind( 'ImageDelId', '0' );
		
		// Execute query
		$res = $db->Execute();
		$num = $res->FetchArray();
		$num = $num[ "imagesnum" ];
		
		return $num;
	}
	
    function Image_NoExtensionName( $filename ) {
		$dotposition = strrpos( $filename , ".");
		if( $dotposition === false ) {
			return $filename;
		}
		$filename = substr( $filename , 0 , $dotposition );	
		
		return $filename;
	}
	function Image_GetExtension( $filename ) {
		$strlength = strlen( $filename );
		$dotposition = strrpos( $filename , "." );
		$extension = substr( $filename , $dotposition + 1 , $strlength - $dotposition + 1 );	
		
		return $extension;
	}
	function Image_MimeByFilename( $filename  ) {
		$ext = Image_GetExtension( $filename );
		$ext = strtolower( $ext );
		
		return Image_MimeByExtension( $ext );
	}
	function Image_MimeByExtension( $ext ) {
		$mimetypes = array( 
			"jpg" => "image/jpeg" , 
			"png" => "image/png" , 
			"bmp" => "image/bmp" ,
			"gif" => "image/gif" ,
			"tiff" =>"image/tiff" ,
			"tif" =>"image/tiff" ,
			"ico" =>"image/x-icon" , 
			"jpe" =>"image/jpeg" ,
			"pjpeg" =>"image/jpeg",
			"jpeg" =>"image/jpeg" ,
			"rgb" =>"image/x-rgb" ,
		);	
		
		if ( !$mimetypes[ $ext ] ) {
			return false;
		}
		else {
			return $mimetypes[ $ext ];
		}	
	}
	
	function Image_LatestUnique( $limit ) {
		global $db;
        global $users;
        global $images;
		global $latestimages;
		
		//$limit = mysql_escape_string( $limit );
		
		// Prepared query
		$db->Prepare("
			SELECT
				`latest_imageid`
			FROM
				`$latestimages`
			CROSS JOIN
				`$images`
			ON
				`latest_imageid` = `image_id`
			WHERE 
				`image_albumid` != :ImageAlbumId
			ORDER BY
				`latest_imageid`
				DESC
			LIMIT
				:Limit
		");
		
		// Assign values to query
		$db->Bind( 'ImageAlbumId', 0 );
		$db->Bind( 'Limit', $limit );
		
		// Execute query
		$res = $db->Execute();
		
		$rows = array();
		
		while ( $row = $res->FetchArray() ) {
            $rows[] = New Image( $row[ 'latest_imageid' ] );
        }
		
		return $rows;
	}

    class Image extends Satori {
        protected $mId;
        protected $mUserId;
        protected $mUserIp;
        protected $mUser;
        protected $mDate;
        protected $mName;
        protected $mWidth;
        protected $mHeight;
        protected $mSize;
        protected $mMime;
        protected $mExtension;
        protected $mAlbumId;
        protected $mAlbum;
        protected $mDescription;
        protected $mPageViews;
        protected $mCommentsNum;
        
        public function GetUser() {
            if ( $this->mUser === false ) {
                $this->mUser = New User( $this->UserId );
            }
            return $this->mUser;
        }
        public function GetTitle() {
            return $this->Name;
        }
		public function GetServerUrl() {
			global $rabbit_settings;
			
			return $rabbit_settings[ 'resourcesdir' ] . '/' . $this->UserId . '/' . $this->Id;
		}
        public function GetExtension() {
            if ( $this->mExtension === false ) {
                $this->mExtension = getextension( $this->Name() );
            }
            return $this->mExtension;
        }
		public function GetProportionalSize( $maxw , $maxh ) {
			$propw = 1;
			$proph = 1;
			if ( $this->Width() > $maxw ) {
				$propw = $this->Width() / $maxw;
			}
			if ( $this->Height() > $maxh ) {
				$proph = $this->Height() / $maxh;
			}
			$prop = max( $propw , $proph );
			$size[ 0 ] = round( $this->Width() / $prop , 0 );
			$size[ 1 ] = round( $this->Height() / $prop , 0 );
			
			return $size;
		}
		public function CommentAdded() {
            $album = new Album( $this->AlbumId );
            $album->CommentAdded();
		   
            ++$this->mNumComments;
		    return $this->Save();
		}
        public function AddPageview() {
            ++$this->mPageviews;
            return $this->Save();
        }
		public function Delete() {
			global $db;
			global $images;
			global $latestimages;
			
            $this->DelId = 1;
            $this->Save();

            $this->Album->ImageDeleted( $this );
            Image_Removed( $this->User );

			//update latest images
			
			// Prepared query
			$this->mDb->Prepare("
				SELECT
					`image_id`
				FROM
					`$images`
				WHERE
					`image_userid` = :ImageUserId
				AND
					`image_delid` = :ImageDelId
				ORDER BY
					`image_id` DESC
				LIMIT :Limit
				;
			");
			
			// Assign query values
			$this->mDb->Bind( 'ImageUserId', $this->UserId );
			$this->mDb->Bind( 'ImageDelId', 0);
			$this->mDb->Bind( 'Limit', 1);
			
			// Execute query
			$res = $this->mDb->Execute();
			
			if ( !$res->Results() ) {
				// Prepared query
				$this->mDb->Prepare("
					DELETE FROM
						`$latestimages`
					WHERE
						`latest_imageid` = 	:LatestImageId
					LIMIT :Limit
					;
				");
				
				// Assign query values
				$this->mDb->Bind( 'LatestImageId', $this->Id );
				$this->mDb->Bind( 'Limit', 1 );
						
				// Execute query
				return $this->mDb->Execute();
			}
			else {
				while( $row = $res->FetchArray() ) {
                    $sql = "REPLACE INTO
                                `$latestimages`
                                ( `latest_userid`,
                                  `latest_imageid` )
                            VALUES
                                ( '" . $this->UserId . "',
                                  '" . $row[ 'image_id' ] . "' );";	
                                  
                    return $this->mDb->Query( $sql );
				}
			}
		}
        public function CommentDeleted() {
            if ( !$this->Album->CommentDeleted() ) {
                return false;
            }

            --$this->NumComments;
            return $this->Save();
        }
        public function SetAlbumId( $value ) {
            global $user;

            $album = New Album( $value );
            if ( $album->Exists() && $album->User->Id() != $user->Id() ) {
                return false;
            }
            $this->mAlbumId = $value;
        }
        public function SetTemporaryFile( $value ) {
            $this->mTemporaryFile = $value;

            if ( filesize( $value ) > 1024*1024 ) {
                return -1;
            }
            
            if ( $this->Name = mystrtolower( basename( $value ) ) == -1 ) { // wrong extension
                return -2;
            }
        }
        public function SetName( $value ) {
            if ( !$this->Extension = Image_GetExtension( $value ) ) {
                return -1;
            }

            $this->mName = $value;

            if ( strlen( $value ) > 96 ) {
                $this->mName = utf8_substr( $value , 0 , 96 ) . "." . $this->Extension;
            }
            
            $noext = Image_NoExtenstionName( $value );
            if ( empty( $noext ) ) {
                $this->mName = 'noname' . rand( 1, 20 ) . $this->Extension;
            }
        }
        public function SetExtension( $value ) {
            $extensions = array( 'jpg', 'jpeg', 'png', 'gif' );
            if ( !in_array( $value, $extensions ) ) {
                return false;
            }
            $this->mExtension = $value;
        }
        public function SetDescription( $value ) {
            $this->mDescription = $value;

            if ( strlen( $value ) > 200 ) {
                $this->mDescription = utf8_substr( $value, 0, 200 );
            }
        }
        public function Upload( $resizeto = false ) {
            $path = $this->UserId . "/" . $this->Id;

            return Image_Upload( $path, $this->mTemporaryFile, $resizeto );
        }
        public function Save( $resizeto = false ) {
            if ( $this->Exists() ) {
                return parent::Save();
            }

            // else
            $this->Size = filesize( $this->mTemporaryFile );
            $this->Mime = Image_MimeByExtension( $extension );
            
            if ( !parent::Save() ) {
                return -1;
            }

            $upload = $this->Upload( $resizeto );
            if ( $upload < 0 ) {
                return $upload; // error code
            }

            if ( parent::Save() ) { // save again: Upload() has set size, width and height 
                $sql = "REPLACE INTO `$latestimages` ( `latest_userid`, `latest_imageid` ) VALUES( '" . $user->Id() . "', '$lastimgid' );";
                $this->mDb->Query( $sql );
            }

            Image_Added();
        }
        public function SetDefaults() {
            $this->mDate    = NowDate();
            $this->mUserIp  = UserIp();
            $this->Width    = 0;
            $this->Height   = 0;
        }
		public function Image( $construct = false ) {
			global $db;
            global $images;
			global $water;
			
		    $this->mDb = $db;
            $this->mDbTable = $images;

            $this->SetFields( array(
                'image_id'          => 'Id',
                'image_userid'      => 'UserId',
                'image_created'     => 'Date',
                'image_userip'      => 'UserIp',
                'image_name'        => 'Name',
                'image_description' => 'Description',
                'image_width'       => 'Width',
                'image_height'      => 'Height',
                'image_size'        => 'Size',
                'image_mime'        => 'Mime',
                'image_albumid'     => 'AlbumId',
                'image_numcomments' => 'CommentsNum',
                'image_pageviews'   => 'Pageviews'
            ) );

            $this->Satori( $construct );

            $this->User     = isset( $construct[ "user_id" ] )  ? New User( $construct )  : false;
            $this->Album    = isset( $construct[ "album_id" ] ) ? New Album( $construct ) : false;
		}
    }
		/*
        public function Pageviews() {
			global $db;
			global $pageviews;
			
			if ( empty( $this->mPageviews ) ) {
				$sql = "SELECT
							COUNT( * )
						AS 
							numpages
						FROM 
							`$pageviews`
						WHERE 
							`pageview_itemid` = '" . $this->Id() . "' AND `pageview_type` = 'image';";
							
				$res = $db->Query( $sql );
				$row = $res->FetchArray();
				$views = $row[ "numpages" ];
				
				$this->SetPageviews( $views );
			}
			return $this->mPageviews;
		}
        */

?>
