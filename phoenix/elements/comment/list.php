<?php
    
    /*
    class ElementCommentList extends Element {
        public function Render( $comments ) {
            global $water;
            global $page;
            global $user;

            $water->Trace( 'In comments list: Got ' . count( $comments ) . ' comment parents and ' . count( $comments[ 0 ] ) . ' root comments' );
            $jsarr = "Comments.numchildren = { ";
            $stack = array();
            for ( $i = count( $comments[ 0 ] ) - 1; $i >= 0; --$i ) {
                array_push( $stack, array( $comments[ 0 ][ $i ], 0 ) );
            }
            while ( !empty( $stack ) ) {
                $item = array_pop( $stack );
                $comment = $item[ 0 ];
                $root = $comment->Id;
                $indent = $item[ 1 ];
                $children = isset( $comments[ $root ] ) ? count( $comments[ $root ] ) : 0;

                Element( 'comment/view', $comment, $indent, $children );
                
                $jsarr .= $root . " : " . $children . ", ";
                
                for ( $i = $children - 1; $i >= 0; --$i ) {
                    array_push( $stack, array( $comments[ $root ][ $i ], $indent + 1 ) );
                }
            }
            if ( strlen( $jsarr ) != 25 ) { // page without comments
                $jsarr = substr( $jsarr, 0, -2);
            }
            $jsarr .= " };";
            if ( $user->Id > 0 ) {
                $page->AttachInlineScript( $jsarr );
            }
        }
        */

        class ElementCommentList extends Element {
            public function Render( $comments ) {
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
                $water->Trace( 'comment id is '. $comment->Id );
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
