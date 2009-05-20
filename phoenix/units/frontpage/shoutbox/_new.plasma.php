<?php
    function UnitFrontpageShoutboxNew( Shout $shout ) {
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
        ?> );alert( unescape('<? echo utf8_encode($shout->Text ); ?>') );<?php
    }
?>
