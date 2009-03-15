<?php

    class UserSettings extends Satori {
        protected $mDbTableAlias = 'usersettings';
       
        public function LoadDefaults() {
            $this->Emailprofilecomment = 'yes';
            $this->Emailjournalcomment = 'yes';
            $this->Emailpollcomment = 'yes';
            $this->Emailphotocomment = 'yes';
            $this->Emailphototag = 'yes';
            $this->Emailreply = 'yes';
            $this->Emailfriendaddition = 'yes';
            $this->Emailfriendjournal = 'no';
            $this->Emailfriendphoto = 'no';
            $this->Emailfriendpoll = 'no';
            $this->Emailfavourite = 'yes';
            $this->Emailbirthday = 'yes';
            $this->Notifyprofilecomment = 'yes';
            $this->Notifyjournalcomment = 'yes';
            $this->Notifypollcomment = 'yes';
            $this->Notifyphotocomment = 'yes';
            $this->Notifyphototag = 'yes';
            $this->Notifyreply = 'yes';
            $this->Notifyfriendaddition = 'yes';
            $this->Notifyfriendjournal = 'no';
            $this->Notifyfriendphoto = 'no';
            $this->Notifyfriendpoll = 'no';
            $this->Notifyfavourite = 'yes';
            $this->Notifybirthday = 'yes';
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
    }

?>
