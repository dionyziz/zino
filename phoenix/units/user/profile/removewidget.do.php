<?php
    function UnitUserProfileRemovewidget() {
        global $libs;
		global $user;

        $libs->Load( 'music/grooveshark' );
        $libs->Load( 'user/profile' );
		
		Grooveshark_DeleteSong();
		?>Profile.Player.Setsong( <?php
		ob_start();
		Element( 'user/profile/sidebar/flash', $user->Profile->Songwidgetid );
		echo w_json_encode( ob_get_clean() );
		?> );<?php

        $user->Profile->Updated = NowDate();
        $user->Profile->Save();
    }
?>
