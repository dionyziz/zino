<?php
    class ContactFinder extends Finder {
        protected $mModel = 'Contact';
    }
    
    class Contact extends Satori {
        protected $mDbTableAlias = 'contacts';
        
        public function AddContact( $mail, $usermail ) {
            global $user;
            $contact = new Contact();
            $contact->Mail = $mail;
            $contact->Usermail = $usermail;
            $contact->Userid = $user->Id;
            $contact->Created = DateNow();
            $contact->Save();
            return;
        }
    }
