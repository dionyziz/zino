<?php
    class ElementDeveloperDionyzizSatori extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'user/user' );
            $t = microtime( true );
            $a = array();
            for ( $i = 0; $i < 50; ++$i ) {
                $a[] = New User( array( 'user_id' => $i, 'user_name' => 'dionyziz' ) );
            }
            echo microtime( true ) - $t;
        }
    }
?>
