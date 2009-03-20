<?php
    class ElementShoutboxReply extends Element {
        protected $mPersistent = array( 'userid' , 'useravatarid' );
        
        public function Render( $userid , $useravatarid , $user ) {
            global $user;
            
            ?><div class="comment newcomment">
                <div class="who"><a href=""><?php
                    Element( 'user/avatar', $user->Avatar->Id, $user->Id,
                             $user->Avatar->Width, $user->Avatar->Height,
                             $user->Name, 100, 'avatar', '', true, 50, 50 );
                    ?></a>
                </div>
                <div class="text">
                    <input id="shoutbox_text" disabled="disabled" value="" />
                </div>
                <div class="bottom">
                    <div style="border: 1px solid green"></div>
                    <input id="shoutbox_submit" type="submit" value="Σχολίασε!" disabled="disabled" />
                </div>
            </div><?php
        }
    }
?>
