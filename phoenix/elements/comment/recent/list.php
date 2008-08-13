<?php
/*
 * Developer: Aggelos Orfanakos <me@agorf.gr>
 * License:   MIT
 * Copyright: 2008, Kamibu, http://www.kamibu.com/
 *
 * $Id$
 */

    class ElementCommentRecentList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;

            $pageno = $pageno->Get();

            if ( $pageno <= 0 ) {
                $pageno = 1;
            }

            $libs->Load( 'comment' );

            $finder = New CommentFinder();
            $comments = $finder->FindLatest( ( $pageno - 1 ) * 20, 20 );
            ?><div class="latestcomments">
                <h2>Πρόσφατα σχόλια</h2>
                <div class="list"><?php
                foreach ( $comments as $comment ) {
                    Element( 'frontpage/comment/view', $comment );
                }
                ?></div>
            </div>
            <div class="eof"></div><?php
            Element( 'pagify', $pageno, 'comments?pageno=', ceil( $finder->Count() / 20 ) );
        }
    }
?>
