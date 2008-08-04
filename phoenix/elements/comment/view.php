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
            ?>" class="comment" style="border-color:#dee;<?php
            if ( $indent > 0 ) {
                ?>padding-left:<?php
                echo $indent * 20;
                ?>px;<?php
            }
            ?>">
                <div class="toolbox">
                    <a style="margin-right:<?php
                    if( !$deletable ) {
                        echo $indent * 20;
                    } else {
                        echo "0";
                    }
                    ?>px;" class="time" href="#comment_<?php
                    echo $comment->Id;
                    ?>"><?php
                    Element( 'date/diff', $comment->Created );
                    ?></a><?php
                    if ( $deletable ) {
                        ?><a href="" style="margin-right:<?php
                            echo $indent * 20;
                        ?>px;" onclick="Comments.Delete( <?php
                        echo $comment->Id;
                         // There is a reason for the dot. If there's no character between <a></a> the background css image is not shown in IE7
                        ?> );return false;" title="Διαγραφή">.</a><?php
                    }
                ?></div>
                <div class="who"><?php
                    Element( 'user/display', $comment->User );
                    ?> είπε:
                </div>
                <div class="text"<?php
                if ( ( $user->Id == $comment->User->Id && ( time()-strtotime( $comment->Created ) < 900 ) ) || $user->HasPermission( PERMISSION_COMMENT_EDIT_ALL )  ) {
                    ?> ondblclick="Comments.Edit( <?php
                    echo $comment->Id;
                    ?> );return false;"<?php
                }
                ?>><?php
                    echo $comment->Text; // no htmlspecialchars(); the text is already sanitized
                ?></div><?php
                if ( $indent <= 50 && $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                    ?><div class="bottom">
                        <a href="">Απάντησε</a> σε αυτό το σχόλιο
                    </div><?php
                }
            ?></div><?php
        }
    }
?>
