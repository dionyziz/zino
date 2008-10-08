<?php
    class ElementUserProfileSidebarView extends Element {
        protected $mPersistent = array( 'theuserid' , 'updated' );
        public function Render( $theuser , $theuserid , $updated ) {            
            ?><div class="sidebar">
                <div class="basicinfo"><?php
                    Element( 'user/profile/sidebar/basicinfo' , $theuser , $theuserid , $updated ); 
                    ?><dl class="online"><dt><strong>Online</strong></dt><dd></dd></dl><?php
                ?></div><?php
                Element( 'user/profile/sidebar/details' , $theuser , $theuseid , $updated );
            ?></div><?php
        }
    }
?>
