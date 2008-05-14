<?php
    class AlbumFinder extends Finder {
        protected $mModel = 'Album';
        
        public function FindByUser( User $theuser, $offset = 0, $limit = 25 ) {
            $prototype = New Album();
            $album->Userid = $theuser->Id;
            $album->Delid = 0;
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
    }
    
    class Album extends Satori {
        protected $mDbTableAlias = 'albums';
        private $mImageTableAlias = 'images';

        public function Relations() {
            $this->Images = $this->HasMany( 'ImageFinder', 'FindByAlbum', $this );
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        public function SetName( $value ) {
            if ( strlen( $value ) > 100 ) {
                $value = utf8_substr( $value, 0, 100 );
            }

            $this->mCurrentValues[ 'Name' ] = $value;
        }
        public function SetDescription( $value ) {
            if ( strlen( $value ) > 200 ) {
                $value = utf8_substr( value, 0, 200 );
            }

            $this->mCurrentValues[ 'Description' ] = $value;
        }
		public function IsDeleted() {
			return $this->Delid > 0;
		}
		public function Delete() {
            global $water;
            
            if ( $this->IsDeleted() ) {
                $water->Notice( 'Album already deleted; skipping' );
                return;
            }
            $this->Delid = 1;
            $this->Save();
		    
            /*
            This would be nicer this way:
            $album->Images->Delete();
            But we'll need Finders to return a collection rather than an array
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
		}
        public function OnCommentCreate() {
			++$this->NumComments;
		    $this->Save();	
        }
        public function OnCommentDelete() {
			--$this->NumComments;
		    $this->Save();	
        }
        public function ImageDeleted( $image ) {
            $this->NumComments -= $image->NumComments();
            $this->Save();
        }
        protected function OnUpdate( $attributes ) {
            if ( isset( $attributes[ 'Mainimage' ] ) ) {
                if ( $this->User->EgoAlbum->Id == $this->Id ) {
                    $this->User->Icon = $this->Mainimage->Id;
                    $this->User->Save();
                }
            }
        }
        public function LoadDefaults() {
			global $user;
			
            $this->Created = NowDate();
			$this->Userid = $user->Id;
			$this->Submithost = UserIp();
			$this->Pageviews = 1;
        }
    }

?>
