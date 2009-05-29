<?php
    class ElementApiApi extends Element {
        public function Render( tText $a, tText $user ) {
            switch ( $a ) {
                case 'user':
                    Element( 'api/user', $user->Get() );
                    break;
                case 'friends':
                    Element( 'api/friends', $user->Get() );
                    break;
            }
        }
    }
?>