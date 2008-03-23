<?php
    /*
        Developer: Dionyziz
    */

    class UserException extends Exception {
    }

    global $libs;
    $libs->Load( 'user/preferences' );
    $libs->Load( 'user/profile' );
    $libs->Load( 'user/space' );
    
    class UserFinder extends Finder {
        protected $mModel = 'User';
        
        public function FindAll() {
            return $this->FindByPrototype( New User() );
        }
        public function FindByNameAndPassword( $username, $password ) {
            $prototype = New User();
            $prototype->Username = $username;
            $prototype->Password = $password;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByIdAndAuthtoken( $username, $authtoken ) {
            $prototype = New User();
            $prototype->Username = $username;
            $prototype->Authtoken = $authtoken;
            return $this->FindByPrototype( $prototype );
        }
    }
    class User extends Satori {
        protected $mDbTableAlias = 'users';
        
        public function Relations() {
            $this->Preferences = $this->HasOne( 'UserPreferences', 'userid' );
            $this->Profile = $this->HasOne( 'UserProfile', 'userid' );
            $this->Journals = $this->HasMany( 'JournalFinder', 'FindByUserId', 'userid' );
            $this->Albums = $this->HasMany( 'AlbumFinder', 'FindByUserId', 'userid' );
            $this->Space = $this->HasOne( 'UserSpace', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'Users cannot be deleted' );
        }
        public function HasPermission( $permission ) {
            return $this->Rights >= $permission;
        }
    }
?>
