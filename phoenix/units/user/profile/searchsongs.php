<?php
    function UnitUserProfileSearchsongs( tText $query ) {
        global $libs;
        
		$libs->Load( 'music/grooveshark' );
		
        $query->Get();
		?>Profile.Player.Addsongs( <?php
		echo Grooveshark_SearchSong( $query );
		?> );<?php
	}
?>
