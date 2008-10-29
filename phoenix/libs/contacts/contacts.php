<?php
    class Contacts extends Finder {
        protected $mModel = 'Contacts';
    }
    
    class Contacts extends Satori {
        protected $mDbTableAlias = 'contacts';
        
        public function AddContact( $mail, $usermail ) {
            global $user;
            $contact = new Contacts();
            $contact->Mail = $mail;
            $contact->Usermail = $usermail;
            $contact->Userid = $user->Id;
            $contact->Created = DateNow();
            $contact->Save();
            return;
        }
    }
