<?php

    function ElementDeveloperAbresasSearch() {
        global $libs;
        global $user;

        $libs->Load( 'comment' );

        $comments = new CommentsSearch;
        $comments->TypeId   = 1;
        $comments->ItemId   = 832;
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
   
        Element( 'comment/import' );
        Element( 'comment/list', $comments, 0, 0 );
    }

?>
