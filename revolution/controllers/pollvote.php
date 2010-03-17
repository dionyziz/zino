<?php
	function Create( $pollid, $optionid ) {
		$success = isset( $_SESSION[ 'user' ] );
		include 'models/db.php';
		include 'models/poll.php';
		Pollvote::Create( $pollid, $optionid, $_SESSION[ 'user' ][ 'id' ] );
		$poll = Poll::Item( $id );
        $poll !== false or $success = false;
        $options = $poll[ 'options' ];
		include 'views/poll/create';
	}
?>