<?php
/*
 * Developer: Aggelos Orfanakos <me@agorf.gr>
 * License:   MIT
 * Copyright: 2008, Kamibu, http://www.kamibu.com/
 *
 * $Id$
 */

function ElementCommentRecentList( tInteger $pageno ) {
    global $libs;

    $libs->Load( 'comment' );

    $finder = New CommentFinder();
    $comments = $finder->FindLatest( ( $pageno - 1 ) * 20, 20 );
    ?><div class="latestcomments">
        <h2>Πρόσφατα σχόλια</h2>
        <div class="list"><?php
        foreach ( $comments as $comment ) {
            Element( 'frontpage/comment/view', $comment );
        }
        ?></div><?php
    ?></div>
    <div class="eof"></div><?php
    Element( 'pagify', $pageno, '?p=comments/recent', ceil( $finder->Count() / 20 ) );
}
?>
