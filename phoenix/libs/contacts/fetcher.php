<?php
/* Usage:
$gmail = New ContactsFetcher();
if ( $gmail->Login( 'username', 'password' ) ) {
   $contacts = $gmail->Retrieve();
   foreach ( $contacts as $email => $name ) {
       echo $email . ", ";
   }
}
else {
   echo "Login failure - incorrect username/password";
}
*/
    class ContactsFetcher {
        private $mContacts = Array();
        public $ambiguous = 0;
        private $mPath; //'/var/www/zino.gr/beta/phoenix/libs/contacts/contacts.rb'; 

        public function Add( $email, $name = '' ) {
            if ( strlen( $email ) == 0 || array_key_exists( $email, $this->mContacts ) ) {
                return false;
            }
            $this->mContacts[ $email ] = $name;
            return true;
        }

        public function Retrieve() {
            return $this->mContacts;
        }

        public function Login( $user, $pass ) {
            global $rabbit_settings;
            $this->mPath = $rabbit_settings[ 'rootdir' ] . '/libs/contacts/contacts.rb';
            /* Pipe */
            $descriptorspec = array(
               0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
               1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
               2 => array("pipe", "w") // stderr is a pipe that the child will write to
            );
            
            $process = proc_open('ruby ' . $this->mPath, $descriptorspec, $pipes);
            
            if (!is_resource($process)) {
                die ("Can't execute " . $this->mPath ."!");
            }

            fwrite($pipes[0], "$user\n$pass\n");
            fclose($pipes[0]);        // 0 => stdin
            
            $allcontacts = stream_get_contents($pipes[1]);
            fclose($pipes[1]);        // 1 => stdout
    
            $errors = stream_get_contents($pipes[2]);
            fclose($pipes[2]);        // 2 => stderr
            
            // It is important that you close any pipes before calling proc_close in order to avoid a deadlock
            $return_value = proc_close($process);
            
            /* Exceptions */
            //die ( "$return_value: $allcontacts \n$errors" );
            if ( $return_value == 2 ) { // login failure
                return false;
            }
            
            if ( $return_value != 0 ) { // unknown error
                //die ( "Error: $allcontacts \n$errors" );
                return false;
            }

            /* Parsing */
            
            $pieces = explode("\n", $allcontacts);
            foreach ( $pieces as $piece ) {
                $columns = explode("\t", $piece);
                
                $name = $columns[0];
                
                if ( preg_match_all ( '/([0-9A-Za-z_+=.-]+@[0-9A-Za-z_.=-]{2,})/s' , $columns[1] , $matches , PREG_PATTERN_ORDER ) ) {
                    foreach ( $matches[1] as $email ) {
                        if ( !( $this->Add( $email, $name ) ) ) {
                            $this->ambiguous++;
                        }
                    }
                }
            }

            return true;
        }
    }
?>
