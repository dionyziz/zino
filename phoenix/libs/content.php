<?php

    function getContent() {
        global $libs;
        global $user;

        $libs->Load( 'research/spot' );
        $libs->Load( 'comment' );

        $content = Spot::GetContent( $user, 8, 5, 4 );

        $comments = array();
        $commfinder = new CommentFinder();

        foreach ( $content as $object ) {
            $comments[ $object->Id ] = $commfinder->FindByTypeidAndItemid( Type_FromObject( $object ), $object->Id, 0, 5 );            
        }
        




    }
?>
