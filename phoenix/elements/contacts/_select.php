<?php
    class ElementContactsSelect extends Element {
        public function Render( tText $email, tText $password ) {
            global $libs;
            
            $libs->Load( 'contacts/fetcher' );
            
            $email = $email->Get();
            $password = $password->Get();

            echo $email.$password;
            $gmail = New ContactsFetcher();
            if ( $gmail->Login( 'ted', '998877' ) ) {
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
