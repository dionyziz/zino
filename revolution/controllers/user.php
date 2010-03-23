<?php
    function View( $id, $commentpage = 1 ) {
        $id = ( int )$id;
        $commentpage = ( int )$commentpage;
        $commentpage >= 1 or die;
        include 'models/db.php';
        include 'models/comment.php';
        include 'models/user.php';
        $user = User::Item( $id );
        $user !== false or die;
		if ( $user[ 'userdeleted' ] === 1 ) { 
			include 'views/itemdeleted.php';
			return;
		}
        $commentdata = Comment::FindByPage( TYPE_USERPROFILE, $id, $commentpage );
        $numpages = $commentdata[ 0 ];
        $comments = $commentdata[ 1 ];
        $countcomments = $photo[ 'numcomments' ];
        include 'views/user/view.php';
    }
    function Listing( $commentpage = 1 ) {
    }
    function Create() {
    }
    function Update() {
    }
    function Delete() {
    }
?>
