<?php
    function UnitUserProfileSelectsong( tInteger $songid ) {
        global $libs;
		global $user;

        $libs->Load( 'music/grooveshark' );
		
        $songid = $songid->Get();
		Grooveshark_SetSong( $songid );
		?>$( '.sidebar .mplayer' ).html( "<?php
		ob_start();
		Element( 'user/profile/sidebar/player', $user );
		echo w_json_encode( ob_get_clean() );
		?>" );
		Profile.Player.Initialize();
		<?php
    }
?>
