<?php

    function ActionImageUpload2( tInteger $albumid , tFile $uploadimage , tInteger $typeid , tString $color ) {
        global $libs;
        global $water;
        global $rabbit_settings;
        global $user;
        
        //$water->DebugThis();
        $libs->Load( 'image/image' );
        $libs->Load( 'rabbit/helpers/file' );

        $typeid = $typeid->Get(); //look beneath for use of typeid
        if ( !$user->Exists() || !$user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
            return Redirect();
        }

        $albumid = $albumid->Get();
        if ( $albumid > 0 ) {
            $album = New Album( $albumid );
            if ( $album->IsDeleted() ) {
                switch ( $album->Ownertype ) {
                    case TYPE_USERPROFILE:
                        $canupload = $album->Owner->Id == $user->Id;
                        break;
                    case TYPE_SCHOOL:
                        $canupload = $user->Profile->Schoolid == $album->Owner->Id; 
                        break;
                    default:
                        $canupload = false;
                }
                if ( !$canupload ) {
                    die( "Not allowed" );
                }
            }
        }
        $extension = File_GetExtension( $uploadimage->Name );
        if ( !( strtolower( $extension ) == 'jpg' || strtolower( $extension ) == 'jpeg' || strtolower( $extension ) == 'png' || strtolower( $extension == 'gif' ) ) ) {
            die( "Not supported filetype" );
        }
        if ( !$uploadimage->Exists() ) {
            if ( $albumid > 0 ) {
                return Redirect( 'index?p=upload&albumid=' . $albumid );
            }
        }
        
        header( 'Content-type: text/html' );
        $image = New Image();
        $image->Name = '';
        $setTempFile = $image->LoadFromFile( $uploadimage->Tempname );
        switch ( $setTempFile ) {
            case -1: // too big file
                ?><script type="text/javascript">
                    alert( 'H φωτογραφία σου δεν πρέπει να ξεπερνάει το 1MB' );
                    window.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=upload&albumid=' . $album->Id );
                    ?>;
                </script><?php
                exit();
            default:
                break;
        }
        $image->Albumid = $albumid;
        try {
            $image->Save();
        }
        catch ( ImageException $e ) {
            //some error must have occured
            ?><html><head><title>Upload error</title><script type="text/javascript">
                alert( 'Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας: ' + <?php
                echo w_json_encode( $e->getMessage() ); 
                ?> );
                window.location.href = <?php
                echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=upload&albumid=' . $album->Id );
                ?>;
            </script></head><body></body></html><?php
            return;
        }
        ?><html>
        <head>
        <title>Upload</title>
        </head>
        <body>
        <script type="text/javascript"><?php
        if ( $albumid > 0 ) {
            //typeid is 0 for album photo upload and 1 for settings avatar upload
            if ( $typeid == 0 ) {
                $album = New Album( $albumid );
                if ( $album->Numphotos == 1 ) {
                    $album->Mainimageid = $image->Id;
                    $album->Save();
                }
                $jsimage = array(
                    'id' => $image->Id,
                    'imagesnum' => $album->Numphotos,
                );
                ?>parent.PhotoList.AddPhoto( <?php
                    echo w_json_encode( $jsimage );
                ?> , false );<?php
            }
            else if ( $typeid == 1 ) {
                ?>parent.Settings.AddAvatar( <?php
                echo $image->Id;
                ?> );<?php
            }
            else if ( $typeid == 2 ) {
                ?>parent.Profile.AddAvatar( <?php
                echo $image->Id;
                ?> );<?php
            }
            else if ( $typeid == 3 ) {
                $album = New Album( $albumid );
                $jsimage = array(
                    'id' => $image->Id,
                    'imagesnum' => $album->Numphotos,
                );
                ?>parent.PhotoList.AddPhoto( <?php
                echo w_json_encode( $jsimage );
                ?> , true );<?php
            }
            else if ( $typeid == 4 ) {
                ?>parent.Profile.Easyuploadadd( <?php
                echo $image->Id;
                ?> );<?php
            }
        } 
        ?>
        window.location.href = <?php
        echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=upload&albumid=' . $albumid . '&typeid=' . $typeid . '&color='.$color );
        ?>;</script></body></html><?php
    }

?>
