<?php
    function UnitUserProfileSearchsongs( tText $query ) {
        global $libs;
        
		$libs->Load( 'music/grooveshark' );
		
        $query = $query->Get();
		?>Profile.Player.Addsongs( <?php
			Grooveshark_SearchSong( $query );
		?>);<?php
	} 
?>
