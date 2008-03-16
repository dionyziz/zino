<?php
    /*
        Developer: Dionyziz
    */
    
    class UserException extends Exception {
    }
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
    class UserPreferences extends Satori {
        protected $mDbTableAlias = 'userpreferences';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'User preferences cannot be deleted' );
        }
    }
    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'User profiles cannot be deleted' );
        }
    }
    class User extends Satori {
        protected $mDbTableAlias = 'users';
        
        public function GetBanned() {
            return $this->Rights < 10;
        }
        public function Relations() {
            $this->Preferences = $this->HasOne( 'UserPreferences', 'userid' );
            $this->Profile = $this->HasOne( 'UserProfile', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'Users cannot be deleted' );
        }
    }
?>
