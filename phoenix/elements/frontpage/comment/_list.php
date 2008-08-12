<?php
    class ElementFrontpageCommentList extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'comment' );
            
            $finder = New CommentFinder();
            $comments = $finder->FindLatest( 0 , 7 );
            ?><h2>Σχόλια (<a href="">όλα τα σχόλια</a>)</h2>
            <div class="list"><?php
                foreach ( $comments as $comment ) {
                    Element( 'frontpage/comment/view' , $comment );
                }
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
