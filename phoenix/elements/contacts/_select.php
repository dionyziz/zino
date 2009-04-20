<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $username, tText $pass, tText $provider ) {
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            
            $username = $username->Get();
            $pass = $pass->Get();
            $provider = $provider->Get();
            
            $finder = New ContactFinder();
            $ret = $finder->FindByUseridAndMail( $user->Id, $username );
            echo count( $ret )."STEP 1";
            if ( count( $ret ) == 0 ){
                GetContacts( $username, $pass, $provider );
                $ret = $finder->FindByUseridAndMail( $user->Id, $username );
            }
            echo count( $ret )."STEP 2";
            foreach ( $ret as $contact ) {
                echo $contact->Mail;
                ?><br/><?php
                echo $contact->Usermail;
                ?><br/><br/><?php
            }
            return;
        }
    }
?>
