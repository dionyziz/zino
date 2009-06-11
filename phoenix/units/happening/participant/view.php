<?php
    function UnitHappeningParticipants( tInteger $happeningid, tCoalaPointer $f ) {
        global $libs;
        
        $libs->Load( 'happening' );
        
        $happeningid = $happeningid->Get();
        $happening = New Happening( $happeningid );
        
        $participants = array();
        foreach ( $happening->Participants as $participant ) {
            $participants[] = $participant->User->Name;
        }
        
        echo $f;
        ?>( <?php
        echo w_json_encode( $happening->Participants );
        ?> );<?php
    }
?>
