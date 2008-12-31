<?php
    class ElementiPhoneShoutboxView extends Element {
        public function Render( $shout ) {
            global $user;
            
            ?><div class="shout">
                <div class="who"><?php
                    Element( 'user/avatar', $shout->User->Avatar->Id, $shout->User->Id,
                             $shout->User->Avatar->Width, $shout->User->Avatar->Height,
                             $shout->User->Name, 100, 'avatar', '', true, 50, 50 );
                ?></div>
                <div class="text"><?php
                    echo nl2br( $shout->Text ); // no htmlspecialchars(); the text is already sanitized
                ?></div>
            </div><?php
        }
    }
?>
