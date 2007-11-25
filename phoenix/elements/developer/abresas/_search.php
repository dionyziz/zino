<?php

    global $libs;

    $libs->Load( 'search' );

    $comments = new CommentsSearch;
    $comments->TypeId   = 1;
    $comments->Page     = $theuser;
    $comments->DelId    = 0;

    //$comments->OrderBy  = array( 'date', 'DESC' );

    /*
    if ( $oldcomments ) {
        $comments->Limit = 10000;
    }
    else {
        $comments->Limit = 50;
    }
    */

    $comments = $comments->Get();

    print_r( $comments );

?>
