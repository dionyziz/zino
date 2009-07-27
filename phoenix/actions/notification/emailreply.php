<?php
    function ActionNotificationEmailReply( tText $rawdata ) {
        file_put_contents( '/home/dionyziz/testbeast', 'THE BEAST WAS HERE: ' . $rawdata );
        /*
        global $libs;
        
        $libs->Load( 'shoutbox' );

        $rawdata = $rawdata->Get();

        $shout = New Shout();
        $shout->Text = 'THE BEAST WAS HERE: ' . $rawdata;
        $shout->Userid = 1;
        $shout->Save();
        */
        ?>The beast has heard you.<?php
    }
?>
