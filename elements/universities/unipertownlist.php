<?php
	function ElementUniversitiesUnipertownlist( $townid ) {
		global $libs;
		global $water;
		
		$libs->Load( 'universities' );
		
		$unis = Uni_Retrieve( $townid , false );
		?><select id="modalunisel" onchange="return false;"><?php
		foreach( $unis as $uni ) {
			?><option value="<?php
			echo $uni->Id;
			?>"><?php
			echo $uni->Name;
			?></option><?php
		}
		?></select><?php
	}
?>