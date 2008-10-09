<?php
    class ElementCommentView extends Element {
        public function Render( $comment, $indent, $numchildren ) {
            global $user;
            global $libs;
            global $water;

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
                    <span style="margin-right:<?php
                    if ( !$deletable ) {
                        echo $indent * 20;
                    }
                    else {
                        ?>0<?php
                    }
                    ?>px;" class="time"><?php
                    Element( 'date/diff', $comment->Created );
                    ?></span><?php
                    if ( $deletable ) {
                        ?><a href="" style="margin-right:<?php
                            echo $indent * 20;
                        ?>px;" onclick="return Comments.Delete( <?php
                        echo $comment->Id;
                        ?> );" title="Διαγραφή">&nbsp;</a><?php
                    }
                ?></div><div class="who"><?php
                    Element( 'user/display', $comment->User->Id , $comment->User->Avatar->Id , $comment->User );
                    ?> είπε:
                </div><div class="text"<?php
                if ( ( $user->Id == $comment->User->Id && ( time()-strtotime( $comment->Created ) < 900 ) ) || $user->HasPermission( PERMISSION_COMMENT_EDIT_ALL )  ) {
                    ?> ondblclick="Comments.Edit( <?php
                    echo $comment->Id;
                    ?> );return false"<?php
                }
                ?>><?php
                    echo $comment->Text; // no htmlspecialchars(); the text is already sanitized
                ?></div><?php
                if ( $indent <= 50 && $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                    ?><div class="bottom"><a href="">Απάντησε</a> σε αυτό το σχόλιο</div><?php
                }
            ?></div><?php
        }
    }
?>
