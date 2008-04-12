<?php

	function ElementUserSettingsCharacteristicsView() {
		?><div>
			<label>Χρώμα μαλλιών</label>
			<div class="setting" id="haircolor"><?php
				Element( 'user/settings/characteristics/haircolor' );
			?></div>
		</div>
		<div>
			<label>Χρώμα ματιών</label>
			<div class="setting" id="eyecolor"><?php
				Element( 'user/settings/characteristics/eyecolor' );
			?></div>
		</div>
		<div>
			<label>Ύψος</label>
			<div class="setting" id="height"><?php
				Element( 'user/settings/characteristics/height' );
			?></div>
		</div>
		<div>
			<label>Βάρος</label>
			<div class="setting" id="weight"><?php
				Element( 'user/settings/characteristics/weight' );
			?></div>
		</div>
		<div>
			<label>Καπνίζεις;</label>
			<div class="setting" id="smoker"><?php
				Element( 'user/settings/characteristics/smoker' );
			?></div>
		</div>
		<div>
			<label>Πίνεις;</label>
			<div class="setting" id="drinker"><?php
				Element( 'user/settings/characteristics/drinker' );
			?></div>
		</div><?php	
		}
?>
