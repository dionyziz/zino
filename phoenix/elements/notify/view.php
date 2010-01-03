<?php
    class ElementNotifyView extends Element {
        public function Render( $notif ) {
            if ( !$notif->Exists() || !$notif->Item->Exists() ) {
                return;
            }
        }
    }
?>