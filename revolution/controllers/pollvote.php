<?php
	function Create( $pollid, $optionid ) {
		$success = isset( $_SESSION[ 'user' ] );
		include 'model/db.h';
		include 'model/poll.php';
		$vote->Create( $pollid, $optionid, $_SESSION[ 'user' ][ 'id' ] );
		$poll = Poll::Item( $id );
        $poll !== false or $success = false;
        $options = $poll[ 'options' ];
		include 'view/poll/create';
	}
?>