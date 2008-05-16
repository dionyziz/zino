<?php
/* Usage:
$gmail = New ContactsGmail();
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
	class ContactsGmail {
		private $contacts = Array();
		public $ambiguous = 0;
		private $exepath = '/var/www/zino.gr/beta/phoenix/libs/contacts/gmail.rb'; 

		public function Add( $email, $name = '' ) {
			if ( strlen( $email ) == 0 || array_key_exists( $email, $this->contacts ) ) {
				return false;
			}
			$this->contacts[ $email ] = $name;
			return true;
		}

		public function Retrieve() {
			return $this->contacts;
		}

		public function Login( $user, $pass ) {
			/* Pipe */
			$descriptorspec = array(
			   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			   2 => array("pipe", "w") // stderr is a pipe that the child will write to
			);
			
			$process = proc_open('ruby ' . $this->exepath, $descriptorspec, $pipes);
			
			if (!is_resource($process)) {
				die ("Can't execute " . $this->exepath ."!");
			}

			fwrite($pipes[0], "$user\n$pass\n");
			fclose($pipes[0]);		// 0 => stdin
			
			$allcontacts = $this->stdout = stream_get_contents($pipes[1]);
			fclose($pipes[1]);		// 1 => stdout
	
			$errors = $this->stderr = stream_get_contents($pipes[2]);
			fclose($pipes[2]);		// 2 => stderr
			
			// It is important that you close any pipes before calling proc_close in order to avoid a deadlock
			$return_value = $this->return_value = proc_close($process);
			
			/* Exceptions */
			if ( $return_value == 2 ) { // either login failure or changes in gmail's website
				return false;
			}
			
			if ( $return_value != 0 ) { // unknown error
				die ( $errors );
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
