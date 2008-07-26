<?php
    
    class ElementUserTrivialPlace extends Element {
        protected $mPersistent = array( 'place' );

        public function Render( $place ) {
            if ( $place->Exists() ) {
                echo htmlspecialchars( $place->Name );
            }
        }
    }
?>
