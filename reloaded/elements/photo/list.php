<?php
    function ElementPhotoList( tInteger $userid ) {
        global $xc_settings;
        global $user;
        
        if ( !$user->IsSysOp() ) {
            return;
        }
        
        $userid = $userid->Get();
        $theuser = New User( $userid );
        if ( !$theuser->Exists() ) {
            return;
        }
        $search = New Search_Images_Latest( $userid, false );
        $search->SetLimit( 5000 );
        $images = $search->Get();
        foreach ( $images as $image ) {
            ?><img src="<?php
            echo $xc_settings[ 'imagesurl' ];
            ?>/<?php
            echo $userid;
            ?>/<?php
            echo $image->Id();
            ?>" alt="<?php
            echo htmlspecialchars( $image->Name() );
            ?>" /><?php
        }
    }
?>
