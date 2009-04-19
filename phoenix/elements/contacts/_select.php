<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email, tText $password ) {
            global $page;
            global $user;
            global $libs;
            
            $libs->Load( 'contacts/fetcher' );
            $libs->Load( 'user/profile' );
            $libs->Load( 'relation/relation' );
            
            $page->SetTitle( "Επιλογή Επαφών" );
            
            $email = $email->Get();
            $email = urldecode( $email );
            $password = $password->Get();         
            echo $email.$password;
            $gmail = New ContactsFetcher();
            if ( $gmail->Login( $email, $password ) ) {
                $contacts = $gmail->Retrieve();
                foreach ( $contacts as $email => $name ) {
                    echo $email . ", ";
                }
            }
            else {
                echo "Login failure - incorrect username/password";
            }
        }
    }
?>
