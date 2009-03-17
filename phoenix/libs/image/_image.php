<?php
    /*
    Images media structure for images 
    */
    
    global $libs;
    
    $libs->Load( 'album' );
    $libs->Load( 'image/server' );
    $libs->Load( 'image/frontpage' );
    $libs->Load( 'rabbit/helpers/file' );

    define( 'IMAGE_PROPORTIONAL_210x210', '210' );
    define( 'IMAGE_CROPPED_100x100', '100' );
    define( 'IMAGE_CROPPED_150x150', '150' );
    define( 'IMAGE_FULLVIEW', 'full' );
	
	function ProportionalSize( $maxw , $maxh , $imagewidth , $imageheight ) {
        $propw = 1;
        $proph = 1;
        if ( $imagewidth > $maxw ) {
            $propw = $imagewidth / $maxw;
        }
        if ( $imageheight > $maxh ) {
            $proph = $imageheight / $maxh;
        }
        $prop = max( $propw , $proph );
        $size[ 0 ] = round( $imagewidth / $prop , 0 );
        $size[ 1 ] = round( $imageheight / $prop , 0 );
        
        return $size;
    }

    class ImageException extends Exception {
    }

    class ImageFinder extends Finder {
        protected $mModel = 'Image';
        
        public function FindAll( $offset = 0, $limit = 25 ) {
            $image = New Image();
            $image->Delid = 0;
            return $this->FindByPrototype( $image, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByIds( $imageids ) {
            w_assert( is_array( $imageids ), 'ImageFinder->FindByIds() expects an array' );
            foreach ( $imageids as $imageid ) {
                w_assert( is_int( $imageid ), 'Each item of the array passed to ImageFinder->FindByIds() must be an integer' );
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
            $prototype->Userid = $theuser->Id;
            $prototype->Delid = 0;
            
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByAlbum( Album $album, $offset = 0, $limit = 25 ) {
            $prototype = New Image();
            $prototype->Albumid = $album->Id;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindAround( Image $pivot, $limit = 12 ) {
            w_assert( $pivot->Exists(), 'Image->FindAround() must only be called for an existing pivot image' );
            w_assert( $pivot->Album->Exists(), 'Image->FindAround() must only be called for a pivot image within an album' );

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :images
                WHERE
                    `image_albumid` = :albumid
                    AND `image_delid` = :delid'
            );
            $query->BindTable( 'images' );
            $query->Bind( 'albumid', $pivot->Album->Id );
            $query->Bind( 'delid', 0 );
            $res = $query->Execute();

            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[ $row[ 'image_id' ] ] = New Image( $row );
            }
            krsort( $ret );
            
            $i = 0;
            foreach ( $ret as $id => $image ) {
                if ( $id == $pivot->Id ) {
                    break;
                }
                ++$i;
            }
            $begin = $i - floor( $limit / 2 );
            $end = $i + floor( $limit / 2 );
            if ( $begin < 0 ) {
                $begin = 0;
            }
            if ( $end > count( $ret ) ) {
                $end = count( $ret );
            }
            $ret = array_values( array_slice( $ret, $begin, $end - $begin ) );

            return $ret;
        }
        public function FindFrontpageLatest( $offset = 0, $limit = 15 ) {
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
                    LEFT JOIN :users ON
                        `image_userid` = `user_id`
                WHERE
                    `image_id` IN :imageids'
            );
            $query->BindTable( 'images', 'users' );
            $query->Bind( 'imageids', $imageids );
            
            $res = $query->Execute();
            $images = array();
            while ( $row = $res->FetchArray() ) {
                $image = New Image( $row );
                $image->CopyUserFrom( New User( $row ) );
                $images[] = $image;
            }

            $ret = array();
            foreach ( $images as $image ) {
                $ret[ $image->Id ] = $image;
            }
            krsort( $ret );

            return $ret;
        }
        public function Count() {
            $query = $this->mDb->Prepare(
                "SELECT 
                    COUNT(*) AS imagesnum
                FROM 
                    :images
                WHERE
                    `image_delid` = 0;");
            $query->BindTable( 'images' );
            
            $res = $query->Execute();
            $row = $res->FetchArray();

            return ( int )$row[ "imagesnum" ];
        }
    }
    
    class Image extends Satori {
        protected $mDbTableAlias = 'images';
        protected $mTemporaryFile;
        
        public function __get( $key ) {
            global $rabbit_settings;
            
            if ( $key == 'ServerUrl' ) {
                return $rabbit_settings[ 'resourcesdir' ] . '/' . $this->Userid . '/' . $this->Id;
            }

            return parent::__get( $key );
        }
        public function __set( $key, $value ) {
            if ( $key == 'Name' ) {
                if ( mb_strlen( $value ) > 96 ) {
                    $value = mb_substr( $value , 0 , 96 );
                }
                
                $this->mCurrentValues[ 'Name' ] = $value;
            }

            parent::__set( $key, $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Album = $this->HasOne( 'Album', 'Albumid' );
        }
        public function IsDeleted() {
            return $this->Delid > 0 || !$this->Exists();
        }
        public function OnCommentCreate() {
            $frontpageimagefinder = New FrontpageImageFinder();
            $latest = $frontpageimagefinder->FindLatest();
            foreach ( $latest as $photo ) {
                if ( $photo->Imageid == $this->Id ) {
                    Sequence_Increment( SEQUENCE_FRONTPAGEIMAGECOMMENTS );
                    break;
                }
            }

            if ( $this->Albumid ) {
                $this->Album->OnCommentCreate( $this );
            }
           
            ++$this->Numcomments;
            return $this->Save();
        }
        public function OnCommentDelete() {
            $frontpageimagefinder = New FrontpageImageFinder();
            $latest = $frontpageimagefinder->FindLatest();
            foreach ( $latest as $photo ) {
                if ( $photo->Imageid == $this->Id ) {
                    Sequence_Increment( SEQUENCE_FRONTPAGEIMAGECOMMENTS );
                    break;
                }
            }

            if ( $this->Albumid ) {
                $this->Album->OnCommentDelete( $this );
            }

            --$this->Numcomments;
            return $this->Save();
        }
        public function AddPageview() {
            ++$this->Pageviews;
            return $this->Save();
        }
        public function OnBeforeDelete() {
            global $user;
            global $libs;
            
            $libs->Load( 'adminpanel/adminaction' );
            $libs->Load( 'image/tag' );
                        
            if ( $user->Id != $this->Userid ) {
                $adminaction = new AdminAction();
                $adminaction->saveAdminAction( $user->Id, UserIp(), OPERATION_DELETE, TYPE_IMAGE, $this->Id );
            }
            
            $finder = New ImageTagFinder();
            $tags = $finder->FindByImage( $this );
            foreach( $tags as $tag ) {
                $tag->Delete();
            }
        
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();
            
            return false;
        }
        public function Undelete() {
            $this->Delid = 0;
            $this->Save();

            if ( $this->Albumid ) {
                $this->Album->ImageUndeleted( $this );
            }

            $this->OnUndelete();
        }
        public function CommentDeleted() {
            if ( $this->Albumid ) {
                if ( !$this->Album->CommentDeleted() ) {
                    return false;
                }
            }

            --$this->Numcomments;
            return $this->Save();
        }
        public function LoadFromFile( $value ) {
            w_assert( !empty( $value ), 'LoadFromFile() cannot be called with an empty argument' );

            $this->mTemporaryFile = $value;
            w_assert( !empty( $this->mTemporaryFile ), 'Could not set mTemporaryFile' );

            if ( filesize( $value ) > 4 * 1024 * 1024 ) { // 4 MB
                return -1;
            }
            return 0;
        }
        public function Upload() {
            global $water;

            w_assert( !empty( $this->mTemporaryFile ), 'Please call LoadFromFile() before calling Upload(); mTemporaryFile is empty' );

            // throws ImageException
            $data = Image_Upload( $this->Userid, $this->Id, $this->mTemporaryFile );

            // else success
            w_assert( is_array( $data ), 'Image_Upload did not return an array' );

            if ( $data[ 'width' ] < 10 || $data[ 'height' ] < 10 ) {
                throw New ImageException( 'The resolution of target image is too small: ' . $data[ 'width' ] . 'x' . $data[ 'height' ] );
            }

            $this->Width = $data[ 'width' ];
            $this->Height = $data[ 'height' ];
            $this->Size = $data[ 'filesize' ];
            $this->Mime = $data[ 'mime' ];

            return true;
        }
        public function OnBeforeCreate() {
            w_assert( !empty( $this->mTemporaryFile ), 'mTemporaryFile is not set OnBeforeCreate' );

            $this->Size = filesize( $this->mTemporaryFile );
        }
        public function OnCreate() {
            global $libs;

            $libs->Load( 'event' );
            $libs->Load( 'comet' );

            ++$this->User->Count->Images;
            $this->User->Count->Save();

            // throws ImageException
            $upload = $this->Upload();

            parent::Save();

            if ( $this->Albumid ) {
                $this->Album->ImageAdded( $this );
            }

            $event = New Event();
            $event->Typeid = EVENT_IMAGE_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();

            Sequence_Increment( SEQUENCE_IMAGE );            
                        
            Comet_Publish( 'frontpageimage', $this->Id );
        }
        protected function OnDelete() {
            global $libs;
            
            $libs->Load( 'comment' );
            $libs->Load( 'event' );

            --$this->User->Count->Images;
            $this->User->Count->Save();

            if ( $this->Albumid > 0 ) {
                $this->Album->ImageDeleted( $this );
            }

            $finder = New CommentFinder();
            $finder->DeleteByEntity( $this );

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            Sequence_Increment( SEQUENCE_IMAGE );
        }
        protected function OnUndelete() {
            if ( $this->Albumid ) {
                $this->Album->ImageUndeleted( $this );
            }
        }
        public function LoadDefaults() {
            global $user;

            $this->Created = NowDate();
            $this->Userip  = UserIp();
            $this->Width   = 0;
            $this->Height  = 0;
            $this->Userid  = $user->Id;
        }
    }
?>
