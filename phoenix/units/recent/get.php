<?php
    function UnitRecentGet_Compare( $a, $b ) { // we really need classes here!
        if ( $a->Created < $b->Created ) {
            return 1;
        }
        return -1;
    }
    function UnitRecentGet( tCoalaCallback $f ) {
        /* LoadLibs */ {
            global $libs;
            
            $libs->Load( 'comment' );
            $libs->Load( 'image/image' );
            $libs->Load( 'favourite' );
            $libs->Load( 'poll/poll' );
            $libs->Load( 'journal' );
            $libs->Load( 'album' );
            $libs->Load( 'relation/relation' );
            $libs->Load( 'image/tag' );
        }
        /*
        private function RenderUser( User $user ) {
            return array(
                'name' => $user->Name,
                'avatar' => $user->Avatar,
                'gender' => $user->Gender
            );
        } */
        /*
        private function RenderEvent( $event ) {
            switch ( get_class( $event ) ) {
                case 'Comment':
                    return array(
                        'who' => $this->Render( $event->User ),
                    );
        } */
        /* LoadEvents */ {
            // comments
            $commentfinder = New CommentFinder();
            $comments = $commentfinder->FindLatest();
            // photo uploads
            $imagefinder = New ImageFinder();
            $images = $imagefinder->FindAll();
            // user registrations
            $userfinder = New UserFinder();
            $users = $userfinder->FindAll();
            // favourites
            $favouritefinder = New FavouriteFinder();
            $favourites = $favouritefinder->FindAll();
            // new polls
            $pollfinder = New PollFinder();
            $polls = $pollfinder->FindAll();
            // new journals
            $journalfinder = New JournalFinder();
            $journals = $journalfinder->FindAll();
            // album created
            $albumfinder = New AlbumFinder();
            $albums = $albumfinder->FindAll();
            // poll vote
            // ...
            // friend add
            $friendfinder = New FriendRelationFinder();
            $friends = $friendfinder->FindAll();
            // user profile update
            // ...
            // tagged
            $imagetagfinder = New ImageTagFinder();
            $imagetags = $imagetagfinder->FindAll();
            
            $events = array(
                $comments, $images, $users, $favourites, 
                $polls, $albums, $friends, $imagetags
            );
        }
        /* MergeEvents */ {
            $merged = array();
            foreach ( $events as $array ) {
                foreach ( $array as $item ) {
                    $merged[] = $item;
                }
            }
        }
        /* SortEvents */ {
            usort( $merged, 'UnitRecentGet_Compare' );
        }
        $out = array();
        foreach ( $merged as $event ) {
            $str = w_json_encode( array(
                'type' => get_class( $event ),
                'created' => $event->Created
            ) );
            $out[] = $str;
        }
        echo $f;
        ?>( <?php
        echo w_json_encode( $out );
        ?> );<?php
    }
?>
