<?php
    class ElementiPhoneShoutboxView extends Element {
        public function Render( $shout ) {
            global $xc_settings;
            global $user;
            
            ?><div class="shout"><a href="<?php
                echo $xc_settings[ 'iphoneurl' ];
                ?>?p=user&subdomain=<?php
                echo $shout->User->Subdomain;
                ?>">
                <span class="who"><?php
                Element( 'user/avatar', $shout->User->Avatar->Id, $shout->User->Id,
                             $shout->User->Avatar->Width, $shout->User->Avatar->Height,
                             $shout->User->Name, 100, 'avatar', '', true, 50, 50 );
                ?></span>
                <span class="text"><strong><?php
                    Element( 'user/name' , $shout->User->Id, $shout->User->Name, $shout->User->Subdomain, false );
                    ?></strong><br /><?php
                    echo nl2br( $shout->Text ); // no htmlspecialchars(); the text is already sanitized
                ?></span>
            </a></div><?php
        }
    }
?>
