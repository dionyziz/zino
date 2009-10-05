<?php
    //Masked by Chorvus
    //On 29/9/2008
    //Reason: oop optimization
    class ElementCommentArrayview extends Element {
        public function Render( $comment, $indent, $numchildren, $theuser ) {
            global $user;
            global $libs;
            global $water;

            $libs->Load( 'comment' );
            
            ?><div id="comment_<?php
            echo $comment[ 'comment_id' ];
            ?>" class="comment oc" style="<?php
            if ( $indent > 0 ) {
                ?>margin-left:<?php
                echo $indent * 20;
                ?>px;<?php
            }
            ?>"><div class="toolbox">
                    <span class="time invisible"><?php
                        echo $comment[ 'comment_created' ];
                    ?></span>
                </div><div class="who"><?php
                    Element( 'user/display', $comment[ 'comment_userid' ], $theuser->Avatarid , $theuser, true );
                    ?>
                </div><div class="text"><?php
                    echo $comment[ 'text' ]; // no htmlspecialchars(); the text is already sanitized
                ?></div>
                <div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div>
            </div><?php
        }
    }
?>
