<?php

    class UserSettings extends Satori {
        protected $mDbTableAlias = 'usersettings';
       
        public function LoadDefaults() {
            $this->Emailprofile = 'yes';
            $this->Emailjournals = 'yes';
            $this->Emailpolls = 'yes';
            $this->Emailreplies = 'yes';
            $this->Emailfriends = 'yes';
            $this->Notifyprofile = 'yes';
            $this->Notifyjournals = 'yes';
            $this->Notifypolls = 'yes';
            $this->Notifyreplies = 'yes';
            $this->Notifyfriends = 'yes';
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
    }

?>
