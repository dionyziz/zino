<?php
    //Masked by Chorvus
    //On 29/9/2008
    //Reason: oop optimization
    class ElementCommentView extends Element {
        public function Render( $comment, $indent, $numchildren, $userarray ) {
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
                        echo $comment->Created;
                    ?></span>
                </div><div class="who"><?php
                    Element( 'user/display', $userarray[ 'user_id' ], $userarray[ 'user_avatarid' ] , $comment->User, true );
                    ?>
                </div><div class="text"><?php
                    echo $comment->Text; // no htmlspecialchars(); the text is already sanitized
                ?></div>
                <div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div>
            </div><?php
        }
    }
?>
