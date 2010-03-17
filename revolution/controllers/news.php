<?php
    function Listing() {
        include 'models/news.php';
		include 'models/poll.php';
		include 'models/journal.php';
		$polls = Poll::ListRecent();
		$journals = Journal::ListRecent();
		
		include 'views/news.php';	
    }
?>
