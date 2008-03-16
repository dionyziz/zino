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
        
        public function Relations() {
            $this->Preferences = $this->HasOne( 'UserPreferences', 'userid' );
            $this->Profile = $this->HasOne( 'UserProfile', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'Users cannot be deleted' );
        }
    }
?>
