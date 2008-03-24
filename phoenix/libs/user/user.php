<?php
    /*
        Developer: Dionyziz
    */

    class UserException extends Exception {
    }

    global $libs;
    $libs->Load( 'user/settings' );
    $libs->Load( 'user/profile' );
    $libs->Load( 'user/space' );
    
    class UserFinder extends Finder {
        protected $mModel = 'User';
        
        public function FindAll() {
            return $this->FindByPrototype( New User() );
        }
        public function FindByNameAndPassword( $username, $password ) {
            $prototype = New User();
            $prototype->Name = $username;
            $prototype->Password = $password;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByIdAndAuthtoken( $username, $authtoken ) {
            $prototype = New User();
            $prototype->Name = $username;
            $prototype->Authtoken = $authtoken;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByName( $name ) {
            $prototype = New User();
            $prototype->Name = $name;
            return $this->FindByPrototype( $prototype );
        }
        public function FindBySubdomain( $subdomain ) {
            $prototype = New User();
            $prototype->Subdomain = $subdomain;
            return $this->FindByPrototype( $prototype );
        }
    }
    class User extends Satori {
        protected $mDbTableAlias = 'users';
        
        public function Relations() {
            $this->Preferences = $this->HasOne( 'UserSettings', 'Id' );
            $this->Profile = $this->HasOne( 'UserProfile', 'Id' );
            $this->Journals = $this->HasMany( 'JournalFinder', 'FindByUser', $this );
            $this->Albums = $this->HasMany( 'AlbumFinder', 'FindByUser', $this );
            $this->Space = $this->HasOne( 'UserSpace', 'Id' );
        }
        public function Delete() {
            throw New UserException( 'Users cannot be deleted' );
        }
        public function HasPermission( $permission ) {
            return $this->Rights >= $permission;
        }
    }
?>
