<?php
    function GetContacts( $username, $pass ) {
        global $libs;
        
        $libs->Load( 'contacts/fetcher' );        
        
        $fetcher = new ContactsFetcher();
        $state = $fetcher->Login( $username, $pass );
        if ( $state == true ) {    	        
	        $contacts = $fetcher->Retrieve();	        
	        $contact = new Contact();
	        foreach ( $contacts as $key=>$val ) {
                $contact->AddContact( $key, $username );
            }
        }
        else {
            return false;//if the failed
        }
        return true;//if contacts added succesfully
    }    
    
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
            $contact->Created = NowDate();
            $contact->Save();
            return;
        }
    }
