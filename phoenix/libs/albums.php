<?php
    class AlbumFinder extends Finder {
        protected $mModel = 'Album';
        
        public function FindByUserId( $userid ) {
            $prototype = New Album();
            $album->UserId = $userid;
            $album->DelId = 0;
            return $this->FindByPrototype( $prototype );
        }
    }
    
    class Album extends Satori {
        protected $mDbTable = 'albums';

        public function Relations() {
            $this->Images = $this->HasMany( 'ImageFinder', 'FindByAlbumId', 'albumid' );
        }
        public function SetName( $value ) {
            if ( strlen( $value ) > 100 ) {
                $value = utf8_substr( $value, 0, 100 );
            }

            $this->mName = $value;
        }
        public function SetDescription( $value ) {
            if ( strlen( $value ) > 200 ) {
                $value = utf8_substr( value, 0, 200 );
            }

            $this->mDescription = $value;
        }
        public function GetUser() {
            if ( $user === false || !$user instanceof User ) {
                $this->mUser = New User( $this->UserId );
            }

            return $this->mUser;
        }
		public function IsDeleted() {
			return $this->DelId > 0;
		}
		public function Delete() {
            $this->DelId = 1;
            $this->Save();
			
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
        public function LoadDefaults() {
            $this->Created = NowDate();
        }
    }

?>
