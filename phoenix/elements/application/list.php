<?php
    class ElementApplicationList extends Element {
    
        public function Render() {
            global $libs;
            global $settings;
            
            $libs->Load( 'application' );
            
            $testers = Array( 5104 );
            
            if ( !in_array( $user->Id, $testers ) && !$settings[ 'production' ] ) {
                die( 'Δεν έχεις πρόσβαση σε αυτήν την σελίδα.' );
            }
            
        }
    }
?>