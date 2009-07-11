<?php
    class ElementApplicationList extends Element {
    
        public function Render() {
            global $libs;
            global $rabbit_settings;
            
            $libs->Load( 'application' );
            
            $testers = Array( 5104 );
            
            if ( !in_array( $user->Id, $testers ) && !$rabbit_settings[ 'production' ] ) {
                ?>Δεν έχεις πρόσβαση σε αυτήν την σελίδα.<?
                var_dump( $rabbit_settings[ 'production' ] );
            }
            else {
                
                
            }
            
        }
    }
?>