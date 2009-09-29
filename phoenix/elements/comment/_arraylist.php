<?php
    //Masked by chorvus
    class ElementCommentList extends Element {
        // protected $mPersistent = array( 'typeid' , 'itemid' );

        public function Render( $comments, $typeid = 0 , $itemid = 0 ) {
            global $water;
            global $page;
            global $user;

            Element( 'comment/comet' , $typeid , $itemid );
            
            if ( empty( $comments ) ) {
                return; // why did you call me?
            }

            $indent = array(); /* comment_parentid => comment_indent */
            $indent[ 0 ] = 0;
            $children_nums = array();

            foreach ( $comments[ 'comment' ] as $comment ) { 
                if ( !isset( $children_nums[ $comment[ 'comment_parentid'] ] ) ) {
                    $children_nums[ $comment[ 'comment_parentid'] ] = 0;
                }
                $children_nums[ $comment[ 'comment_parentid'] ] = $children_nums[ $comment[ 'comment_parentid'] ] + 1;
            }
            foreach ( $comments as $comment ) {
                $indent[ $comment[ 'comment_id'] ] = $indent[ $comment[ 'comment_parentid'] ] + 1;
                $children = isset( $children_nums[ $comment[ 'comment_id'] ] ) ? $children_nums[ $comment[ 'comment_id'] ] : 0;
                Element( 'comment/arrayview', $comment, $indent[ $comment[ 'comment_parentid'] ], $children, $comments[ 'user' ][ $comment[ 'comment_userid' ] ] );
            }
            if ( !$user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                $page->AttachInlineScript( 'ExcaliburSettings.CommentsDisabled = true;' );
            }
            
            return $indent;
        }   
    }
?>
