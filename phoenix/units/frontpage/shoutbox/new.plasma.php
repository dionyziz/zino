<?php
    function UnitFrontpageShoutboxNew( Shout $shout ) {
        ?>alert('New shout');<?php
        return;
        
        ?>Frontpage.Shoutbox.OnMessageArrival( <?php
        echo $shout->Id;
        ?>, <?php
        echo w_json_encode( $shout->Text );
        ?>, <?php
        echo w_json_encode( array(
            'id' => $shout->User->Id,
            'name' => $shout->User->Name,
            'avatar' => $shout->User->Avatarid,
            'subdomain' => $shout->User->Subdomain
        ) );
        ?> );<?php
    }
?>
