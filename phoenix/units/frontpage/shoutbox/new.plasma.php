<?php
    function UnitFrontpageShoutboxNew( Shout $shout, $channel ) {
		w_assert( is_int( $channel ) );
		
        ?>Frontpage.Shoutbox.OnMessageArrival( <?php
        echo $shout->Id;
        ?>, <?php
        echo w_json_encode( $shout->Text );
        ?>, <?php
        echo w_json_encode( array(
            'id' => $shout->Userid,
            'name' => $shout->User->Name,
            'avatar' => $shout->User->Avatarid,
            'subdomain' => $shout->User->Subdomain
        ) );
        ?>, <?php
		echo $channel;
		?> );<?php
		return 0;
    }
?>
