<?php
    
    class ElementCommentReply extends Element {
        public function Render( $itemid, $typeid , $userid , $avatarid ) {
            global $user;
            global $page;
            
            ?><div id="newcom" class="comment newcomment">
                <div class="toolbox">
                    <span class="time"></span>
                </div>
                <div class="who"><?php
                    Element( 'user/avatar' , $avatarid , $userid , $user->Avatar->Width , $user->Avatar->Height , $user->Name , 100 , 'avatar' , '' , true , '50' , '50' );
                    ?>
                </div>
                <div class="text">
                    <textarea rows="" cols="">Πρόσθεσε ένα σχόλιο...</textarea>
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
