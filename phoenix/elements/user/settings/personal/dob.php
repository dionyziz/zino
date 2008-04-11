<?php

	function ElementUserSettingsPersonalDob() {
		global $user;
		
		?><select name="day" class="small">
			<option value="-1">-</option><?php
			for ( $i = 1; $i <= 31; ++$i ) {
				?><option value="<?php
				if ( $i <= 9 ) {
					?>0<?php
				}
				echo $i;
				?>"><?php
				if ( $i <= 9 ) {
					?>0<?php
				}
				echo $i;
				?></option><?php
			}
		?></select>
		<select name="month" class="small">
			<option value="-1">-</option>
			<option value="01">Ιανουαρίου</option>
			<option value="02">Φεβρουαρίου</option>
			<option value="03">Μαρτίου</option>
			<option value="04">Απριλίου</option>
			<option value="05">Μαϊου</option>
			<option value="06">Ιουνίου</option>
			<option value="07">Ιουλίου</option>
			<option value="08">Αυγούστου</option>
			<option value="09">Σεπτεμβρίου</option>
			<option value="10">Οκτωβρίου</option>
			<option value="11">Νοεμβρίου</option>
			<option value="12">Δεκεμβρίου</option>
		</select>
		<select name="month" class="small">
			<option value="-">-</option><?php
			for ( $i = 2001; $i >= 1950; --$i ) {
				?><option value="<?php
				echo $i;
				?>"><?php
				echo $i;
				?></option><?php
			}
		?></select><?php
	}
?>
