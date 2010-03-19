<?php
    function View( $id, $commentpage = 1 ) {
        $id = ( int )$id;
        $commentpage = ( int )$commentpage;
        $commentpage >= 1 or die;
        include 'models/db.php';
        include 'models/comment.php';
        include 'models/photo.php';
        include 'models/favourite.php';
        $photo = Photo::Item( $id );
        $photo !== false or die;
		if ( $photo[ 'userdeleted' ] === 1 ) { 
			include 'views/itemdeleted.php';
			return;
		}
        $commentdata = Comment::FindByPage( TYPE_IMAGE, $id, $commentpage );
        $numpages = $commentdata[ 0 ];
        $comments = $commentdata[ 1 ];
        $countcomments = $photo[ 'numcomments' ];
        $favourites = Favourite::Listing( TYPE_IMAGE, $id );
        include 'views/photo/view.php';
    }
    function Listing() {
        include 'models/db.php';
        include 'models/photo.php';
        $photos = Photo::ListRecent();
        include 'views/photo/listing.php';
    }
    function Create() {
    }
    function Update() {
    }
    function Delete() {
    }
?>
