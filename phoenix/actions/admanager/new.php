<?php
    function ActionAdManagerNew(
        tText $title, tText $body, tFile $uploadimage, tText $url, tInteger $adid
    ) {
        global $libs;
        global $user;
        
        $title = $title->Get();
        $body = $body->Get();
        $url = $url->Get();
        $adid = $adid->Get();
        
        $libs->Load( 'admanager' );
        
        if ( $adid ) {
            // this user may be logged out; TODO
            if ( !$user->HasPermission( PERMISSION_AD_EDIT ) ) {
                return;
            }
            $ad = New Ad( $adid );
            if ( !$ad->Exists() ) {
                ?>Η διαφήμιση που προσπαθείτε να επεξεργαστείτε δεν υπάρχει.<?php
                return;
            }
            if ( $ad->Userid != $user->Id ) {
                ?>Δεν μπορείτε να επεξεργαστείτε μία διαφήμιση που δεν σας ανήκει.<?php
                return;
            }
        }
        else {
            // this user may be logged out; TODO
            if ( !$user->HasPermission( PERMISSION_AD_CREATE ) ) {
                return;
            }
            $ad = New Ad();
        }
        $ad->Title = $title;
        $ad->Body = $body;
        $ad->Url = $url;
        
        if ( $uploadimage->Exists() ) {
            $image = New Image();
            $image->Name = '';
            $extension = File_GetExtension( $uploadimage->Name );
            switch ( strtolower( $extension ) ) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    break;
                default:
                    ?><script type="text/javascript">
                        alert( 'H εικόνα της διαφήμισής σας δεν υποστηρίζεται. Παρακαλούμε χρησιμοποιήστε μία εικόνα jpg ή png.' );
                        window.location.href = <?php
                        echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                        ?>;
                    </script><?php
                return;
            }
            $setTempFile = $image->LoadFromFile( $uploadimage->Tempname );
            $image->SetMaxSize( 200, 85 );
            switch ( $setTempFile ) {
                case -1: // Too big
                    ?><script type="text/javascript">
                        alert( 'H εικόνα της διαφήμισής σας δεν μπορεί να ξεπερνάει το 1MB' );
                        window.location.href = <?php
                        echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                        ?>;
                    </script><?php
                return;
            }
            try {
                $image->Save();
            }
            catch ( ImageException $e ) {
                ?><script type="text/javascript">
                    alert( 'Υπήρξε ένα πρόβλημα με την αποθήκευση της εικόνας σας: ' + <?php
                    echo w_json_encode( $e->getMessage() );
                    ?> );
                    window.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                    ?>;
                </script><?php
                return;
            }
            $ad->Imageid = $image->Id;
        }
        if ( $ad->Exists() ) {
            $ad->Save(); // save changes
            $ret = Redirect( '?p=admanager/list' );
        }
        else {
            $ad->Save(); // create ad
            $ret = Redirect( '?p=admanager/demographics&id=' . $ad->Id . '&canskip=true' );
        }
        
        return $ret;
    }
?>
