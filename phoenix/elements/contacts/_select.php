<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $username, tText $pass, tText $provider ) {
            global $libs;
            
            $libs->Load( 'contacts/contacts' );
            
            $username = $username->Get();
            $pass = $pass->Get();
            $provider = $provider->Get();
            
            $contacts = GetContacts( $username, $pass, $provider );
            echo $contacts;
            echo $contacts[ 0 ];
            return;
        }
    }
?>
