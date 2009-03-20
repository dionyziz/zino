<?php
    function UnitFrontpageShoutboxTyping( User $user, $typing ) {
        w_assert( is_bool( $typing ) );
        if ( $typing ) {
            ?>Frontpage.Shoutbox.OnStartTyping(<?php
            echo w_json_encode( array(
                'gender' => $user->Gender,
                'name' => $user->Name
            ) );
            ?>);<?php
        }
        else {
            ?>Frontpage.Shoutbox.OnStopTyping(<?php
            echo w_json_encode( array(
                'gender' => $user->Gender,
                'name' => $user->Name
            ) );
            ?>);<?php
        }
    }
?>
