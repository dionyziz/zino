<?php

    function UnitPollOptionEdit( tInteger $id, tText $text ) {
        global $user;
        global $libs;

        $libs->Load( 'poll' );
    
        $option = New PollOption( $id->Get() );
        $poll   = $option->Poll;

        if ( !$poll->Exists() || $user->IsAnonymous() || $poll->UserId != $user->Id() ) {
            return;
        }

        $option->Text = $text->Get();
        $option->Save();
    }

?>
