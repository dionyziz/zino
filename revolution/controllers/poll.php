<?php
    function View( $id, $commentpage = 1 ) {
        $id = ( int )$id;
        $commentpage = ( int )$commentpage;
        $commentpage >= 1 or die;
        include 'models/db.php';
        include 'models/comment.php';
        include 'models/poll.php';
        include 'models/favourite.php';
        $poll = Poll::Item( $id );
        $poll !== false or die;
        $commentdata = Comment::FindByPage( TYPE_POLL, $id, $commentpage );
        $numpages = $commentdata[ 0 ];
        $comments = $commentdata[ 1 ];
        $countcomments = $poll[ 'numcomments' ];
        $favourites = Favourite::Listing( TYPE_POLL, $id );
        $options = $poll[ 'options' ];
        include 'views/poll/view.php';
    }
    function Listing() {
        include 'models/db.php';
        include 'models/poll.php';
        $polls = Poll::ListRecent();
        include 'views/poll/listing.php';
    }
    function Create() {
    }
    function Update() {
    }
    function Delete() {
    }
?>
