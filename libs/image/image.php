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
		$sql = "UPDATE `$users` SET `user_numimages` = `user_numimages` + 1 WHERE `user_id` = '" . $user->Id() . "' LIMIT 1;";
		$change = $db->Query( $sql );
       
        if ( !$change->Impact() ) {
            return false;
        }

        if ( $numimages > 0 ) {
            $sql = "SELECT max( `image_id` ) AS maximg FROM `$images` WHERE `image_id` = '" . $user->Id() . "' LIMIT 1;";
            $res = $db->Query( $sql )->FetchArray();
            $maximg = $res[ 'maximg' ];

            $sql = "UPDATE `$latestimages` SET `latest_imageid` = '" . $res[ 'maximg' ] . "' WHERE `latest_userid` = '" . $user->Id() . "';";
            $change = $db->Query( $sql );
        }
        else {
            $sql = "DELETE FROM `$latestimages` WHERE `latest_userid` = '" . $user->Id() . "';";
            $change = $db->Query( $sql );
        }
		
		return $change->Impact();
	}
	function Image_Removed( $user ) {
		global $users;
		global $user;
		global $db;
		
		$sql = "UPDATE `$users` SET `user_numimages` = `user_numimages` - 1 WHERE `user_id` = '" . $user->Id() . "' LIMIT 1;";
		$change = $db->Query( $sql );
		
		return $change->Impact();
	}
	
    function Image_ById( $imageids ) {
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
    
	function Image_Upload( $path, $binary, $resizeto = false ) {
        global $xc_settings;
        global $rabbit_settings;
		global $user;              

		if ( $user->Rights() < $xc_settings[ "allowuploads" ] ) {
			return -1; // disallowed uploads
		}
        
		$fp = fsockopen( $xc_settings[ 'imagesupload' ][ 'ip' ], $xc_settings[ 'imagesupload' ][ 'port' ] );
		
        if ( !$fp ) {
            return -2; // could not connect to remote server
        }

		$body = "-----------------------------2618471642458\r\n"
		. "Content-Disposition: form-data; name=\"path\"\r\n"
		. "\r\n"
		. "$path\r\n"
		. "-----------------------------2618471642458\r\n"
		. "Content-Disposition: form-data; name=\"mime\"\r\n"
		. "\r\n"
		. "image/jpeg\r\n";

        if ( !$rabbit_settings[ 'production' ] ) {
            $body .= "-----------------------------2618471642458\r\n"
            . "Content-Disposition: form-data; name=\"sandbox\"\r\n"
            . "\r\n"
            . "yes\r\n";
        }

	    $body .= "-----------------------------2618471642458\r\n"
		. "Content-Disposition: form-data; name=\"uploadimage\"; filename=\"uploadimage\"\r\n"
		. "Content-Type: image/jpeg\r\n"
		. "Content-Transfer-Encoding: binary\r\n"
		. "\r\n"
		. $binary
		. "\r\n";
		if ( is_array( $resizeto ) && is_integer( $resizeto[0] ) && is_integer( $resizeto[1] ) ) {
			$body .= "-----------------------------2618471642458\r\n"
			. "Content-Disposition: form-data; name=\"size\"\r\n"
			. "\r\n"
			. $resizeto[0] . "x" . $resizeto[1] ."\r\n";
		}
		$body .= "-----------------------------2618471642458--\r\n";
	
		$header = "POST " . $xc_settings[ 'imagesupload' ][ 'url' ] . " HTTP/1.1\r\n"
		. "Host: " . $xc_settings[ 'imagesupload' ][ 'host' ] . "\r\n"
		. "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4\r\n"
		. "Accept: application/x-shockwave-flash,text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\r\n"
		. "Accept-Language: en-us,en;q=0.5\r\n"
		. "Accept-Encoding: \r\n"
		. "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n"
		. "Connection: close\r\n"
		. "Content-Type: multipart/form-data; boundary=---------------------------2618471642458\r\n"
		. "Content-Length: ".  strlen( $body ) . " \r\n\r\n";

        if ( $user->IsSysOp() ) {
            var_dump( $header );
            var_dump( $body );
            die();
            header( 'Content-type: text/plain' );
            die( $header . $body );
        }
        
		fputs( $fp, $header . $body );

		$data = '';
		while ( !feof( $fp ) ) {
			$data .= @fgets( $fp, 1024 );
		}
        
		fclose( $fp );

        $split = explode( "\r\n\r\n", $data );
		
        $data = $split[ 1 ];
		$upload = array();

		if ( strpos( $data, "error" ) !== false && $user->IsSysOp() ) {
			die( $data );
		}
		else if ( strpos( $data, "error" ) !== false ) {
			$upload[ 'successful' ] = false;
		}
		else if ( strpos( $data, "success" ) !== false ) {
			$upload[ 'successful' ] = true;
			$start = strpos( $data, "[" ) + 1;

			$resolution = substr( $data, $start, strlen( $data ) - $start - 1 );
			$split = explode( "," , $resolution );
			$width = $split[ 0 ];
            $height = $split[ 1 ];
			$filesize = $split[ 2 ];
			$upload[ 'width' ] = (integer) $width;
			$upload[ 'height' ] = (integer) $height;
			$upload[ 'filesize' ] = (integer) $filesize;
		}
        else {
            if ( $user->IsSysOp() ) {
                die( $data );
            }
        }

		return $upload;
	}
	
	function getAllImageInfo( $id ) {
		global $images;
		global $db;
		
		$img_id = myescape( $id );
		$sql = "SELECT * FROM `$images` WHERE `image_id`='$img_id' LIMIT 1;";
		
		$res = $db->Query( $sql );
		$fetched = $res->FetchArray();
		
		return $fetched;
	}
    function CountImages() {
		global $images;
		global $db;
		
		$sql = "SELECT 
					COUNT( * )
				AS 
					imagesnum
				FROM 
					`$images`
				WHERE
					`image_delid` = '0';";
		$res = $db->Query( $sql );
		$num = $res->FetchArray();
		$num = $num[ "imagesnum" ];
		
		return $num;
	}
	class Image {
		private $mId;
		private $mUserId;
		private $mCreator;
		private $mDate;
		private $mHost;
		private $mName;
		private $mWidth;
		private $mHeight;
		private $mSize;
		private $mExists;
		private $mMimetype;
		private $mExtension;
		private $mAlbumid;
		private $mDescription;
		private $mPageviews;
		private $mNumComments;
		
		public function Exists() {
			return $this->mExists;
		}
		public function Id() {
			return $this->mId;
		}
		public function UserId() {
			return $this->mUserId;
		}
		public function Creator() {
			if ( empty( $this->mCreator ) ) {
				$this->mCreator = New User( $this->UserId() );
			}
			return $this->mCreator;
		}
		public function Date() {
			return $this->mDate;
		}
		public function Host() {
			return $this->mHost;
		}
		public function Name() {
			return $this->mName;
		}
		public function Title() {
			return $this->Name();
		}
		public function Width() {
			return $this->mWidth;
		}
		public function Height() {
			return $this->mHeight;
		}
		public function Size() {
			return $this->mSize;
		}
		public function URL() {
			global $rabbit_settings;
			
			return $rabbit_settings[ 'resourcesdir' ] . '/' . $this->UserId() . '/' . $this->Id();
		}
		public function MimeType() {
			return $this->mMimeType;
		}
		public function Extension() {
			if ( $this->mExtension === false ) {
				$this->mExtension = getextension( $this->Name() );
			}
			return $this->mExtension;
		}
		public function AlbumId() {
			return $this->mAlbumid;
		}
		public function Description() {
			return $this->mDescription;
		}
		public function UpdateName( $newname ) {
			global $db;
			global $images;
			
			$newname = myescape( $newname );
			$sql = "UPDATE
						`$images`
					SET 
						`image_name` = '$newname'
					WHERE 
						`image_id` = '" . $this->Id() . "'
					LIMIT 1;";
			$db->Query( $sql );
		}
		public function UpdateDescription( $newdescription ) { 
			global $db;
			global $images;
			
			$newdescription = myescape( $newdescription );
			$sql = "UPDATE
						`$images`
					SET
						`image_description` = '$newdescription'
					WHERE
						`image_id` = '" . $this->Id() ."'
					LIMIT 1;";
			$db->Query( $sql );
		}
		public function ProportionalSize( $maxw , $maxh ) {
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
            global $db;
            global $images;

            $album = new Album( $this->mAlbumid );
            $album->CommentAdded();
		   
            ++$this->mNumComments;
		   
            $sql = "UPDATE
                        `$images`
                    SET
                        `image_numcomments` = `image_numcomments` + 1
                    WHERE
                        `image_id` = '" . $this->Id() . "'
                    LIMIT 1;";
		   
            return $db->Query( $sql )->Impact();
		}
		public function NumComments() {
			return $this->mNumComments;
		}
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
		public function AddPageview( $bywhom = 0 ) {
			global $db;
			global $pageviews;
			global $user;
			
			if ( $bywhom == 0 ) {
				$bywhom = $user->Id();
				if ( $bywhom == 0 ) {
					return false;
				}
			}
			
			$sqlarray = array(
				'pageview_type' => 'image',
				'pageview_itemid' => $this->Id(),
				'pageview_userid' => $bywhom,
				'pageview_date' => NowDate()
			);
			
			++$this->mPageviews;
			
			return $db->Insert( $sqlarray, $pageviews )->Impact();
		}
		public function SetPageviews( $newpageviews ) {
			$this->mPageviews = $newpageviews;
		}
		public function Delete() {
			global $db;
			global $images;
			global $latestimages;
			
			$sql = "UPDATE `$images` SET `image_delid` = '1' WHERE `image_id` = '".$this->Id()."' LIMIT 1;";
			$db->Query( $sql );

            $album = new Album( $this->mAlbumid );
            $album->ImageDeleted( $this );
			
			//update latest images
			$sql = "SELECT
						`image_id`
					FROM
						`$images`
					WHERE
						`image_userid` = '" . $this->UserId() . "'
					AND
						`image_delid` = 0
					ORDER BY
						`image_id`
						DESC
					LIMIT 1;";
			
			$res = $db->Query( $sql );
			
			if ( !$res->Results() ) {
				$sql = "DELETE FROM
							`$latestimages`
						WHERE
							`latest_imageid` = 	'" . $this->Id() . "'
						LIMIT 1;";
						
				$db->Query( $sql );
			}
			else {
				while( $row = $res->FetchArray() ) {
                    $sql = "REPLACE INTO
                                `$latestimages`
                                ( `latest_userid`,
                                  `latest_imageid` )
                            VALUES
                                ( '" . $this->UserId() . "',
                                  '" . $row[ 'image_id' ] . "' );";	
                                  
                    $db->Query( $sql );
				}
			}
		}
		public function CommentKilled( ) {
			global $db;
			global $images;
            
            $album = new Album( $this->mAlbumid );
            $album->CommentDeleted();
			
			$sql = "UPDATE `$images` SET `image_numcomments` = '" . --$this->mNumComments . "' WHERE `image_id` = '" . $this->Id() . "' LIMIT 1;";
			
			return $db->Query( $sql )->Impact();
		}
		public function Image( $construct = false ) {
			// New image( $id )
			// New image( $fetch_array )
			
			global $images;
			global $db;
			global $water;
			
            if ( $construct === false ) { // empty/non-existing image
                $imagearray = array();
                $img_id = 0;
            }
			else if ( is_array( $construct ) ) {
				$imagearray = $construct;
				$img_id = $construct[ 'image_id' ];
                if ( $img_id == 0 ){    
                    $this->mExists = false;
                    return false;
                }
				$this->mExists = true;
			}
			else {
				$img_id = myescape( $construct ); 
				$query = "SELECT * FROM `$images` WHERE `image_id`='$img_id' LIMIT 1;";
				$sqlr = $db->Query( $query );
				$num_rows = $sqlr->NumRows();			
				if ( $num_rows == 0 ) { // If there is no image in the database
					$this->mExists = false;
					return;
				}
				$imagearray = $sqlr->FetchArray();
				$this->mExists = true;
			}
			
			// Assign the values fetched from the database
			$this->mId 			= $img_id;
			$this->mUserId 		= isset( $imagearray[ "image_userid" ] )      ? $imagearray[ "image_userid" ] : 0;
			$this->mDate 		= isset( $imagearray[ "image_created" ] )     ? $imagearray[ "image_created" ] : '0000-00-00 00:00:00';
			$this->mHost 		= isset( $imagearray[ "image_userip" ] )      ? $imagearray[ "image_userip" ] : '0.0.0.0';
			$this->mName 		= isset( $imagearray[ "image_name" ] )        ? $imagearray[ "image_name" ] : '';
			$this->mWidth 		= isset( $imagearray[ "image_width" ] )       ? $imagearray[ "image_width" ] : 0;
			$this->mHeight		= isset( $imagearray[ "image_height" ] )      ? $imagearray[ "image_height" ] : 0;
			$this->mSize 		= isset( $imagearray[ "image_size" ] )        ? $imagearray[ "image_size" ] : 0;
			$this->mMimeType 	= isset( $imagearray[ "image_mime" ] )        ? $imagearray[ "image_mime" ] : 'image/jpeg';
			$this->mBinary 		= false;
			$this->mAlbumid 	= isset( $imagearray[ "image_albumid" ] )     ? $imagearray[ "image_albumid" ] : 0;
			$this->mDescription = isset( $imagearray[ "image_description" ] ) ? $imagearray[ "image_description" ] : '';
			$this->mNumComments = isset( $imagearray[ "image_numcomments" ] ) ? $imagearray[ "image_numcomments" ] : 0;
			$this->mPageviews	= "";
		}
	}
	
	function submit_photo( $filename , $temp_location , $albumid = 0 , $description = '' , $resizeto = false ) {
		global $user;
		global $images;
		global $latestimages;
        global $rabbit_settings;
		global $db;
		
		if ( strlen( $filename ) > 96 ) {
			$filename = utf8_substr( $filename , 0 , 96 ) . "." . $extension;
		}
		$filename = mysql_escape_string( $filename );
		$albumid = mysql_escape_string( $albumid );
		if ( strlen( $description ) > 200 ) {
			$description = utf8_substr( $description , 0 , 200 );
		}
		$description = mysql_escape_string( $description );
		$userid = $user->Id(); 
		$date = NowDate();
		$host = UserIp();
		$extension = getextension( $filename );
		
		$size = filesize( $temp_location );
		$fp = fopen( $temp_location , "r" );
		$binary = fread( $fp , $size );
		fclose( $fp );
		
		$width = 0;
		$height = 0;

		$mime = mime_by_extension( $extension );
		$sql = "INSERT INTO `$images` ( `image_id` , `image_userid` , `image_created` , `image_userip` , `image_name` , `image_mime` , `image_width` , `image_height` , `image_size` , `image_delid` , `image_albumid` , `image_description` , `image_numcomments` )
							   VALUES (     '' ,       '$userid' ,     '$date' ,           '$host' ,      '$filename', '$mime' , '$width' , '$height' , '$size' , '0' , '$albumid' , '$description' , '0' );";
		$change = $db->Query( $sql );
		$lastimgid = $change->InsertId();

		$upload = Image_Upload( $userid."/".$lastimgid , $binary, $resizeto );
		
        if ( !is_array( $upload ) ) {
            return $upload; // errorcode
        }
        
		if ( $upload[ 'successful' ] ) {
			$sql = "UPDATE `$images` SET `image_width` = '" . myescape( $upload[ 'width' ] ) . "', `image_height` = '" . myescape( $upload[ 'height' ] ) . "', `image_size` = '" . myescape( $upload[ 'filesize' ] ) . "' WHERE `image_id` = '$lastimgid' LIMIT 1;";
			$change = $db->Query( $sql );

			if ( $change->Impact() ) {
                $sql = "REPLACE INTO `$latestimages` ( `latest_userid`, `latest_imageid` ) VALUES( '" . $user->Id() . "', '$lastimgid' );";
                $db->Query( $sql );

				return $lastimgid;
			}
            return -64;
		}
        return -65;
	}
	
	function delete_photo( $photo_id ) {
		global $user;
		global $images;
		global $db;
		
        // TODO: delete the actual file?
		//$photo_id = mysql_escape_string( $photo_id );
		//we have to see if we 'll use another table for deleted images
		//again suppose class user has been instanciated
		$userid = $user->Id();
        $photo_id = myescape( $photo_id );
		$sql = "UPDATE `$images` SET `image_delid`='1' WHERE `image_id`='$photo_id';";
		$db->Query( $sql );
	}
	
	function NoExtensionName( $filename ) {
		$dotposition = strrpos( $filename , ".");
		if( $dotposition === false ) {
			return $filename;
		}
		$filename = substr( $filename , 0 , $dotposition );	
		
		return $filename;
	}
	function getextension( $filename ) {
		$strlength = strlen( $filename );
		$dotposition = strrpos( $filename , "." );
		$extension = substr( $filename , $dotposition + 1 , $strlength - $dotposition + 1 );	
		
		return $extension;
	}
	function mime_by_filename( $filename  ) {
		$ext = getextension( $filename );
		$ext = strtolower( $ext );
		
		return mime_by_extension( $ext );
	}
	function mime_by_extension( $ext ) {
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
	
	function Image_LatestUnique( $limit ) {
		global $db;
        global $users;
        global $images;
		global $latestimages;
		
		$limit = mysql_escape_string( $limit );
		
		$sql = "SELECT
					`latest_imageid`
				FROM
					`$latestimages`
				CROSS JOIN
					`$images`
				ON
					`latest_imageid` = `image_id`
				WHERE 
					`image_albumid` != 0
				ORDER BY
					`latest_imageid`
					DESC
				LIMIT
					$limit";
		
		$res = $db->Query( $sql );
		
		$rows = array();
		
		while ( $row = $res->FetchArray() ) {
            $rows[] = New Image( $row[ 'latest_imageid' ] );
        }
		
		return $rows;
	}
?>
