<?php
    function UnitHappeningParticipantNew( tInteger $happeningid, tText $mobile, tText $firstname ) {
        global $libs;
        global $user;
        
        $libs->Load( 'happening' );
        
        $happeningid = $happeningid->Get();
        $mobile = $mobile->Get();
        $firstname = $firstname->Get();
        
        $happening = New Happening( $happeningid );
        if ( !$happening->Exists() ) {
            ?>alert( 'Could not retrieve happening details' );<?php
            return;
        }
        if ( !$user->Exists() ) {
            ?>window.location.href = '?p=join';<?php
            return;
        }
        $participant = New HappeningParticipant( $happeningid, $user->Id );
        if ( !$participant->Exists() ) {
            $participant = New HappeningParticipant();
            $participant->Happeningid = $happeningid;
            $participant->Certainty = HAPPENING_PARTICIPATION_YES;
            $participant->Save();
        }
        
        $user->Profile->Mobile = $mobile;
        $user->Profile->Firstname = $firstname;
        $user->Profile->Save();
    }
?>
