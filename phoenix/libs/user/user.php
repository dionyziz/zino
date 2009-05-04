<?php
    /*
        Developer: Dionyziz
    */

    class UserException extends Exception {
    }

    global $libs;

    $libs->Load( 'user/permission' );
    $libs->Load( 'user/settings' );
    $libs->Load( 'user/profile' );
    $libs->Load( 'user/lastactive' );
    $libs->Load( 'user/count' );
    $libs->Load( 'image/image' );
    $libs->Load( 'journal' );
    $libs->Load( 'album' );
    $libs->Load( 'mood' );
    $libs->Load( 'question/answer' );
    
    
    function User_Valid( $username ) {
        static $reserved = array(
            'anonymous',
            'www',
            'beta',
            'store',
            'radio',
            'iphone',
            'universe',
            'images',
            'images2',
            'static'
        );
        return ( bool )preg_match( '#^[a-zA-Z][a-zA-Z\-_0-9]{3,19}$#', $username ) && !in_array( $username , $reserved );
    }
    function User_DeriveSubdomain( $username ) {
        /* RFC 1034 - They must start with a letter, 
        end with a letter or digit,
        and have as interior characters only letters, digits, and hyphen.
        Labels must be 63 characters or less. */
        $username = strtolower( $username );
        $username = preg_replace( '/([^a-z0-9-])/i', '-', $username ); //convert invalid chars to hyphens
        $pattern = '/([a-z]+)([a-z0-9-]*)([a-z0-9]+)/i';
        if ( !preg_match( $pattern, $username, $matches ) ) {
            return false;
        }
        return $matches[ 0 ];
    }
    
    class UserFinder extends Finder {
        protected $mModel = 'User';

        public function IsTaken( $username ) {
            if ( $this->FindByName( $username ) !== false ) {
                return true;
            }
            $subdomain = User_DeriveSubdomain( $username );
            if ( $subdomain === false ) {
                return true;
            }
            if ( $this->FindBySubdomain( $subdomain ) !== false ) {
                return true;
            }
            return false;
        }
        public function FindAll( $offset = 0, $limit = 25, $order = false ) {
            return parent::FindAll( $offset, $limit, $order );
        }
        public function FindById( $userid ) {
            global $user;

            if ( $user->Id == $userid ) {
                return $user;
            }
            $prototype = New User();
            $prototype->Id = $userid;            
            return $this->FindByPrototype( $prototype );        
        }
        public function FindByNameAndPassword( $username, $password ) {
            $prototype = New User();
            $prototype->Name = $username;
            $prototype->Password = $password;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByIdAndAuthtoken( $userid, $authtoken ) {
            $prototype = New User();
            $prototype->Id = $userid;
            $prototype->Authtoken = $authtoken;

            return $this->FindByPrototype( $prototype );
        }
        public function FindByName( $name ) {
            global $user;

            if ( strtolower( $user->Name ) == strtolower( $name ) ) {
                return $user;
            }
            $prototype = New User();
            $prototype->Name = $name;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByIds( $ids ) {
            if ( !is_array( $ids ) ) {
                $ids = array( $ids );
            }

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :users
                WHERE
                    `user_id` IN :ids
                LIMIT
                    :limit;'
            );

            $query->BindTable( 'users' );
            $query->Bind( 'ids', $ids );
            $query->Bind( 'limit', count( $ids ) );

            return $this->FindBySqlResource( $query->Execute() );
        }
        public function FindByNames( $names ) {
            if ( !is_array( $names ) ) {
                $names = array( $names );
            }

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :users
                WHERE
                    `user_name` IN :names
                LIMIT
                    :limit;'
            );

            $query->BindTable( 'users' );
            $query->Bind( 'names', $names );
            $query->Bind( 'limit', count( $names ) );

            return $this->FindBySqlResource( $query->Execute() );
        }
        public function FindBySubdomain( $subdomain ) {
            global $user;

            if ( strtolower( $user->Subdomain ) == strtolower( $subdomain ) ) {
                return $user;
            }
            $prototype = New User();
            $prototype->Subdomain = $subdomain;
            return $this->FindByPrototype( $prototype );
        }
        public function FindLatest() {
            return $this->FindByPrototype( New User(), 0, 25, array( 'Created', 'DESC' ) );
        }
        public function FindOnline( $offset = 0, $limit = 100 ) {
            global $xc_settings;
            
            $query = $this->mDb->Prepare(
                'SELECT
                    SQL_CALC_FOUND_ROWS
                    :users.*, :images.*
                FROM
                    :users 
                    CROSS JOIN :lastactive ON 
                        `user_id` = `lastactive_userid`
                    LEFT JOIN :images ON
                        `user_avatarid` = `image_id`
                WHERE
                    `lastactive_updated` > NOW() ' . $xc_settings[ 'mysql2phpdate' ] . ' - INTERVAL 5 MINUTE
                ORDER BY
                    `lastactive_updated` DESC
                LIMIT
                    :offset, :limit'
            );

            $query->BindTable( 'users', 'lastactive', 'images' );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
            $res = $query->Execute();
            $users = array();
            while ( $row = $res->FetchArray() ) {
                $user = New User( $row );
                $user->CopyAvatarFrom( New Image( $row ) );
                $users[] = $user;
            }

            $total = ( int )array_shift( $this->mDb->Prepare(
                'SELECT FOUND_ROWS() AS foundrows;'
            )->Execute()->FetchArray() );

            return New Collection( $users, $total );
        }
        public function FindBySchool( School $school, $offset = 0, $limit = 10000 ) {
            $profilefinder = New UserProfileFinder();
            $userprofiles = $profilefinder->FindBySchool( $school, $offset, $limit ); 
            $userids = array();
            foreach ( $userprofiles as $userprofile ) {
                $userids[] = $userprofile->Userid;
            }
            return $this->FindByIds( $userids );
        }
        public function FindByTag( Tag $tag, $offset = 0, $limit = 20 ) {
            w_assert( $tag instanceof Tag );

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :tags CROSS JOIN :users
                        ON `tag_userid`=`user_id`
                WHERE
                    `tag_text` = :text
                    AND `tag_typeid` = :type
                LIMIT
                    :offset, :limit'
            );
            $query->BindTable( 'tags', 'users' );
            $query->Bind( 'text', $tag->Text );
            $query->Bind( 'type', $tag->Typeid );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            $res = $query->Execute();

            return $this->FindBySQLResource( $res );
        }
        public function FindByBirthday( $month = 3, $day = 17, $offset = 0, $limit = 20 ) {
            if ( !is_int( $month ) || !is_int( $day ) || $month > 12 || $month < 1 || $day < 1 || $day > 31 ) {
                return false;
            }
            
            $query = $this->mDb->Prepare(
                "SELECT
                    `profile_userid`, `relation_userid`
                FROM
                    :userprofiles CROSS JOIN :relations
                        ON `relation_friendid` = `profile_userid`
                WHERE
                    DATE_FORMAT( `profile_dob`, '%m' ) = :month
                    AND DATE_FORMAT( `profile_dob`, '%d' ) = :day
                LIMIT
                    :offset, :limit"
            );
            $query->BindTable( 'userprofiles' );
            $query->BindTable( 'relations' );
            $query->Bind( 'month', $month );
            $query->Bind( 'day', $day );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            $res = $query->Execute();
            
            $arr = array();
            while( $row = $res->FetchArray() ) {
                $arr[] = array( ( int )$row[ 'profile_userid' ], ( int )$row[ 'relation_userid' ] );
            }
            return $arr;
        }
        public function Count() {
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT(*) AS numusers
                FROM
                    :users;'
            );
            $query->BindTable( 'users' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            return ( int )$row[ 'numusers' ];
        }
        public function ClearPlace( $placeid ) {
            $query = $this->mDb->Prepare(
                'UPDATE
                    :users
                SET
                    `placeid` = 0
                WHERE
                    `placeid` = :placeid'
            );
            $query->BindTable( 'users' );
            $query->Bind( 'placeid', $placeid );
            $query->Execute();
        }
    }

    class User extends Satori {
        protected $mDbTableAlias = 'users';
      
        public function CopyAvatarFrom( $value ) {
            $this->mRelations[ 'Avatar' ]->CopyFrom( $value );
        }
        public function CopyProfileFrom( $value ) {
            $this->mRelations[ 'Profile' ]->CopyFrom( $value );
        }
        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Password':
                    $this->mCurrentValues[ 'Password' ] = md5( $value );
                    break;
                default:
                    return parent::__set( $key, $value );
            }
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'Password':
                    throw New UserException( 'User passwords cannot be retrieved, as they are encrypted; use IsCorrectPassword() for comparisons' );
                case 'LastActive':
                    return $this->LastActivity->Updated;
                default:
                    return parent::__get( $key );
            }
        }
        public function IsCorrectPassword( $value ) {
            return md5( $value ) == $this->mCurrentValues[ 'Password' ];
        }
        protected function Relations() {
            $this->Preferences = $this->HasOne( 'UserSettings', 'Id' );
            $this->Profile = $this->HasOne( 'UserProfile', 'Id' );
            $this->Count = $this->HasOne( 'UserCount', 'Id' );
            $this->Journals = $this->HasMany( 'JournalFinder', 'FindByUser', $this );
            $this->Albums = $this->HasMany( 'AlbumFinder', 'FindByUser', $this );
            $this->Answers = $this->HasMany( 'AnswerFinder', 'FindByUser', $this );
            $this->LastActivity = $this->HasOne( 'UserLastActive', 'Id' );
            $this->EgoAlbum = $this->HasOne( 'Album', 'Egoalbumid' );
            $this->Avatar = $this->HasOne( 'Image', 'Avatarid' );
        }
        protected function OnBeforeDelete() {
            foreach ( $this->Albums as $album ) {
                $album->Delete();
            }
            foreach ( $this->Journals as $journal ) {
                $journal->Delete();
            }
            if ( $this->Profile->Exists() ) {
                $this->Profile->Delete();
            }
            if ( $this->Preferences->Exists() ) {
                $this->Preferences->Delete();
            }
        }
        public function HasPermission( $permission ) { 
            return $this->Rights >= $permission;
        }
        protected function LoadDefaults() {
            $this->Rights = 30; // default permissions of user right after registering
            $this->Registerhost = UserIp();
            $this->Created = NowDate();
            $this->RenewAuthtoken(); // create a basic authtoken
        }
        protected function OnConstruct( /* ... */ ) {
            $args = func_get_args();
            if ( count( $args ) == 1 ) {
                if ( is_array( $args[ 0 ] ) ) {
                    if ( count( $args[ 0 ] ) == 0 ) {
                        // construction by empty array -- logged out user
                        $this->ConstructLoggedOut();
                    }
                }
            }
        }
        protected function ConstructLoggedOut() {
            $this->Rights = 10; // logged out permissions
        }
        public function UpdateLastLogin() {
            $this->Lastlogin = NowDate();
        }
        protected function OnBeforeCreate() {
            $this->EgoAlbum->Save();
            $this->Egoalbumid = $this->EgoAlbum->Id;
        }
        protected function OnCreate() {
            global $libs;

            $libs->Load( 'rabbit/helpers/email' );
            $libs->Load( 'pm/pm' );

            $this->EgoAlbum->Ownerid = $this->Id;
            $this->EgoAlbum->Ownertype = TYPE_USERPROFILE;
            $this->EgoAlbum->Save();
            
            $this->OnUpdate();
            PMFolder_PrepareUser( $this );

            $this->Save(); // save again

            ob_start();
            $link = $this->Profile->ChangedEmail( '', $this->Name );
            $subject = Element( 'user/email/welcome', $this, $link );
            $text = ob_get_clean();
            Email( $this->Name, $this->Profile->Email, $subject, $text, "Zino", "noreply@zino.gr" );
        }
        protected function OnUpdate() {
            $this->Profile->Save();
            $this->Preferences->Save();
        }
        public function RenewAuthtokenIfNeeded() {
            if ( empty( $this->Authtoken ) ) { // this shouldn't normally happen
                $this->RenewAuthtoken();
            }
        }
        public function RenewAuthtoken() {
            // generate authtoken
            // first generate 16 random bytes
            // generate 8 pseurandom 2-byte sequences 
            // (that's bad but generally conventional pseudorandom generation algorithms do not allow very high limits
            // unless they repeatedly generate random numbers, so we'll have to go this way)
            $bytes = array(); // the array of all our 16 bytes
            for ( $i = 0; $i < 8 ; ++$i ) {
                $bytesequence = rand( 0, 65535 ); // generate a 2-bytes sequence
                // split the two bytes
                // lower-order byte
                $a = $bytesequence & 255; // a will be 0...255
                // higher-order byte
                $b = $bytesequence >> 8; // b will also be 0...255
                // append the bytes
                $bytes[] = $a;
                $bytes[] = $b;
            }
            // now that we have 16 "random" bytes, create a string of 32 characters,
            // each of which will be a hex digit 0...f
            $authtoken = ''; // start with an empty string
            foreach ( $bytes as $byte ) {
                // each byte is two authtoken digits
                // split them up
                $first = $byte & 15; // this will be 0...15
                $second = $byte >> 4; // this will be 0...15 again
                // convert decimal to hex and append
                // order doesn't really matter, it's all random after all
                $authtoken .= dechex($first) . dechex($second);
            }
            
            $this->Authtoken = $authtoken;
        } 
        // user added a new comment; for profile comments, UserProfile::OnCommentCreate
        public function OnCommentCreate() {
            ++$this->Count->Comments;
            $this->Count->Save();
        }
        public function OnCommentDelete() {
            --$this->Count->Comments;
            $this->Count->Save();
        }
    }
?>
