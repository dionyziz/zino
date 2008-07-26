<?php

    class ElementUserTrivialUniversity extends Element {
        protected $mPersistent = array( 'uni' );

        public function Render( $uni ) {
            if ( $uni->Exists() ) {
                echo htmlspecialchars( $uni->Name );
            }
        }
    }
?>
