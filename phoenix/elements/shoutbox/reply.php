<?php
    
    class ElementShoutboxReply extends Element {
        protected $mPersistent = array( 'userid' , 'useravatarid' );
        public function Render( $userid , $useravatarid , $user ) {
            global $user;
            
            ?><div class="comment newcomment">
                <div class="who" style="margin-top:-10px"><?php
                    Element( 'user/display' , $userid , $useravatarid , $user );
                    ?>πρόσθεσε ένα σχόλιο στη συζήτηση
                </div>
                <div class="text">
                    <textarea id="shoutbox_text" rows="2" cols="50" onkeyup="$( '#shoutbox_submit' )[ 0 ].disabled = ( $.trim( this.value ).length == 0 )"></textarea>
                </div>
                <div class="bottom">
                    <input id="shoutbox_submit" type="submit" value="Σχολίασε!" disabled="disabled" />
                </div>
            </div><?php
        }
    }
?>
