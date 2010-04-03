<?php
    function Listing() {	
		include 'models/db.php';
		include 'models/poll.php';
		include 'models/journal.php';
		$polls = Poll::ListRecent( 4 );
		$journals = Journal::ListRecent( 4 );
        $content = array();
        $i = 0;
        foreach ( $polls as $poll ) {
            $content[ $i ] = $poll;
            $content[ $i ][ 'type' ] = 'poll';
            ++$i;
        }
        foreach ( $journals as $journal ) {
            $content[ $i ] = $journal;
            $content[ $i ][ 'type' ] = 'journal';
            ++$i;
        }
		include 'views/news/listing.php';
    }
?>
