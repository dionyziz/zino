<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $username, tText $pass, tText $provider ) {
            global $libs;
            global $user;
            
            $libs->Load( 'contacts/contacts' );
            
            $username = $username->Get();
            $pass = $pass->Get();
            $provider = $provider->Get();
            
            $finder = New ContactFinder();
            $ret = $finder->FindByUseridAndMail( $user->Id, $username );
            if ( count( $ret ) == 0 ){
                GetContacts( $username, $pass, $provider );
                $ret = $finder->FindByUseridAndMail( $user->Id, $username );
            }
            echo $ret;
            foreach ( $ret as $contact ) {
                echo $contact->Mail;
                ?><br/><?php
            }
            return;
        }
    }
?>
