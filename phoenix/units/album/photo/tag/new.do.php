<?php
    function UnitAlbumPhotoTagNew( tInteger $photoid, tText $username ) {
        global $user;
        global $libs;

        $libs->Load( 'relation/relation' );

        if ( !$user->Exists() ) {
            // require login
            return;
        }

        $photoid = $photoid->Get();
        $username = $username->Get();

        $photo = New Image( $photoid );
        $userfinder = New UserFinder();
        $theuser = $userfinder->FindByName( $username );
        
        //$theuser = New User( $userid );

        if ( !$theuser->Exists() ) {
            // target user does not exist
            return;
        }
        if ( !$photo->Exists() ) {
            // target photo does not exist
            return;
        }
        // check for permissions
        $photoowner = $photo->User;

        $relationfinder = New FriendRelationFinder();
        // check if user is owner of photo or friend of owner; you can't tag some unknown person's photos
        if ( $photoowner->Id != $user->Id 
             && $relationfinder->IsFriend( $photoowner, $user ) | FRIENDS_BOTH == FRIENDS_BOTH ) {
            // the user doing the tag doesn't know the owner of the photo -- don't allow the tagging
            return;
        }

        // now check that the tagged person is the friend of the user; you can't tag who doesn't know you
        if ( $relationfinder->IsFriend( $theuser, $user ) | FRIENDS_BOTH != FRIENDS_BOTH ) {
            // the user doing the tag doesn't know the person they say they know on the picture -- don't allow the tagging
            return;
        }

        // all OK, proceed
        $tag = New ImageTag();
        $tag->Imageid = $photoid;
        $tag->Personid = $theuser->Id;
        $tag->Save();
    }
?>
