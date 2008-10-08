<?php
    class ElementUserProfileSidebarView extends Element {
        public function Render( $theuser ) {            
            ?><div class="sidebar">
                <div class="basicinfo"><?php
                    Element( 'user/profile/sidebar/basicinfo' , $theuser , $theuser->Id , $theuser->Profile->Updated ); 
                    ?><dl class="online"><dt><strong>Online</strong></dt><dd></dd></dl><?php
                ?></div><?php
                Element( 'user/profile/sidebar/details' , $theuser , $theuser->Id , $theuser->Profile->Updated );
            ?></div><?php
        }
    }
?>
