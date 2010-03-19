<?php
    function Listing() {
		include 'models/news.php';
		include 'models/poll.php';
		include 'models/journal.php';
		$polls = Poll::ListRecent( 4 );
		$journals = Journal::ListRecent( 4 );
		
		include 'views/news.php';	
    }
?>
