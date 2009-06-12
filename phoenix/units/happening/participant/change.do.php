<?php
    function UnitHappeningParticipantChange( tInteger $happeningid, tInteger $certainity ) {
        global $libs;
        global $user;
        
        $libs->Load( 'happening' );
        
        $happeningid = $happeningid->Get();
        $certainity = $certainity->Get();
        if ( $certainity < 0 || $certainity > 2 ) {
            ?>alert( 'Certainity number invalid' );<?php
            return;
        }
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
            ?>alert( 'Participation not found' );<?php
            return;
        }
        
        $participant->Certainty = $certainity;
        $participant->Save();
    }
?>