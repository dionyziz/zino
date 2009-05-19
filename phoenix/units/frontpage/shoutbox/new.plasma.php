<?php
    function UnitFrontpageShoutboxNew( Shout $shout ) {
        ?>Frontpage.Shoutbox.OnMessageArrival( <?php
        echo $shout->Id;
        ?>, <?php
		// Trying to fix ff2 greek characters
        echo w_json_encode( rawurlencode($shout->Text) );
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
