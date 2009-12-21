<?php

    function Content_GetContent() {
        global $libs;
        global $user;

        $libs->Load( 'research/spot' );
        $libs->Load( 'comment' );

        $content = Spot::GetContent( $user, 8, 5, 4 );

        $comments = array();
        $res = array();
        $items = array();
        $commfinder = new CommentFinder();        
        foreach ( $content as $object ) {
            $comments[ $object->Id ] = $commfinder->FindByTypeidAndItemid( Type_FromObject( $object ), $object->Id, 0, 3 );
            // comments , Text , User Name            
            foreach ( $comments[ $object->Id ] as $comment ) {
                $items[] = $comment;
            }            
        }

        $collection = New CommentCollection( $items, count( $items ) );
        $collection->PreloadRelation( 'User' );
        $collection->PreloadBulk();
        $comms = $collection->ToArrayById();

        
        $nea = array();        
        foreach ( $comments as $key=>$val ) {
            $nea = array();
            foreach ( $comments[ $key ] as $obj ) {
                $nea[] = $comms[ $obj->Id ];
            }
            $comments[ $key ] = $nea;
        }

        foreach ( $content as $object ) {
            $res[ $object->Id ][ "object" ] = $object;
            $res[ $object->Id ][ "comments" ] = $comments[ $object->Id ];
        }        
        
        return $res;
    }
?>
