<?php
    function UnitAlbumPhotoTagNew( tInteger $photoid, tText $username, tInteger $left, tInteger $top ) {
        global $user;
        global $libs;

        $libs->Load( 'relation/relation' );
        $libs->Load( 'image/tag' );

        if ( !$user->Exists() ) {
            // require login
            return;
        }

        $photoid = $photoid->Get();
        $username = $username->Get();
        $left = $left->Get();
        $top = $top->Get();

        $photo = New Image( $photoid );
        $userfinder = New UserFinder();
        $theuser = $userfinder->FindByName( $username );

        if ( !$theuser->Exists() ) {
            ?>alert( 'Ο χρήστης αυτός είναι αποκύημα της φαντασίας σας' );
            window.location.reload();<?php
            return;
        }
        if ( !$photo->Exists() ) {
            ?>alert( 'Η φωτογραφία που προσπαθείται να tagαρεται δεν υπάρχει' );
            window.location.reload();<?php
            return;
        }
        // check for permissions
        $photoowner = $photo->User;

        $relationfinder = New FriendRelationFinder();
        // check if user is owner of photo or friend of owner; you can't tag some unknown person's photos
        if ( $photoowner->Id != $user->Id 
             && $relationfinder->IsFriend( $photoowner, $user ) != FRIENDS_BOTH ) {
             ?>alert( 'Δεν έχεις καμία σχέση με τον κάτοχο της φωτογραφίας' );
             window.location.reload();<?php
             return;
        }

        // now check that the tagged person is the friend of the user; you can't tag who doesn't know you
        if ( ( $relationfinder->IsFriend( $theuser, $user ) | FRIENDS_BOTH ) != FRIENDS_BOTH ) {
            ?>alert( 'Ο συγκεκριμένος χρήστης δεν έχει καμία σχέση μαζί σας' );
            window.location.reload();<?php
            return;
        }
        var_dump( $theuser->Id );
        die();
        // all OK, proceed
        $tag = New ImageTag();
        $tag->Imageid = $photoid;
        $tag->Personid = $theuser->Id;
        $tag->Ownerid = $user->Id;
        $tag->Left = $left;
        $tag->Top = $top;
        $tag->Save();
    }
?>
