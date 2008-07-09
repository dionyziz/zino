<?php

    global $libs;
    $libs->Load( 'image/image' );

    class AlbumFinder extends Finder {
        protected $mModel = 'Album';
        
        public function FindByUser( User $theuser, $offset = 0, $limit = 25 ) {
            $query = $this->mDb->Prepare( "
                SELECT
                    *
                FROM
                    :albums LEFT JOIN :images
                        ON `album_mainimageid` = `image_id`
                WHERE
                    `album_userid` = :userid AND
                    `album_delid` = 0
                ORDER BY
                    `album_id` DESC
                LIMIT
                    :offset, :limit;" );
            
            $query->BindTable( 'albums', 'images' );
            $query->Bind( 'userid', $theuser->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $album = New Album( $row );
                $album->CopyMainimageFrom( New Image( $row ) );
                $album->CopyUserFrom( $theuser );
                if ( $theuser->Egoalbumid == $album->Id ) {
                    array_unshift( $ret, $album );
                }
                else {
                    $ret[] = $album;
                }
            }

            return $ret;
        }
    }
    
    class Album extends Satori {
        protected $mDbTableAlias = 'albums';
        private $mImageTableAlias = 'images';

        public function CopyMainimageFrom( $value ) {
            $this->mRelations[ 'Mainimage' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function Relations() {
            $this->Images = $this->HasMany( 'ImageFinder', 'FindByAlbum', $this );
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Mainimage = $this->HasOne( 'Image', 'Mainimageid' );
        }
        public function SetName( $value ) {
            if ( strlen( $value ) > 100 ) {
                $value = mb_substr( $value, 0, 100 );
            }

            $this->mCurrentValues[ 'Name' ] = $value;
        }
        public function SetDescription( $value ) {
            if ( strlen( $value ) > 200 ) {
                $value = mb_substr( value, 0, 200 );
            }

            $this->mCurrentValues[ 'Description' ] = $value;
        }
		public function IsDeleted() {
			return $this->Delid > 0;
		}
		public function OnBeforeDelete() {
            global $water;
            global $libs;

            $libs->Load( 'event' );
            
            if ( $this->IsDeleted() ) {
                $water->Notice( 'Album already deleted; skipping' );
                return;
            }
            $this->Delid = 1;
            $this->Save();
		    
            --$this->User->Count->Albums;
            $this->User->Count->Save();

            /*
            This would be nicer this way:
            $album->Images->Delete();
            But we'll need Finders to return a collection rather than an array
                                                                -- abresas
            
            For now, use relevant finders to mass delete, similar to how placeids
            are nullified in `users` records using a User finder called from the Place model
            TODO
                                                                -- dionyziz
            */
			$query  = $this->mDb->Prepare("
				UPDATE 
                    :" . $this->mImageTableAlias . "
				SET
					`image_delid` 	= :ImageDelId
				WHERE
				  	`image_albumid` = :AlbumId;
			");
			$query->BindTable( $this->mImageTableAlias );
			$query->Bind( 'ImageDelId', 1 );
			$query->Bind( 'AlbumId', $this->Id );
			$query->Execute();

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            return false;
		}
        public function OnCommentCreate() {
			++$this->Numcomments;
		    $this->Save();	
        }
        public function OnCommentDelete() {
			--$this->Numcomments;
		    $this->Save();	
        }
        public function ImageAdded( Image $image ) {
            $this->Numcomments += $image->Numcomments;
            ++$this->Numphotos;
            if ( $this->Mainimageid == 0 ) {
                $this->Mainimageid = $image->Id;
            }
            $frontpage = New FrontpageImage( $image->Userid );
            if ( !$frontpage->Exists() ) {
                $frontpage = New FrontpageImage();
                $frontpage->Userid = $image->Userid;
            }
            $frontpage->Imageid = $image->Id;
            $frontpage->Save();
            $this->Save();
        }
        public function ImageDeleted( Image $image ) {
            $this->Numcomments -= $image->Numcomments;
            --$this->Numphotos;
            if ( $this->Mainimageid == $image->Id ) {
                $imagefinder = New ImageFinder();
                $images = $imagefinder->FindByAlbum( $this, 0, 1 );
                if ( !empty( $images ) ) {
                    $this->Mainimageid = $images[ 0 ]->Id;
                }
                else {
                    $this->Mainimageid = 0;
                }
            }
            $frontpage = New FrontpageImage( $image->Userid );
            if ( $frontpage->Exists() ) {
                if ( $frontpage->Imageid == $image->Id ) {
                    $finder = New ImageFinder();
                    $oldimage = $finder->FindByUser( $image->User, 0, 1 );
                    if ( $oldimage === false ) {
                        $frontpage->Delete();
                    }
                    else {
                        $frontpage->Imageid = $oldimage->Id;
                        $frontpage->Save();
                    }
                }
            }
            $this->Save();
        }
        public function ImageUndeleted( Image $image ) {
            $this->ImageAdded( $image );
        }
        protected function OnUpdate( $attributes ) {
            if ( isset( $attributes[ 'Mainimageid' ] ) ) {
                if ( $this->User->EgoAlbum->Id == $this->Id ) {
                    $this->User->Avatarid = $this->Mainimageid;
                    $this->User->Save();
                }
            }
        }
        protected function OnCreate() {
            global $libs;
            $libs->Load( 'event' );

            ++$this->User->Count->Albums;
            $this->User->Count->Save();

            /*
            $event = New Event();
            $event->Typeid = EVENT_ALBUM_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();
            */
        }
        public function LoadDefaults() {
			global $user;
			
            $this->Created = NowDate();
			$this->Userid = $user->Id;
			$this->Userip = UserIp();
        }
    }

?>
