<?php
	/*
	Images media structure for images 
	*/
	
	global $libs;
	
	$libs->Load( 'album' );
    $libs->Load( 'image/server' );
	$libs->Load( 'image/frontpage' );
    
    class ImageFinder extends Finder {
        protected $mModel = 'Image';
        
        public function FindByIds( $imageids ) {
            w_assert( is_array( $imageids ) );
            foreach ( $imagesids as $imageid ) {
                w_assert( is_int( $imageid ) );
            }
            if ( !count( $imageids ) ) {
                return array();
            }
            
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :images
                WHERE
                    `image_id` IN :imageids'
            );
            $query->BindTable( 'images' );
            $query->Bind( 'imageids', $imageids );
            
            return $this->FindBySQLResource( $query->Execute() );
        }
        public function FindByUser( User $theuser, $offset = 0, $limit = 15 ) {
            $prototype = New Image();
            $image->Userid = $theuser->Id;
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Imageid', 'DESC' ) );
        }
        public function FindByAlbum( Album $album, $offset = 0, $limit = 25 ) {
            $prototype = New Image();
            $image->Albumid = $album->Id;
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'ImageId', 'DESC' ) );
        }
        public function FindFrontpage( $offset = 0, $limit = 15 ) {
            $finder = New FrontpageImageFinder();
            $found = $finder->FindLatest( $offset, $limit );
            $imageids = array();
            foreach ( $found as $frontpageimage ) {
                $imageids[] = $frontpageimage->Imageid;
            }
            if ( !count( $imageids ) ) {
                return array();
            }
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :images
                WHERE
                    `image_id` IN :imageids'
            );
            $query->BindTable( 'images' );
            $query->Bind( 'imageids', $imageids );
            return $this->FindBySQLResource( $query->Execute() );
        }
        public function Count() {
    		$query = $this->mDb->Prepare("
    			SELECT 
    				COUNT(*) AS imagesnum
    			AS 
    				imagesnum
    			FROM 
    				`:images`
    			WHERE
    				`image_delid` = 0;");
            $query->BindTable( 'images' );
            
    		$res = $query->Execute();
    		$row = $res->FetchArray();
    		return $row[ "imagesnum" ];
    	}
    }
	
    class Image extends Satori {
        protected $mDbTableAlias = 'images';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Album = $this->HasOne( 'Album', 'Albumid' );
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
            if ( $this->Albumid > 0 ) {
                $this->Album->CommentAdded();
            }
		   
            ++$this->NumComments;
		    return $this->Save();
		}
        public function AddPageview() {
            ++$this->Pageviews;
            return $this->Save();
        }
		public function Delete() {
            $this->DelId = 1;
            $this->Save();

            $this->Album->ImageDeleted( $this );
            --$theuser->Numimages;
            $theuser->Save();

            $finder = New ImageFinder();
            
			// update latest images
            if ( $this->Album->Id == $this->User->EgoAlbum->Id ) {
    			$images = $finder->FindByAlbum( $this->User->EgoAlbum, 0, 1 );
                $frontpageimage = New FrontpageImage( $this->Userid );
                if ( !count( $images ) ) {
                    // no previous images uploaded
                    $frontpageimage->Delete();
                }
                else {
                    $frontpageimage->Imageid = $images[ 0 ]->Id;
                    $frontpageimage->Save();
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
        public function SetTemporaryFile( $value ) {
            $this->mTemporaryFile = $value;

            if ( filesize( $value ) > 1024 * 1024 ) {
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
            if ( strlen( $value ) > 200 ) {
                $value = utf8_substr( $value, 0, 200 );
            }
            $this->mCurrentValues[ 'Description' ] = $value;
        }
        public function Upload( $resizeto = false ) {
            $path = $this->UserId . "/" . $this->Id;

            return Image_Upload( $path, $this->mTemporaryFile, $resizeto );
        }
        public function Save( $resizeto = false ) {
            if ( $this->Exists() ) {
                return parent::Save();
            }
            
            // else: only when creating
            
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
                if ( $this->Album->Id == $this->User->EgoAlbum->Id ) {
                    $frontpageimage = New FrontpageImage( $this->Userid );
                    if ( !$frontpageimage->Exists() ) {
                        $frontpageimage = New FrontpageImage();
                        $frontpageimage->Userid = $this->Userid;
                    }
                    $frontpageimage->Imageid = $this->Id;
                    $frontpageimage->Save();
                }
            }
            
            ++$theuser->Numimages;
            $theuser->Save();
        }
        public function LoadDefaults() {
            $this->Created = NowDate();
            $this->Userip  = UserIp();
            $this->Width   = 0;
            $this->Height  = 0;
        }
    }
?>
