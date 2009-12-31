<?php

	class ContentStream {

		private static function cmpByDate( $a, $b ) {
			return $a[ "created" ] < $b[ "created" ];
		}

		public function GetContent( $theuser ) {
		    global $libs;

		    $libs->Load( 'research/spot' );
		    $libs->Load( 'comment' );
		    $libs->Load( 'poll/frontpage' );
		    $libs->Load( 'poll/poll' );
		    $libs->Load( 'journal/journal' );
			$libs->Load( 'journal/frontpage' );
		    $libs->Load( 'image/image' );
			$libs->Load( 'bulk' );
			$libs->Load( 'user/user' );

		    $content = Spot::GetContent( $theuser, 8, 5, 4 );
		    
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
		    $commfinder = new CommentFinder();        
		    foreach ( $content as $object ) {
		        $comments[ $object->Id ] = $commfinder->FindByTypeidAndItemid( Type_FromObject( $object ), $object->Id, 0, 3 );        
		        foreach ( $comments[ $object->Id ] as $comment ) {
					$bulk_ids[] = (int)$comment[ 'comment_bulkid' ];
					$user_ids[] = (int)$comment[ 'comment_userid' ];
					
		        }            
		    }

			$bulk = Bulk::FindById( $bulk_ids );
			$finder = New UserFinder();
			$res = $finder->FindByIds( $user_ids );

			foreach ( $res as $one ) {
				$users [ $one->Id ] = $one;
			}

			$nea = array();        
		    foreach ( $comments as $key=>$val ) {
		        $nea = array();
		        foreach ( $comments[ $key ] as $obj ) {
		            $nea[] = array( 'id'=> $obj[ 'comment_id' ], 
									'parentid' => $obj[ 'comment_parentid' ], 
									'text'=> $bulk[ $obj[ 'comment_bulkid' ] ], 
									'user_name' => $users[ $obj[ 'comment_userid' ] ]->Name , 									'user_subdomain' => $users[ $obj[ 'comment_userid' ] ]->Subdomain 
									);
		        }
		        $comments[ $key ] = $nea;
		    }
	
			$res = array();
			$name = array();
		    foreach ( $content as $object ) {
				$name = Type_FromObject( $object ) . "_" . $object->Id;
		        $res[ $name ][ "item" ] = $object;
		        $res[ $name ][ "comments" ] = $comments[ $object->Id ];
				$res[ $name ][ "created" ] = strtotime( $object->Created );
		    }    

			uasort( $res, 'ContentStream::cmpByDate' );  
		    
		    return $res;
		}
	}
?>
