<?php
	
	function ElementUserSettingsPersonalUniversity( $placeid , $typeid ) {
		global $user;
		
		if ( ( $placeid > 0 ) && ( $typeid == 0 || $typeid == 1 ) ) {
			$finder = New UniFinder();
			//typeid is 0 for AEI and 1 for TEI
			$unis = $finder->Find( $placeid , $typeid );
			?><select>
				<option value="-1"<?php
				if ( $user->Profile->Uniid == 0 ) {
					?> selected="selected"<?php
				}
				?>>-</option><?php
				foreach( $unis as $uni ) {
					?><option value="<?php
					echo $uni->Id;
					?>"<?php
					if ( $user->Profile->Uniid == $unid->Id ) {
						?> selected="selected"<?php
					}
					?>><?php
					echo htmlspecialchars( $uni->Name );
					?></option><?php
				}
			?></select><?php
		}
	}
?>
