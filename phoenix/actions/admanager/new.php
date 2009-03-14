<?php
    function ActionAdManagerNew(
        tText $title, tText $body, tFile $uploadimage, tText $url
    ) {
        global $libs;
        global $user;
        
        if ( !$user->ExistS() ) {
            retrn; // TODO: This user may be logged out!
        }

        $title = $title->Get();
        $body = $body->Get();
        $url = $url->Get();
        
        $libs->Load( 'admanager' );
        
        if ( $uploadimage->Exists() ) {
            $image = New Image();
            $image->Name = '';
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
            $extension = File_GetExtension( $uploadimage->Name );
            $setTempFile = $image->LoadFromFile( $uploadimage->Tempname );
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
                    alert( 'Υπήρξε ένα πρόβλημα με την αποθήκευση της εικόνας σας' );
                    window.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=admanager/create' );
                    ?>;
                </script><?php
                return;
            }
            $ad->Imageid = $image->Id;
        }
        
        $ad = New Ad();
        $ad->Title = $title;
        $ad->Body = $body;
        $ad->Url = $url;
        $ad->Save();
        
        return Redirect( '?p=admanager/demographics&id=' . $ad->Id );
    }
?>
