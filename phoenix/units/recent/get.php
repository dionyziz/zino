<?php
    function UnitRecentGet_Compare( $a, $b ) { // we really need classes here!
        if ( $a->Created < $b->Created ) {
            return 1;
        }
        return -1;
    }
    function UnitRecentGet( tCoalaPointer $f ) {
        global $xc_settings;
        
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
            $item = array(
                'type' => get_class( $event ),
                'created' => strtotime( $event->Created )
            );
            switch ( $item[ 'type' ] ) {
                case 'Comment':
                    $item[ 'text' ] = $event->GetText( 100 );
                    $owner = $event->User;
                    break;
                case 'Image':
                    $item[ 'id' ] = $event->Id;
                    list( $width, $height ) = ProportionalSize( 210, 210, $event->Width, $event->Height );
                    $item[ 'width' ] = $width;
                    $item[ 'height' ] = $height;
                    $owner = $event->User;
                    break;
                case 'Favourite':
                    $type = get_class( $event->Item );
                    switch ( $type ) {
                        case 'Journal':
                            $item[ 'target' ][ 'title' ] = $event->Item->Title;
                            break;
                        case 'Image':
                            list( $width, $height ) = ProportionalSize( 210, 210, $event->Item->Width, $event->Item->Height );
                            $item[ 'target' ] = array(
                                'owner' => array(
                                    'id' => $event->Item->User->Id
                                ),
                                'id' => $event->Item->Id,
                                'width' => $width,
                                'height' => $height
                            );
                            break;
                    }
                    $item[  'target' ][ 'type' ] = $type;
                    $owner = $event->User;
                    break;
                case 'User':
                    $owner = $event;
                    break;
                case 'Poll':
                    $owner = $event->User;
                    break;
                case 'Journal':
                    $owner = $event->User;
                    break;
                case 'Album':
                    if ( $event->Ownertype == TYPE_USERPROFILE ) {
                        $owner = $event->Owner;
                    }
                    else { // school album?
                        continue;
                    }
                    break;
                case 'FriendRelation':
                    list( $width, $height ) = ProportionalSize( 210, 210, $event->Friend->Avatar->Width, $event->Friend->Avatar->Height );
                    $item[ 'target' ] = array(
                        'id' => $event->Friend->Id,
                        'avatar' => $event->Friend->Avatar->Id,
                        'width' => $width,
                        'height' => $height,
                        'subdomain' => $event->Friend->Subdomain,
                        'name' => $event->Friend->Name
                    );
                    $owner = $event->User;
                    break;
                case 'ImageTag':
                    $owner = $event->Owner;
                    break;
                default:
                    w_assert( false, 'Unexpected object type: ' . $item[ 'type' ] );
            }
            w_assert( $owner instanceof User, 'Expected owner to be an instance of User, variable of type ' . gettype( $owner ) . ' given' );
            ob_start();
            Element( 'url', $event );
            $item[ 'url' ] = ob_get_clean();
            $item[ 'who' ] = array(
                'name' => $owner->Name,
                'id' => $owner->Id,
                'avatar' => $owner->Avatar->Id,
                'gender' => $owner->Gender,
                'subdomain' => $owner->Subdomain
            );
            $out[] = $item;
        }
        echo $f;
        ?>( <?php
        echo w_json_encode( $out );
        ?>, <?php
        echo w_json_encode( strtotime( NowDate() ) );
        ?> );<?php
    }
?>
