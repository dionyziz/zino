<?php
	
	class ElementUserSettingsPersonalUniversity extends Element {
		public function Render( $placeid , $typeid ) {
			global $user;
			
			if ( ( $placeid > 0 ) && ( $typeid == 0 || $typeid == 1 ) ) {
				$finder = New UniFinder();
				//typeid is 0 for AEI and 1 for TEI
				$unis = $finder->Find( $placeid , $typeid );
				if ( count( $unis ) > 0 ) {	
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
							if ( $user->Profile->Uniid == $uni->Id ) {
								?> selected="selected"<?php
							}
							?>><?php
							Element( 'user/trivial/university' , $uni );
							?></option><?php
						}
					?></select><?php
				}
				else {
					?><span>Δεν υπάρχουν εκπαιδευτικά ιδρύματα στην περιοχή</span><?php
				}
			}
		}
	}
?>
