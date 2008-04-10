<?php
	function ElementUserSettingsCharacteristics() {
		global $user;
		?><div>
			<label>Χρώμα μαλλιών</label>
			<div class="setting">
				<select name="haircolor">
					<option value="-">-</option>
					<option value="black">Μάυρα</option>
					<option value="brown">Καστανά</option>
					<option value="red">Κόκκινα</option>
					<option value="blond">Ξανθά</option>
					<option value="highlights">Ανταύγιες</option>
					<option value="grey">Γκρίζα</option>
					<option value="skinhead">Φαλακρός</option><!-- /ή -->
				</select>
			</div>
		</div>
		<div>
			<label>Ύψος</label>
			<div class="setting">
				<select name="height">
					<option value="-1">-</option>
					<option value="-2">Κάτω από 1.20</option><?php
					for ( $i = 120; $i <= 220; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"><?php
						echo $i / 100;
						if ( ( $i % 10 == 0 ) && ( $i % 100 != 0 ) ) {
							?>0<?php
						}
						if ( $i % 100 == 0 ) {
							?>.00<?php
						}
						?></option><?php
					}
					?><option value="-3">Πάνω από 2.20</option>
				</select>
			</div>
		</div>
		<div>
			<label>Βάρος</label>
			<div class="setting">
				<select name="weight">
					<option value="-1">-</option>
					<option value="-2">κάτω από 30kg</option><?php
					for ( $i = 30; $i <= 150; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"><?php
						echo $i;
						?>kg</option><?php
					}
					?><option value="-3">πάνω από 150kg</option><?php
				?></select>
			</div>
		</div>
		<div>
			<label>Καπνίζεις;</label>
			<div class="setting">
				<select name="smoker">
					<option value="-">-</option>
					<option value="yes">Ναι</option>
					<option value="no">Όχι</option>
					<option value="socially">Με παρέα</option>
				</select>
			</div>
		</div>
		<div>
			<label>Πίνεις;</label>
			<div class="setting">
				<select name="drinker">
					<option value="-">-</option>
					<option value="yes">Ναι</option>
					<option value="no">Όχι</option>
					<option value="socially">Με παρέα</option>
				</select>
			</div>
		</div><?php	
	}
?>