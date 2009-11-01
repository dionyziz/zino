<?php

//masked by: Chorvus
//reason: testing purposes, fell free to delete it


    class ElementCommentView extends Element {
        public function Render( $comment, $indent, $numchildren ) {
            global $user;
            global $libs;
            global $water;

            $libs->Load( 'comment' );
            
            ?><div id="comment_<?php
            echo $comment->Id;
            ?>" class="comment oc" style="<?php
            if ( $indent > 0 ) {
                ?>margin-left:<?php
                echo $indent * 20;
                ?>px;<?php
            }
            ?>"><div class="toolbox">
                    <span class="time invisible"><?php
                        var_dump( $comment->Created );
                    ?></span>
                </div><div class="who"><?php
                    Element( 'user/display', $comment->Userid , $comment->User->Avatarid , $comment->User, true );
                    ?>
                </div><div class="text"><?php
                    echo $comment->Text; // no htmlspecialchars(); the text is already sanitized
                ?></div>
                <div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο - test</div>
            </div><?php
        }
    }
?>
