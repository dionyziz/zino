<?php
    class ElementDeveloperFrontpageCommentList extends Element {
        protected $mPersistent = array( 'commentseq' );
        public function Render( $commentseq ) {
            global $libs;

            $libs->Load( 'comment' );
            
            $finder = New CommentFinder();
            $comments = $finder->FindLatest( 0 , 7 );
            ?><h2 class="pheading">Σχόλια <span class="small1">(<a href="comments">προβολή όλων</a>)</span></h2>
            <div class="list"><?php
                foreach ( $comments as $comment ) {
                    Element( 'developer/frontpage/comment/view' , $comment );
                }
            
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
