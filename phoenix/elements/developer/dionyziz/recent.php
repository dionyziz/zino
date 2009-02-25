<?php
    class ElementDeveloperDionyzizRecent extends Element {
        private function LoadLibs() {
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
        private function MergeEvents( /* ... */ ) {
            $events = func_get_args();
            $ret = array();
            foreach ( $events as $array ) {
                foreach ( $array as $item ) {
                    $ret[] = $item;
                }
            }
            return $ret;
        }
        private function LoadEvents() {
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
            
            return $this->MergeEvents( 
                $comments, $images, $users, $favourites, 
                $polls, $albums, $friends, $imagetags
            );
        }
        private function CompareEvents( $a, $b ) {
            if ( $a->Created < $b->Created ) {
                return -1;
            }
            return 1;
        }
        private function SortEvents( $events ) {
            usort( $events, array( $this, 'CompareEvents' ) );
            
            return $events;
        }
        public function Render() {
            $this->LoadLibs();
            $events = $this->SortEvents( $this->LoadEvents() );
            foreach ( $events as $event ) {
                echo $event->Created . '<br />';
            }
        }
    }
?>
