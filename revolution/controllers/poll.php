<?php
    function View( $id, $commentpage = 1 ) {
        $id = ( int )$id;
        $commentpage = ( int )$commentpage;
        $commentpage >= 1 or die;
        include 'models/db.php';
        include 'models/comment.php';
        include 'models/poll.php';
        include 'models/favourite.php';
        $photo = Poll::Item( $id );
        $photo !== false or die;
        $commentdata = Comment::FindByPage( TYPE_POLL, $id, $commentpage );
        $numpages = $commentdata[ 0 ];
        $comments = $commentdata[ 1 ];
        $countcomments = $photo[ 'numcomments' ];
        $favourites = Favourite::Listing( TYPE_POLL, $id );
        include 'views/poll/view.php';
    }
    function Listing() {
        include 'models/db.php';
        include 'models/poll.php';
        $photos = Poll::ListRecent();
        include 'views/poll/listing.php';
    }
    function Create() {
    }
    function Update() {
    }
    function Delete() {
    }
?>
