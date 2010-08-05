<?php
    class ControllerPollvote {
        public static function Create( $pollid, $optionid ) {
            $pollid = ( int )$pollid;
            $optionid = ( int )$optionid;
            $success = true;

            if ( isset( $_SESSION[ 'user' ] ) ) {
                clude( 'models/db.php' );
                clude( 'models/poll.php' );
                $poll = Poll::Item( $pollid );
                if ( $poll !== false ) {
                    PollVote::Create( $pollid, $optionid, $_SESSION[ 'user' ][ 'id' ] );
                    $options = $poll[ 'options' ];
                }
                else {
                    $success = false;
                }
            }
            else {
                $success = false;
            }
            
            $myvote = $optionid;
            $poll = Poll::Item( $pollid );
            Template( 'poll/view', compact( 'poll', 'myvote' ) );
        }
    }
?>
