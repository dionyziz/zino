<?php
    function GetContacts( $username, $pass , $provider ) {//provider { gmail , hotmail , yahoo }
        global $libs;
        
        $libs->Load( 'contacts/OpenInviter/openinviter' );  
        
        $providers = array();//check if valid provider
        $providers[ "hotmail" ] = true;
        $providers[ "gmail" ] = true;
        $providers[ "yahoo" ] = true;
        if( $providers[ $provider ] == false ) {
            return 'ERROR_PROVIDER';
        }
        
        if ( empty( $username ) || empty( $pass ) ) {//check if the password or the username are empty
            return 'ERROR_CREDENTIALS';
        }        

        $inviter = new OpenInviter();
        $inviter->getPlugins();
        $inviter->startPlugin( $provider );
        $state = $inviter->login( $username, $pass );
        if( $state == false ) {
            return 'ERROR_CREDENTIALS';
        }
        $contacts = $inviter->getMyContacts();
        if( $contacts == false  ) {
            return 'ERROR_CONTACTS';
        }        
        $inviter->logout();
        $inviter->stopPlugin();
        
        $contact = new Contact();
        foreach ( $contacts as $key=>$val ) {
            $contact->AddContact( $key, $username );
        }        
        return $contacts;
    }    
    
    function EmailFriend( $emails ) {
        global $user;
    
        foreach ( $emails as $toemail ) {    
            $parts = array();
            $parts = explode( '@', $toemail );
            $toname = $parts[ 0 ];            
            
            $subject = 'Πρόσκληση απο ';
            if ( $user->Gender == 'f' ) {
                $subject .= 'την';
            }
            else {
                $subject .= 'τον';
            }
            $subject .= ' ' . $user->Name . ' στο Zino';
            // TODO: move message/subject to element
            $message = "Γεια σου " . $toname . ",

Σε έχω προσθέσει στους φίλους μου στο Zino. Γίνε μέλος στο Zino για να δεις τα προφίλ των φίλων σου, να φτιάξεις το δικό σου, και να μοιραστείς τις φωτογραφίες και τα νέα σου.

Για να δεις το προφίλ ";
            if ( $user->Gender == 'f' ) {
                $subject .= 'της';
            }
            else {
                $subject .= 'του';
            }
            $message .= ' ' . $user->Name . " στο Zino, πήγαινε στο:
http://" . $user->Subdomain . ".zino.gr/

Ευχαριστώ,
" . $user->Name; // TODO: Add unsubscribe footer
            $fromname = $user->Name;
            $fromemail = 'invite@zino.gr';
            Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
        }
        return;
    }
    
    class ContactFinder extends Finder {
        protected $mModel = 'Contact';
        
        public function FindByUseridAndMail( $userid, $email ) {
            $query = $this->mDb->Prepare(
                'SELECT *
                FROM :contacts
                WHERE `contact_usermail` = :email
                AND `contact_userid` = :id
                GROUP BY `contact_mail` ;
            ');
            $query->BindTable( 'contacts' );
            $query->Bind( 'email', $email );
            $query->Bind( 'id', $userid );
            $res = $query->Execute();
            
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $contact = new Contact( $row );
                $ret []  = $contact;
            }
            
            return $ret;
        
            /*$prototype = new Contact();//<---TESTING NEW
            $prototype->Usermail = $email;
            $prototype->Userid = $userid;
            

            return $this->FindByPrototype( $prototype, 0, 10000 );*/
        }
        
        public function FindNotZinoMembersByUseridAndMail( $userid, $email ) {
            global $libs;            
            $libs->Load( "user/profile" );
        
            $all = $this->FindByUseridAndMail( $userid, $email );//Get all contacts that the user added
            
            $all_emails = array();//Get members only mails
            foreach ( $all as $contact ) {
                $all_emails[] = $contact->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $members = $mailfinder->FindAllUsersByEmails( $all_emails );//Get members ids and emails
            
            $not_members = array();
            foreach ( $all as $sample ) {
                if ( $members[ $sample->Mail ] == NULL ) {
                    $not_members[] = $sample->Mail;
                }
            }
            return $not_members;//<-RETURN array[] = email
        }
        
        public function FindAllZinoMembersByUseridAndMail( $userid, $email ) {
            global $libs;            
            $libs->Load( "user/profile" );
        
            $all = $this->FindByUseridAndMail( $userid, $email );//Get all contacts that the user added
            
            $all_emails = array();//Get members only mails
            foreach ( $all as $contact ) {
                $all_emails[] = $contact->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $members = $mailfinder->FindAllUsersByEmails( $all_emails );//Get members ids and emails
            return $members;//<-RETURN array[ 'profile_email' ] = 'profile_userid'
        }
        
        public function FindNotFriendsZinoMembersByUseridAndMail( $userid, $email ) {
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
        
            $members = $this->FindAllZinoMembersByUseridAndMail( $userid, $email );//Get zino members
            
            $relationfinder = new FriendRelationFinder();//find already zino friends
            $userRelations = $relationfinder->FindByUser( $user );
            $zino_friends = array();
            foreach ( $userRelations as $relation ) {
                $zino_friends[ $relation->Friend->Id ] = true;
            }
            
            $notzino_friends = array();
            foreach ( $members as $key=>$val ) {
                if ( $zino_friends[ $val ] == NULL ) {
                    $notzino_friends[ $key ] = $val;
                }
            }
            return $notzino_friends;//<-RETURN array[ 'profile_email' ] = 'profile_userid'
        }
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
