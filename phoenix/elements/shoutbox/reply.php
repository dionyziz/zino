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
                    <textarea id="shoutbox_text" rows="2" cols="50" disabled="disabled"></textarea>
                </div>
                <div class="bottom">
                    <input id="shoutbox_submit" type="submit" value="Σχολίασε!" disabled="disabled" />
                </div>
            </div><?php
        }
    }
?>
