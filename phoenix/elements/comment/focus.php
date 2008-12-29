<?php
    class ElementCommentFocus extends Element {
        public function Render( $commentid, $indentation ) {
            global $user;
            global $page;
            
            if ( $commentid !== 0 ) {
                ob_start();
                ?>Comments.Focus(<?php
                echo $commentid;
                ?>,<?php
                echo $indentation;
                ?>,<?php
                if ( $user->Exists() ) {
                    ?>1<?php
                }
                else {
                    ?>0<?php
                }
                ?>)<?php
                $page->AttachInlineScript( ob_get_clean() );
            }
        }
    }
?>
