<?php

    function getContent() {
        global $libs;
        global $user;

        $libs->Load( 'research/spot' );
        $libs->Load( 'comment' );

        $content = Spot::GetContent( $user, 8, 5, 4 );

        $comments = array();
        $res = array();
        $commfinder = new CommentFinder();

        foreach ( $content as $object ) {
            $comments[ $object->Id ] = $commfinder->FindByTypeidAndItemid( Type_FromObject( $object ), $object->Id, 0, 5 );
            //$res[ $object->Id ] = array( $object, $comments[ $object->Id ] );
            $res[ $object->Id ][ "object" ] = $object;
            $res[ $object->Id ][ "comments" ] = $comments[ $object->Id ];
        }
        
        return $res;
    }
?>
