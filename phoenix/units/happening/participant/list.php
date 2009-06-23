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
                    'name' => $participant->User->Name,
                    'subdomain' => $participant->User->Subdomain,
                    'userid' => $participant->Userid,
                    'avatarid' => $participant->User->Avatarid,
                    'certainty' => $participant->Certainty
                );
            }
        }
        
        echo $f;
        ?>( <?php
        echo w_json_encode( $participants );
        ?> );<?php
    }
?>
