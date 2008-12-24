<?php
    function GetContacts( $username, $pass , $provider ) {//provider { gmail , hotmail , yahoo }
        global $libs;
        
        $libs->Load( 'contacts/OpenInviter/openinviter' );  
        $providers[ "hotmail" ] = true;
        $providers[ "gmail" ] = true;
        $providers[ "yahoo" ] = true;
        
        if( $providers[ $provider ] == false ) {
            return false;
        }
        
        $inviter = new OpenInviter();
        $inviter->getPlugins();
        $inviter->startPlugin( $provider );
        $state = $inviter->login( $username, $pass );
        if( $state == false ) {
            return false;//Problem login in
        }
        $contacts = $inviter->getMyContacts();
        if( $contacts === false  ) {
            return false;//Problem accessing the contacs
        }
        $inviter->logout();
        $inviter->stopPlugin();
        
        $contact = new Contact();
        foreach ( $contacts as $key=>$val ) {
            $contact->AddContact( $key, $username );
        }        
        return true;
    }    
    
    function EmailFriend( $toemail ) {
            global $user;
            
            $parts = array();
            $parts = explode( '@', $toemail );
            $toname = $parts[ 0 ];            
            
            $subject = 'Πρόσκληση απο τον ' . $user->Name . ' στο Zino';
            //<>TODO
            $message = "Γεια σου $toname,

Ο/Η $user->Name σε πρόσθεσε στους φίλους του στο Zino. Γίνε μέλος στο Zino για να δεις τα προφίλ των φίλων σου, να φτιάξεις το δικό σου, και να μοιραστείς τις φωτογραφίες  και τα νέα σου.

Για να δεις το προφίλ του/της $user->Name στο Zino, πήγαινε στο:
http://$user->Name.zino.gr/

Ευχαριστούμε,
Η Ομάδα του Zino";
            $fromname = 'Zino';//<-TODO
            $fromemail = 'noreply@zino.gr';            //<-TODO
            Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
            return;
    }
    
    class ContactFinder extends Finder {
        protected $mModel = 'Contact';
        
        public function FindByUseridAndMail( $userid, $email ) {
            $prototype = new Contact();
            $prototype->Usermail = $email;
            $prototype->Userid = $userid;
            return $this->FindByPrototype( $prototype, 0, 10000 );
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
            return $not_members;
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
            return $members;
        }
        
        public function FindNotZinoFriendMembersByUseridAndMail( $userid, $email ) {
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
            return $notzino_friends;
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
