<?php
    class ElementCommentList extends Element {
        // protected $mPersistent = array( 'typeid' , 'itemid' );

        public function Render( $comments, $typeid = 0 , $itemid = 0 ) {
            global $water;
            global $page;
            global $user;

            switch ( strtolower( $user->Name ) ) { 
                case 'izual':
                case 'dionyziz':
                    Element( 'comment/comet' , $typeid , $itemid );
            }
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
            foreach ( $comments as $comment ) {
                $indent[ $comment->Id ] = $indent[ $comment->Parentid ] + 1;
                $children = isset( $children_nums[ $comment->Id ] ) ? $children_nums[ $comment->Id ] : 0;
                Element( 'comment/view', $comment, $indent[ $comment->Parentid ], $children );
            }
            if ( !$user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                $page->AttachInlineScript( 'ExcaliburSettings.CommentsDisabled = true;' );
            }
            
            return $indent;
        }   
    }
?>
