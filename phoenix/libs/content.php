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
        
        if ( empty( $content ) ) { // if spot is down , find the newest content    
                        
            $finder = New PollFinder();        
            $polls = $finder->FindFrontpageLatest( 0 , 4 );
            $finder = New JournalFinder();
            $journals = $finder->FindFrontpageLatest( 0, 5 ); 
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
        }


        $comments = array();
		$bulk_ids = array();
		$bulk  = array();
		$user_ids = array();
		$users = array();
        $res = array();
        $items = array();
        $commfinder = new CommentFinder();        
        foreach ( $content as $object ) {
            $comments[ $object->Id ] = $commfinder->FindByTypeidAndItemid( Type_FromObject( $object ), $object->Id, 0, 3 );        
            foreach ( $comments[ $object->Id ] as $comment ) {
                $items[] = $comment;
				$bulk_ids[] = $comment[ 'comment_bulkid' ];
				$user_ids[] = $comment[ 'comment_userid' ];
            }            
        }

		$bulk = Bulk::FindById( $bulk_ids );
		$finder = New UserFinder();
		$res = $finder->FindByIds( $user_ids );

		foreach ( $res as $one ) {
			$users [ $one[ 'user_id' ] ] = $one;
		}

		$nea = array();        
        foreach ( $comments as $key=>$val ) {
            $nea = array();
            foreach ( $comments[ $key ] as $obj ) {
                $nea[] = array( 'id'=> $obj[ 'comment_id' ], 
								'parentid' => $obj[ 'comment_parentid' ], 
								'text'=> $bulk[ $obj[ 'comment_bulkid' ] ], 
								'user_name' => $users[ $obj[ 'comment_userid' ] ][ 'user_name' ] , 									'user_subdomain' => $users[ $obj[ 'comment_userid' ] ][ 'user_subdomain' ] 
								);
            }
            $comments[ $key ] = $nea;
        }
	
        foreach ( $content as $object ) {
            $res[ $object->Id ][ "item" ] = $object;
            $res[ $object->Id ][ "comments" ] = $comments[ $object->Id ];
        }        
        
        return $res;
    }
?>
