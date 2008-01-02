<?php

	function Albums_List( $user ) {
        global $db;

        $sql = "SELECT
                    *
                FROM
                    :albums
                WHERE
                    `album_userid` = :UserId
                    AND `album_delid` = :DelId;";

        $query = $db->Prepare( $sql );
        $query->BindTable( 'albums' );
        $query->Bind( 'UserId', $user->Id() );
        $query->Bind( 'DelId', 0 );
        
        $query->Execute()->ToObjectsArray( 'Album' );
    }

    class Album extends Satori {
        protected $mDbTable = 'albums';
        protected $mImageTable = 'images';
        protected $mId;
        protected $mUserId;
        protected $mUser;
        protected $mDate;
        protected $mUserIp;
        protected $mName;
        protected $mMainImage;
        protected $mDescription;
        protected $mDelId;
        protected $mPageviews;
        protected $mPhotosNum;
        protected $mCommentsNum;

        public function GetImages( $offset = 0, $limit = 16 ) {
            if ( $offset != 0 ) {
                $offset = ( $offset - 1 ) * $limit;
            }
            
            $sql = "SELECT
                         * 
                    FROM 
                        :" . $this->mImageTable . "
                    WHERE 
                        `image_albumid` = :AlbumId
                        AND `image_delid` = :DelId
                    LIMIT 
                        :Offset, :Limit
                    ;";
            $query->BindTable( $this->mImageTable );
            $query->Bind( 'AlbumId', $this->mId );
            $query->Bind( 'DelId', 0 );
            $query->Bind( 'Offset', $offset );
            $query->Bind( 'Limit', $limit );
            
            return $query->Execute()->ToObjectsArray( 'Image' );
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
