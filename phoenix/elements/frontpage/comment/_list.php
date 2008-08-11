<?php
    class ElementFrontpageCommentList extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'comment' );
            
            $finder = New CommentFinder();
            $comments = $finder->FindLatest( 0 , 5 );
            ?><h2>Σχόλια</h2><?php
                foreach ( $comments as $comment ) {
                    Element( 'frontpage/comment/view' , $comment );
                }
            ?><div class="eof"></div>
            <div class="more"><a href="comments" class="button">Όλα τα σχόλια&raquo;</a></div><?php
        }
    }
?>
