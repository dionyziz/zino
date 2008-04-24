<?php
    class AlbumFinder extends Finder {
        protected $mModel = 'Album';
        
        public function FindByUser( User $theuser ) {
            $prototype = New Album();
            $album->UserId = $theuser->Id;
            $album->DelId = 0;
            return $this->FindByPrototype( $prototype );
        }
    }
    
    class Album extends Satori {
        protected $mDbTableAlias = 'albums';

        public function Relations() {
            $this->Images = $this->HasMany( 'ImageFinder', 'FindByAlbum', $this );
            $this->User = $this->HasOne( 'User', 'userid' );
        }
        public function SetName( $value ) {
            if ( strlen( $value ) > 100 ) {
                $value = utf8_substr( $value, 0, 100 );
            }

            $this->mCurrentValues[ 'name' ] = $value;
        }
        public function SetDescription( $value ) {
            if ( strlen( $value ) > 200 ) {
                $value = utf8_substr( value, 0, 200 );
            }

            $this->mCurrentValues[ 'description' ] = $value;
        }
		public function IsDeleted() {
			return $this->DelId > 0;
		}
		public function Delete() {
            global $water;
            
            if ( $this->IsDeleted() ) {
                $water->Notice( 'Album already deleted; skipping' );
                return;
            }
            $this->DelId = 1;
            $this->Save();
			
            // TODO: Encapsulate image update logic into the images model
			$query  = $this->mDb->Prepare("
				UPDATE 
                    :" . $this->mImageTable . "
				SET
					`image_delid` 	= :ImageDelId
				WHERE
				  	`image_albumid` = :AlbumId;
			");
			$query->BindTable( $this->mImageTable );
			$query->Bind( 'ImageDelId', 1 );
			$query->Bind( 'AlbumId', $this->Id );
			$query->Execute();
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
        protected function OnUpdate( $attributes ) {
            if ( isset( $attributes[ 'Mainimage' ] ) ) {
                if ( $this->User->EgoAlbum->Id == $this->Id ) {
                    $this->User->Icon = $this->Mainimage->Id;
                    $this->User->Save();
                }
            }
        }
        public function LoadDefaults() {
            $this->Created = NowDate();
        }
    }

?>
