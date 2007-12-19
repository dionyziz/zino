<?php

	function Albums_List( $user ) {
        global $db;
        global $albums;

        $sql = "SELECT
                    *
                FROM
                    `$albums`
                WHERE
                    `album_userid` = '" . $user->Id() . "' AND
                    `album_delid` = '0'
                ;";

        return $db->Query( $sql )->ToObjectsArray( 'Album' );
    }

    class Album extends Satori {
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

        public function GetImages( $offset = 0, $length = 16 ) {
            if ( $offset != 0 ) {
                $offset = $offset * $length - $length;
            }
            
            $sql = "SELECT
                         * 
                    FROM 
                        `" . $this->mImageTable . "` 
                    WHERE 
                        `image_albumid` = '" . $this->mId . "' AND `image_delid` = '0'
                    LIMIT 
                        " . $offset . " , " . $length . "
                    ;";
                        
            return $db->Query( $sql )->ToObjectsArray( 'Image' );
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
			
			// Prepared query
			$query  = $this->mDb->Prepare("
				UPDATE `" . $this->mImageTable . "`
				SET
					`image_delid` 	= :ImageDelId
				WHERE
				  	`image_albumid` = :AlbumId
				;
			");
			
			// Assign values to query
			$query->Bind( 'ImageDelId', 1 );
			$query->Bind( 'AlbumId', $this->Id );
			
			// Execute query
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
		public function Album( $construct ) {
			global $db;
			global $albums;
            global $images;

            $this->mDb          = $db;
            $this->mDbTable     = $albums;
            $this->mImageTable  = $images;
			
            $this->SetFields( array(
                'album_id'          => 'Id',
                'album_userid'      => 'UserId',
                'album_created'     => 'Date',
                'album_description' => 'Description',
                'album_submithost'        => 'UserIp',
                'album_name'        => 'Name',
                'album_mainimage'   => 'MainImage',
                'album_delid'       => 'DelId',
                'album_pageviews'   => 'Pageviews',
                'album_numcomments' => 'CommentsNum',
                'album_numphotos'   => 'PhotosNum'
            ) );
            
            $this->Satori( $construct );
			
			$this->User			= isset( $construct[ "user_id" ] )      ? New User( $construct )    : "";
		}
    }

?>
