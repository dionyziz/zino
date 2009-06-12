<?php
    function UnitHappeningParticipantList( tInteger $happeningid, tCoalaPointer $f ) {
        global $libs;
        
        $libs->Load( 'happening' );
        
        $happeningid = $happeningid->Get();
        $happening = New Happening( $happeningid );
        
        $participants = array();
        foreach ( $happening->Participants as $participant ) {
            if ( $participant->Certainty != HAPPENING_PARTICIPATION_NO ) {
                $participants[] = array( 
                    $participant->User->Name,
                    $participant->User->Subdomain,
                    $participant->User->Id,
                    $participant->User->Avatarid,
                    $participant->Certainty
                );
            }
        }
        
        echo $f;
        ?>( <?php
        echo w_json_encode( $participants );
        ?> );<?php
    }
?>
