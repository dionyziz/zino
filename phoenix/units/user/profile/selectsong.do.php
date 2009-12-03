<?php
    function UnitUserProfileSelectsong( tInteger $songid ) {
        global $libs;
		global $user;

        $libs->Load( 'music/grooveshark' );
        $libs->Load( 'user/profile' );
		
        $songid = $songid->Get();
		Grooveshark_SetSong( $songid );
		?>Profile.Player.Setsong( <?php
		ob_start();
		Element( 'user/profile/sidebar/flash', $user, true );
		echo w_json_encode( ob_get_clean() );
		?> );<?php

        $user->Profile->Updated = NowDate();
        $user->Profile->Save();

    }
?>
