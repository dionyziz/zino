<?php
    function UnitFrontpageShoutboxNew( Shout $shout ) {
        ?><script charset="utf-8" type="text/javascript>">alert(1);Frontpage.Shoutbox.OnMessageArrival( <?php
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
        ?> );</script><?php
    }
?>
