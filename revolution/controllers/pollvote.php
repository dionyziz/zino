<?php
    class ControllerPollvote {
        public static function Create( $pollid, $optionid ) {
            $success = isset( $_SESSION[ 'user' ] );
            include_fast( 'models/db.php' );
            include_fast( 'models/poll.php' );
            PollVote::Create( $pollid, $optionid, $_SESSION[ 'user' ][ 'id' ] );
            $poll = Poll::Item( $pollid );
            $poll !== false or $success = false;
            $options = $poll[ 'options' ];
            include 'views/poll/create.php';
        }
    }
?>
