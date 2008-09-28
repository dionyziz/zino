<?php
    
    class ElementNotificationList extends Element {
        public function Render( $notifs ) {
            foreach( $notifs as $notif ) {
                Element( 'notification/view' , $notif );
            }
        }
    }
?>
