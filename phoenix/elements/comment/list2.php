<?php
// UNUSED!

    class ElementCommentList extends Element {
        // protected $mPersistent = array( $typeid, $itemid );

        public function Render( $comments, $typeid = 0, $itemid = 0 ) {
            global $water;
            global $page;
            global $user;

            if ( empty( $comments ) ) {
                return; // why did you call me?
            }

            $indent = array(); /* comment_parentid => comment_indent */
            $indent[ 0 ] = 0;
            $children_nums = array();

            foreach ( $comments as $comment ) { 
                if ( !isset( $children_nums[ $comment->Parentid ] ) ) {
                    $children_nums[ $comment->Parentid ] = 0;
                }
                $children_nums[ $comment->Parentid ] = $children_nums[ $comment->Parentid ] + 1;
            }
            
            $jsarr = "Comments.numchildren = { ";

            foreach ( $comments as $comment ) {
                $indent[ $comment->Id ] = $indent[ $comment->Parentid ] + 1;
                
                $children = isset( $children_nums[ $comment->Id ] ) ? $children_nums[ $comment->Id ] : 0;
                $jsarr .= $comment->Id . " : $children, ";

                Element( 'comment/view', $comment, $indent[ $comment->Parentid ], $children );
            }
            
            $jsarr = substr( $jsarr, 0, -2);
            $jsarr .= " };";

            if ( $user->Id > 0 ) {
                $page->AttachInlineScript( $jsarr );
            }
        }   
    }
?>
