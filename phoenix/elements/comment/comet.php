<?php
    class ElementCommentComet extends Element {
        public function Render( $typeid , $itemid ) {
            global $page;
            global $user;
            
            ob_start();
            ?>Comet.Init( <?php
            echo w_json_encode( session_id() );
            ?> );
            Comet.Subscribe( 'CommentsPageNew<?php
            echo $typeid;
            ?>c<?php
            echo $itemid;
            ?>' );<?php

            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>
