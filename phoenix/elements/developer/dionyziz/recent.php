<?php
    class ElementDeveloperDionyzizRecent extends Element {
        public function Render() {
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
        }
    }
?>
