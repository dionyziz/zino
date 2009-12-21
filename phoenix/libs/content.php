<?php

    function Content_GetContent() {
        global $libs;
        global $user;

        $libs->Load( 'research/spot' );
        $libs->Load( 'comment' );
        $libs->Load( 'poll/frontpage' );
        $libs->Load( 'poll/poll' );
        $libs->Load( 'journal/journal' );
		$libs->Load( 'journal/frontpage' );
        $libs->Load( 'image/image' );

        $content = Spot::GetContent( $user, 8, 5, 4 );
        
        if ( count( $content ) == 0 ) { // if spot is down , find the newest content    
                        
            $finder = New PollFinder();        
            $polls = $finder->FindFrontpageLatest( 0 , 4 );
            $finder = New JournalFinder( 0, 5 );
            $journals = $finder->FindFrontpageLatest(); 
            $finder = New ImageFinder();
            $images = $finder->FindFrontpageLatest( 0, 8 );
            foreach ( $polls as $obj ) {
                $content[] = $obj;
            }
            foreach ( $journals as $obj ) {
                $content[] = $obj;
            }
            foreach ( $images as $obj ) {
                $content[] = $obj;
            }
            $polls = array();
            $images = array();
            $journals = array();
            die( $content );            
        }


        $comments = array();
        $res = array();
        $items = array();
        $commfinder = new CommentFinder();        
        foreach ( $content as $object ) {
            $comments[ $object->Id ] = $commfinder->FindByTypeidAndItemid( Type_FromObject( $object ), $object->Id, 0, 3 );        
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
