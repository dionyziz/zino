<?php
    class ElementCommentComet extends Element {
        public function Render( $typeid , $itemid ) {
            global $page;
            global $user;
            global $rabbit_settings;
            
            ob_start();
            ?>document.domain=<?php
            echo w_json_encode( $rabbit_settings[ 'hostname' ] );
            ?>;Comet.Init( <?php
            echo w_json_encode( uniqid() );
            ?>, 'universe.' + <?php
            echo w_json_encode( $rabbit_settings[ 'hostname' ] );
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
