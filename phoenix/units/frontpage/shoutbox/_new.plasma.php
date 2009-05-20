<?php
    function UnitFrontpageShoutboxNew( Shout $shout ) {
        ?>Frontpage.Shoutbox.OnMessageArrival( <?php
        echo $shout->Id;
        ?>, <?php
        echo w_json_encode( mb_convert_encoding( $shout->Text, "UTF-8" ) );
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
