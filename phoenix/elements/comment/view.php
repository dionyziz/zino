<?php
    class ElementCommentView extends Element {
        public function Render( $comment, $indent, $numchildren ) {
            global $user;
            global $libs;
            global $water;

            die( 'Breakpoint 608' );

            $libs->Load( 'comment' );
            $deletable = ( $user->Id == $comment->Userid || $user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) && $numchildren == 0;
            
            ?><div id="comment_<?php
            echo $comment->Id;
            ?>" class="comment" style="<?php
            if ( $indent > 0 ) {
                ?>padding-left:<?php
                echo $indent * 20;
                ?>px;<?php
            }
            ?>"><div class="toolbox">
                    <span class="time invisible"><?php
                        echo $comment->Created;
                    ?></span>
                    <a href="" class="invisible" style="margin-right:<?php
                        echo $indent * 20;
                    ?>px;" title="Διαγραφή">&nbsp;</a><?php
                ?></div><div class="who"><?php
                    Element( 'user/display', $comment->User->Id , $comment->User->Avatar->Id , $comment->User );
                    ?> είπε:
                </div><div class="text"><?php
                    echo $comment->Text; // no htmlspecialchars(); the text is already sanitized
                ?></div>
                <div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div>
            </div><?php
        }
    }
?>
