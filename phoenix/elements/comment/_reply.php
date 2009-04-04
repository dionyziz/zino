<?php
    
    class ElementCommentReply extends Element {
        protected $mPersistent = array( 'itemid' , 'typeid' , 'userid' , 'avatarid' );
        public function Render( $itemid, $typeid , $userid , $avatarid ) {
            global $user;
            global $page;
            
            ?><div class="comment newcomment">
                <div class="toolbox">
                </div>
                <div class="who"><?php
                    Element( 'user/display' , $user->Id , $user->Avatar->Id , $user );
                    ?>
                </div>
                <div class="text">
                    <textarea rows="" cols=""></textarea>
                </div>
                <div class="bottom">
                    <form onsubmit="return false" action=""><input type="submit" value="Σχολίασε!" onclick="Comments.Create(0);" /></form>
                </div>
                <div style="display:none" id="item"><?php
                echo $itemid;
                ?></div>
                <div style="display:none" id="type"><?php
                echo $typeid;
                ?></div>
            </div><?php
        }
    }
?>
