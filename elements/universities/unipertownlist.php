<?php
	function ElementUniversitiesUnipertownlist( $townid ) {
		global $libs;
		global $water;
		
		$libs->Load( 'universities' );
		
		$unis = Uni_Retrieve( $townid->Get() , false );
		if ( count( $unis ) == 0 ) {
			?>Δεν υπάρχουν εκπαιδευτικά ιδρύματα<?php
		}
		else {
			?><select id="modalunisel" onchange="Uni.SaveUni();return false;">
			<option value="0" <?php
			if ( $uniid == 0 ) {
				?>selected="selected"<?php
			}
			?>>(κανένα)</option><?php
			foreach( $unis as $uni ) {
				?><option value="<?php
				echo $uni->Id;
				?>"><?php
				echo htmlspecialchars( $uni->Name );
				?> - <?php
				if ( $uni->TypeId == 0 ) {
					?>ΑΕΙ<?php
				}
				else {
					?>ΤΕΙ<?php
				}
				?></option><?php
			}
			?></select><?php
		}
	}
?>