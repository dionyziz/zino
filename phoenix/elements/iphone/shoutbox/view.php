<?php
    class ElementiPhoneShoutboxView extends Element {
        public function Render( $shout ) {
            global $user;
            
            ?><div class="shout">
                <div class="who"><?php
                    Element( 'user/display' , $shout->User->Id , $shout->User->Avatar->Id , $shout->User );
                ?></div>
                <div class="text"><?php
                    echo nl2br( $shout->Text ); // no htmlspecialchars(); the text is already sanitized
                ?></div>
            </div><?php
        }
    }
?>
