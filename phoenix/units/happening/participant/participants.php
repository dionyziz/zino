<?php
    function UnitHappeningParticipants( tInteger $happeningid, tCoalaPointer $f ) {
        global $libs;
        
        $libs->Load( 'happening' );
        
        $happeningid = $happeningid->Get();
        $happening = New Happening( $happeningid );
        
        $participants = array();
        foreach ( $participants as $participant ) {
            
        }
        
        echo $f;
        ?>( <?php
        echo w_json_encode( $happening->Participants );
        ?> );<?php
    }
?>
