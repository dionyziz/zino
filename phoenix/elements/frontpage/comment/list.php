<?php
    class ElementFrontpageCommentList extends Element {
        protected $mPersistent = array( 'commentseq' );
        public function Render( $commentseq ) {
            global $libs;

            $libs->Load( 'comment' );
            
            $finder = New CommentFinder();
            $comments = $finder->FindLatest( 0 , 7 );
            ?><h2>Σχόλια (<a href="comments">προβολή όλων</a>)</h2>
            <div class="list"><?php
                foreach ( $comments as $comment ) {
                    Element( 'frontpage/comment/view' , $comment );
                }
            
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
