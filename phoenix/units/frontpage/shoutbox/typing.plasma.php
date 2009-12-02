<?php
    function UnitFrontpageShoutboxTyping( User $user, $typing, $channel ) {
        w_assert( is_bool( $typing ) );
		w_assert( is_int( $channel ) );
        if ( $typing ) {
            ?>Frontpage.Shoutbox.OnStartTyping(<?php
            echo w_json_encode( array(
                'gender' => $user->Gender,
                'name' => $user->Name
            ) );
			?>, <?php
			echo $channel;
			?> );<?php
        }
        else {
            ?>Frontpage.Shoutbox.OnStopTyping(<?php
            echo w_json_encode( array(
                'gender' => $user->Gender,
                'name' => $user->Name
            ) );
			?>, <?php
			echo $channel;
			?> );<?php
        }
		return 0; // TODO: propagate to other users as well, based on channel
    }
?>
