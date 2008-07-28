<?php
    
    class ElementCommentReply extends Element {
        public function Render( $itemid, $typeid ) {
            global $user;
            global $page;
            
            ?><div class="comment newcomment">
                <div class="toolbox">
                    <span class="time">τα σχόλια είναι επεξεργάσιμα για ένα τέταρτο</span>
                </div>
                <div class="who"><?php
                    Element( 'user/display' , $user );
                    ?> πρόσθεσε ένα σχόλιο
                </div>
                <div class="text">
                    <textarea rows="" cols=""></textarea>
                </div>
                <div class="bottom">
                    <form onsubmit="return false;" action=""><input type="submit" value="Σχολίασε!" onclick="Comments.Create(0);" /></form>
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
